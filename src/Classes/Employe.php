<?php


class Employe {
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
     * Statistiques pour l'employé
     */
    public function getEmployeStats() {
        try {
            $sql = "SELECT 
                        (SELECT COUNT(*) FROM covoiturages WHERE statut = 'en_attente') as pending_moderation,
                        (SELECT COUNT(*) FROM support_tickets WHERE statut = 'ouvert') as pending_tickets,
                        (SELECT COUNT(*) FROM support_tickets WHERE DATE(date_creation) = CURDATE()) as new_tickets_today,
                        (SELECT COUNT(*) FROM signalements WHERE statut = 'nouveau') as new_reports,
                        (SELECT COUNT(*) FROM support_tickets WHERE DATE(date_resolution) = CURDATE()) as resolved_today,
                        (SELECT COUNT(*) FROM support_tickets WHERE DATE(date_resolution) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) as week_resolved,
                        (SELECT AVG(TIMESTAMPDIFF(HOUR, date_creation, date_resolution)) FROM support_tickets WHERE date_resolution IS NOT NULL) as avg_resolution_time,
                        85 as satisfaction_rate"; // Simulation pour satisfaction_rate
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            // Retourner des valeurs par défaut en cas d'erreur
            return [
                'pending_moderation' => 0,
                'pending_tickets' => 0,
                'new_tickets_today' => 0,
                'new_reports' => 0,
                'resolved_today' => 0,
                'week_resolved' => 0,
                'avg_resolution_time' => 0,
                'satisfaction_rate' => 0
            ];
        }
    }

    /**
     * Trajets à modérer
     */
    public function getTrajetsAModerer($limite = 20) {
        try {
            $sql = "SELECT c.*, 
                           CONCAT(u.prenom, ' ', u.nom) as conducteur_nom,
                           u.email as conducteur_email
                    FROM covoiturages c
                    INNER JOIN utilisateurs u ON c.conducteur_id = u.id
                    WHERE c.statut = 'en_attente' OR c.moderation_required = 1
                    ORDER BY c.date_creation ASC
                    LIMIT :limite";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Avis à modérer
     */
    public function getAvisAModerer($limite = 20) {
        try {
            $sql = "SELECT a.*, 
                           CONCAT(u_eval.prenom, ' ', u_eval.nom) as evaluateur_nom,
                           CONCAT(u_evalue.prenom, ' ', u_evalue.nom) as evalue_nom,
                           CONCAT(c.ville_depart, ' → ', c.ville_arrivee) as trajet_route
                    FROM avis a
                    INNER JOIN utilisateurs u_eval ON a.evaluateur_id = u_eval.id
                    INNER JOIN utilisateurs u_evalue ON a.evalue_id = u_evalue.id
                    INNER JOIN covoiturages c ON a.covoiturage_id = c.id
                    WHERE a.modere = 0
                    ORDER BY a.date_creation ASC
                    LIMIT :limite";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Signalements
     */
    public function getSignalements($limite = 20) {
        try {
            // Table signalements simulée - vous pouvez l'adapter selon votre BDD
            $signalements = [
                [
                    'id' => 1,
                    'titre' => 'Conducteur impoli',
                    'description' => 'Le conducteur était très désagréable et a conduit dangereusement',
                    'date_creation' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                    'statut' => 'nouveau'
                ],
                [
                    'id' => 2,
                    'titre' => 'Trajet annulé au dernier moment',
                    'description' => 'Le conducteur a annulé le trajet 10 minutes avant le départ sans raison valable',
                    'date_creation' => date('Y-m-d H:i:s', strtotime('-1 day')),
                    'statut' => 'nouveau'
                ],
                [
                    'id' => 3,
                    'titre' => 'Prix abusif',
                    'description' => 'Le conducteur demande 50€ pour un trajet de 20km, c\'est excessif',
                    'date_creation' => date('Y-m-d H:i:s', strtotime('-3 hours')),
                    'statut' => 'nouveau'
                ]
            ];
            
            return array_slice($signalements, 0, $limite);
            
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Approuver un trajet
     */
    public function approuverTrajet($trajetId) {
        try {
            $sql = "UPDATE covoiturages 
                    SET statut = 'active', 
                        moderation_required = 0,
                        date_moderation = NOW()
                    WHERE id = :trajet_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':trajet_id' => $trajetId]);
            
            // Log de l'action
            $this->logModerationAction('trajet', $trajetId, 'approuve', $_SESSION['user_id']);
            
            return [
                'success' => true,
                'message' => 'Trajet approuvé avec succès'
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'approbation : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Rejeter un trajet
     */
    public function rejeterTrajet($trajetId, $raison = '') {
        try {
            $sql = "UPDATE covoiturages 
                    SET statut = 'rejete', 
                        moderation_required = 0,
                        raison_rejet = :raison,
                        date_moderation = NOW()
                    WHERE id = :trajet_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':trajet_id' => $trajetId,
                ':raison' => $raison
            ]);
            
            // Log de l'action
            $this->logModerationAction('trajet', $trajetId, 'rejete', $_SESSION['user_id'], $raison);
            
            return [
                'success' => true,
                'message' => 'Trajet rejeté avec succès'
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors du rejet : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Approuver un avis
     */
    public function approuverAvis($avisId) {
        try {
            $sql = "UPDATE avis 
                    SET modere = 1, 
                        date_moderation = NOW()
                    WHERE id = :avis_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':avis_id' => $avisId]);
            
            // Log de l'action
            $this->logModerationAction('avis', $avisId, 'approuve', $_SESSION['user_id']);
            
            return [
                'success' => true,
                'message' => 'Avis approuvé avec succès'
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'approbation : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Rejeter un avis
     */
    public function rejeterAvis($avisId, $raison = '') {
        try {
            $sql = "UPDATE avis 
                    SET modere = -1, 
                        raison_rejet = :raison,
                        date_moderation = NOW()
                    WHERE id = :avis_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':avis_id' => $avisId,
                ':raison' => $raison
            ]);
            
            // Log de l'action
            $this->logModerationAction('avis', $avisId, 'rejete', $_SESSION['user_id'], $raison);
            
            return [
                'success' => true,
                'message' => 'Avis rejeté avec succès'
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors du rejet : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Logger les actions de modération
     */
    private function logModerationAction($type, $item_id, $action, $moderateur_id, $raison = '') {
        try {
            $sql = "INSERT INTO moderation_logs (type, item_id, action, moderateur_id, raison, date_action)
                    VALUES (:type, :item_id, :action, :moderateur_id, :raison, NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':type' => $type,
                ':item_id' => $item_id,
                ':action' => $action,
                ':moderateur_id' => $moderateur_id,
                ':raison' => $raison
            ]);
            
        } catch (PDOException $e) {
            // Log l'erreur mais ne pas interrompre le processus principal
            error_log("Erreur log modération : " . $e->getMessage());
        }
    }

    /**
     * Statistiques de performance de l'employé
     */
    public function getPerformanceStats($employe_id) {
        try {
            $sql = "SELECT 
                        DATE(date_action) as date,
                        COUNT(*) as actions_count
                    FROM moderation_logs 
                    WHERE moderateur_id = :employe_id 
                      AND date_action >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                    GROUP BY DATE(date_action)
                    ORDER BY date";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':employe_id' => $employe_id]);
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Traiter un signalement
     */
    public function traiterSignalement($signalementId, $action, $notes = '') {
        try {
            // Simulation - adaptez selon votre structure de BDD
            return [
                'success' => true,
                'message' => 'Signalement traité avec succès'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors du traitement : ' . $e->getMessage()
            ];
        }
    }
}
?>