<?php
require_once __DIR__ . '/Database.php';

class Covoiturage {
    private $conn;

    public function __construct() {
        // Utiliser la connexion via Singleton
        $this->conn = Database::getConnection();
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM covoiturages ORDER BY date_depart DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Créer un nouveau trajet de covoiturage
     */
    public function createTrajet($depart, $arrivee, $date, $heure, $places, $prix, $userId) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO covoiturages 
                (lieu_depart, lieu_arrivee, date_depart, heure_depart, nombre_places, nombre_places_disponibles, prix_par_personne, conducteur_id, statut, date_creation) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'actif', NOW())
            ");
            
            $result = $stmt->execute([$depart, $arrivee, $date, $heure, $places, $places, $prix, $userId]);
            
            if ($result) {
                return $this->conn->lastInsertId();
            }
            return false;
            
        } catch (PDOException $e) {
            error_log("Erreur createTrajet: " . $e->getMessage());
            return false;
        }
    }
      /**
     * Récupérer tous les covoiturages publics disponibles
     */
    public function getPublicTrajets($limit = 10, $offset = 0) {
        try {
            $stmt = $this->conn->prepare("
                SELECT c.*, 
                       u.prenom, 
                       u.nom,
                       u.email,
                       c.nombre_places_disponibles as places_restantes,
                       CONCAT(u.prenom, ' ', u.nom) as conducteur_nom,
                       DATE_FORMAT(c.date_depart, '%d/%m/%Y') as date_formattee,
                       TIME_FORMAT(c.heure_depart, '%H:%i') as heure_formattee
                FROM covoiturages c 
                INNER JOIN utilisateurs u ON c.conducteur_id = u.id 
                WHERE c.statut = 'actif' 
                AND c.date_depart >= CURDATE()
                AND c.nombre_places_disponibles > 0
                ORDER BY c.date_depart ASC, c.heure_depart ASC 
                LIMIT ? OFFSET ?
            ");
            
            $stmt->execute([$limit, $offset]);
            $trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $trajets ?: [];
            
        } catch (PDOException $e) {
            error_log("Erreur getPublicTrajets: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer les trajets d'un utilisateur spécifique
     */
    public function getUserTrajets($userId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT c.*, 
                       COUNT(r.id) as nombre_reservations,
                       DATE_FORMAT(c.date_depart, '%d/%m/%Y') as date_formattee,
                       TIME_FORMAT(c.heure_depart, '%H:%i') as heure_formattee
                FROM covoiturages c 
                LEFT JOIN reservations r ON c.id = r.covoiturage_id AND r.statut = 'confirmee'
                WHERE c.conducteur_id = ?
                GROUP BY c.id
                ORDER BY c.date_depart DESC
            ");
            
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            
        } catch (PDOException $e) {
            error_log("Erreur getUserTrajets: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer un trajet par son ID
     */
    public function getTrajetById($id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT c.*, 
                       u.prenom, 
                       u.nom,
                       u.email,
                       u.telephone,
                       CONCAT(u.prenom, ' ', u.nom) as conducteur_nom,
                       DATE_FORMAT(c.date_depart, '%d/%m/%Y') as date_formattee,
                       TIME_FORMAT(c.heure_depart, '%H:%i') as heure_formattee
                FROM covoiturages c 
                INNER JOIN utilisateurs u ON c.conducteur_id = u.id 
                WHERE c.id = ?
            ");
            
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
            
        } catch (PDOException $e) {
            error_log("Erreur getTrajetById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Rechercher des trajets selon des critères
     */
    public function searchTrajets($depart = null, $arrivee = null, $date = null) {
        try {
            $sql = "
                SELECT c.*, 
                       u.prenom, 
                       u.nom,
                       CONCAT(u.prenom, ' ', u.nom) as conducteur_nom,
                       c.nombre_places_disponibles as places_restantes,
                       DATE_FORMAT(c.date_depart, '%d/%m/%Y') as date_formattee,
                       TIME_FORMAT(c.heure_depart, '%H:%i') as heure_formattee
                FROM covoiturages c 
                INNER JOIN utilisateurs u ON c.conducteur_id = u.id 
                WHERE c.statut = 'actif' 
                AND c.date_depart >= CURDATE()
                AND c.nombre_places_disponibles > 0
            ";
            
            $params = [];
            
            if ($depart) {
                $sql .= " AND c.lieu_depart LIKE ?";
                $params[] = "%$depart%";
            }
            
            if ($arrivee) {
                $sql .= " AND c.lieu_arrivee LIKE ?";
                $params[] = "%$arrivee%";
            }
            
            if ($date) {
                $sql .= " AND c.date_depart = ?";
                $params[] = $date;
            }
            
            $sql .= " ORDER BY c.date_depart ASC, c.heure_depart ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            
        } catch (PDOException $e) {
            error_log("Erreur searchTrajets: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Réserver une place dans un covoiturage
     */
    public function reserverPlace($covoiturageId, $userId, $nombrePlaces = 1) {
        try {
            $this->conn->beginTransaction();
            
            // Vérifier les places disponibles
            $stmt = $this->conn->prepare("SELECT nombre_places_disponibles FROM covoiturages WHERE id = ? FOR UPDATE");
            $stmt->execute([$covoiturageId]);
            $trajet = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$trajet || $trajet['nombre_places_disponibles'] < $nombrePlaces) {
                $this->conn->rollBack();
                return false;
            }
            
            // Créer la réservation
            $stmt = $this->conn->prepare("
                INSERT INTO reservations (covoiturage_id, passager_id, nombre_places, statut, date_reservation) 
                VALUES (?, ?, ?, 'confirmee', NOW())
            ");
            $stmt->execute([$covoiturageId, $userId, $nombrePlaces]);
            
            // Mettre à jour les places disponibles
            $stmt = $this->conn->prepare("
                UPDATE covoiturages 
                SET nombre_places_disponibles = nombre_places_disponibles - ? 
                WHERE id = ?
            ");
            $stmt->execute([$nombrePlaces, $covoiturageId]);
            
            $this->conn->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Erreur reserverPlace: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprimer un trajet
     */
    public function deleteTrajet($id, $userId) {
        try {
            // Vérifier que l'utilisateur est le propriétaire
            $stmt = $this->conn->prepare("SELECT conducteur_id FROM covoiturages WHERE id = ?");
            $stmt->execute([$id]);
            $trajet = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$trajet || $trajet['conducteur_id'] != $userId) {
                return false;
            }
            
            // Supprimer le trajet (ou le marquer comme supprimé)
            $stmt = $this->conn->prepare("UPDATE covoiturages SET statut = 'supprime' WHERE id = ?");
            return $stmt->execute([$id]);
            
        } catch (PDOException $e) {
            error_log("Erreur deleteTrajet: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Compter le nombre total de trajets publics
     */
    public function countPublicTrajets() {
        try {
            $stmt = $this->conn->query("
                SELECT COUNT(*) as total 
                FROM covoiturages c 
                WHERE c.statut = 'actif' 
                AND c.date_depart >= CURDATE()
                AND c.nombre_places_disponibles > 0
            ");
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['total'] : 0;
            
        } catch (PDOException $e) {
            error_log("Erreur countPublicTrajets: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupérer les statistiques d'un utilisateur
     */
    public function getUserStats($userId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT 
                    COUNT(*) as total_trajets,
                    SUM(CASE WHEN date_depart < CURDATE() THEN 1 ELSE 0 END) as trajets_passes,
                    SUM(CASE WHEN date_depart >= CURDATE() THEN 1 ELSE 0 END) as trajets_futurs,
                    AVG(prix_par_personne) as prix_moyen
                FROM covoiturages 
                WHERE conducteur_id = ? AND statut = 'actif'
            ");
            
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
                'total_trajets' => 0,
                'trajets_passes' => 0,
                'trajets_futurs' => 0,
                'prix_moyen' => 0
            ];
            
        } catch (PDOException $e) {
            error_log("Erreur getUserStats: " . $e->getMessage());
            return [
                'total_trajets' => 0,
                'trajets_passes' => 0,
                'trajets_futurs' => 0,
                'prix_moyen' => 0
            ];
        }
    }
    public function getCovoiturageDetail($id) {

        // Pour l'instant, retourne des données factices
        $dummyData = [
            'id' => $id,
            'ville_depart' => 'Paris',
            'ville_arrivee' => 'Lyon',
            'date_depart' => '2023-12-01',
            'heure_depart' => '08:00:00',
            'prix_par_place' => 25.00,
            'places_restantes' => 3,
            'nombre_places_disponibles' => 4,
            'statut' => 'actif',
            'description' => 'Trajet agréable et confortable.',
            'fumeur_autorise' => false,
            'animaux_autorises' => true,
            'bagage_autorise' => true,
            'voiture_marque' => 'Renault',
            'voiture_modele' => 'Clio',
            'voiture_couleur' => 'Bleu',
            'credits_eco_gagne' => 10,
            'prenom' => 'Jean',
            'nom' => 'Dupont',
            'pseudo' => 'jdupont',
            'conducteur_id' => 1
        ];
        return $dummyData; // Retourne les données factices
    }
    public function rechercherTrajets($ville_depart, $ville_arrivee, $date_depart, $nb_passagers) {

        // Pour l'instant, retourne des données factices
        return [
            [
                'id' => 1,
                'ville_depart' => $ville_depart ?: 'Paris',
                'ville_arrivee' => $ville_arrivee ?: 'Lyon',
                'date_depart' => $date_depart ?: date('Y-m-d', strtotime('+3 days')),
                'heure_depart' => '14:30:00',
                'prix_par_personne' => 25.50,
                'nombre_places' => $nb_passagers ?: 3,
                'conducteur_nom' => 'Martin',
                'conducteur_prenom' => 'Jean',
                'description' => 'Trajet direct, ambiance détendue'
            ]
        ];
    }
}
?>