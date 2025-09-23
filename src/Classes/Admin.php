<?php


class Admin {
    private $pdo;
    
    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=localhost;dbname=ecoride_db;charset=utf8mb4",
                "root",
                "",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            throw new Exception("Erreur de connexion : " . $e->getMessage());
        }
    }

    /**
     * Statistiques générales du système
     */
    public function getGeneralStats() {
        try {
            $sql = "SELECT 
                        (SELECT COUNT(*) FROM utilisateurs WHERE actif = 1) as total_users,
                        (SELECT COUNT(*) FROM utilisateurs WHERE DATE(date_inscription) = CURDATE()) as new_users_today,
                        (SELECT COUNT(*) FROM covoiturages) as total_trips,
                        (SELECT COUNT(*) FROM covoiturages WHERE DATE(date_creation) = CURDATE()) as new_trips_today,
                        (SELECT COALESCE(SUM(prix * places_disponibles), 0) FROM covoiturages WHERE statut = 'terminee') as total_revenue,
                        (SELECT COALESCE(SUM(prix * places_disponibles), 0) FROM covoiturages WHERE statut = 'terminee' AND DATE(date_creation) = CURDATE()) as revenue_today,
                        (SELECT COALESCE(SUM(co2_economise), 0) FROM covoiturages) as total_co2_saved,
                        (SELECT COALESCE(SUM(co2_economise), 0) FROM covoiturages WHERE DATE(date_creation) = CURDATE()) as co2_saved_today";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des statistiques : " . $e->getMessage());
        }
    }

    /**
     * Utilisateurs récents
     */
    public function getRecentUsers($limite = 10) {
        try {
            $sql = "SELECT u.*, r.nom as role_nom
                    FROM utilisateurs u
                    LEFT JOIN roles r ON u.role_id = r.id
                    ORDER BY u.date_inscription DESC
                    LIMIT :limite";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des utilisateurs récents : " . $e->getMessage());
        }
    }

    /**
     * Trajets récents
     */
    public function getRecentTrips($limite = 10) {
        try {
            $sql = "SELECT c.*, 
                           CONCAT(u.prenom, ' ', u.nom) as conducteur_nom
                    FROM covoiturages c
                    INNER JOIN utilisateurs u ON c.conducteur_id = u.id
                    ORDER BY c.date_creation DESC
                    LIMIT :limite";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des trajets récents : " . $e->getMessage());
        }
    }

    /**
     * Alertes système
     */
    public function getSystemAlerts() {
        $alerts = [];
        
        try {
            // Vérifier les trajets suspects
            $sql = "SELECT COUNT(*) as count FROM covoiturages 
                    WHERE prix < 5 AND date_creation > DATE_SUB(NOW(), INTERVAL 24 HOUR)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $suspiciousTrips = $stmt->fetch()['count'];
            
            if ($suspiciousTrips > 5) {
                $alerts[] = [
                    'type' => 'warning',
                    'title' => 'Trajets suspects détectés',
                    'message' => "$suspiciousTrips trajets avec des prix anormalement bas créés aujourd'hui",
                    'time' => 'Il y a quelques minutes'
                ];
            }
            
            // Vérifier les tentatives de connexion échouées
            $sql = "SELECT COUNT(*) as count FROM login_attempts 
                    WHERE attempted_at > DATE_SUB(NOW(), INTERVAL 1 HOUR) AND attempts >= 3";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $failedLogins = $stmt->fetch()['count'];
            
            if ($failedLogins > 10) {
                $alerts[] = [
                    'type' => 'danger',
                    'title' => 'Attaque par force brute détectée',
                    'message' => "$failedLogins tentatives de connexion échouées dans la dernière heure",
                    'time' => 'Il y a quelques minutes'
                ];
            }
            
            // Vérifier l'espace disque (simulation)
            $diskUsage = 85; // Pourcentage
            if ($diskUsage > 90) {
                $alerts[] = [
                    'type' => 'danger',
                    'title' => 'Espace disque critique',
                    'message' => "Utilisation du disque à $diskUsage%. Action requise.",
                    'time' => 'Il y a 5 minutes'
                ];
            }
            
        } catch (PDOException $e) {
            $alerts[] = [
                'type' => 'danger',
                'title' => 'Erreur base de données',
                'message' => 'Impossible de vérifier l\'état du système',
                'time' => 'Maintenant'
            ];
        }
        
        return $alerts;
    }

    /**
     * Gestion des utilisateurs
     */
    public function getUsersList($filters = [], $page = 1, $limite = 20) {
        try {
            $offset = ($page - 1) * $limite;
            $conditions = [];
            $params = [];
            
            $sql = "SELECT u.*, r.nom as role_nom, 
                           COUNT(c.id) as nb_trajets,
                           COALESCE(AVG(a.note), 0) as note_moyenne
                    FROM utilisateurs u
                    LEFT JOIN roles r ON u.role_id = r.id
                    LEFT JOIN covoiturages c ON u.id = c.conducteur_id
                    LEFT JOIN avis a ON u.id = a.evalue_id";
            
            // Filtres
            if (!empty($filters['role'])) {
                $conditions[] = "u.role_id = :role";
                $params[':role'] = $filters['role'];
            }
            
            if (!empty($filters['statut'])) {
                $conditions[] = "u.actif = :actif";
                $params[':actif'] = $filters['statut'] === 'actif' ? 1 : 0;
            }
            
            if (!empty($filters['recherche'])) {
                $conditions[] = "(u.nom LIKE :recherche OR u.prenom LIKE :recherche OR u.email LIKE :recherche)";
                $params[':recherche'] = '%' . $filters['recherche'] . '%';
            }
            
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
            
            $sql .= " GROUP BY u.id ORDER BY u.date_inscription DESC LIMIT :limite OFFSET :offset";
            
            $stmt = $this->pdo->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
        }
    }

    /**
     * Bannir/Débannir un utilisateur
     */
    public function toggleUserStatus($userId, $actif) {
        try {
            $sql = "UPDATE utilisateurs SET actif = :actif WHERE id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':actif' => $actif ? 1 : 0,
                ':user_id' => $userId
            ]);
            
            return [
                'success' => true,
                'message' => $actif ? 'Utilisateur activé' : 'Utilisateur désactivé'
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la modification : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Statistiques de sécurité
     */
    public function getSecurityStats() {
        try {
            $sql = "SELECT 
                        DATE(attempted_at) as date,
                        COUNT(*) as failed_attempts
                    FROM login_attempts 
                    WHERE attempted_at > DATE_SUB(NOW(), INTERVAL 30 DAY)
                    GROUP BY DATE(attempted_at)
                    ORDER BY date";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>