<?php


class Avis {
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
            throw new Exception("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
    
    /**
     * Créer un nouvel avis
     */
    public function creerAvis($evaluateur_id, $evalue_id, $covoiturage_id, $note, $commentaire = null) {
        try {
            // Vérifier que l'utilisateur peut donner cet avis
            if (!$this->peutDonnerAvis($evaluateur_id, $evalue_id, $covoiturage_id)) {
                return [
                    'success' => false,
                    'message' => 'Vous ne pouvez pas donner d\'avis pour ce trajet.'
                ];
            }
            
            // Vérifier qu'un avis n'existe pas déjà
            if ($this->avisExiste($evaluateur_id, $evalue_id, $covoiturage_id)) {
                return [
                    'success' => false,
                    'message' => 'Vous avez déjà donné un avis pour ce trajet.'
                ];
            }
            
            $sql = "INSERT INTO avis (evaluateur_id, evalue_id, covoiturage_id, note, commentaire) 
                    VALUES (:evaluateur_id, :evalue_id, :covoiturage_id, :note, :commentaire)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':evaluateur_id' => $evaluateur_id,
                ':evalue_id' => $evalue_id,
                ':covoiturage_id' => $covoiturage_id,
                ':note' => $note,
                ':commentaire' => $commentaire
            ]);
            
            // Mettre à jour la note moyenne de l'utilisateur évalué
            $this->mettreAJourNoteMoyenne($evalue_id);
            
            return [
                'success' => true,
                'message' => 'Avis créé avec succès !',
                'avis_id' => $this->pdo->lastInsertId()
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la création de l\'avis : ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Récupérer les avis d'un utilisateur
     */
    public function getAvisUtilisateur($user_id, $limite = 10) {
        try {
            $sql = "SELECT a.*, 
                           u_eval.prenom as evaluateur_prenom, 
                           u_eval.nom as evaluateur_nom,
                           c.ville_depart, 
                           c.ville_arrivee, 
                           c.date_depart
                    FROM avis a
                    INNER JOIN utilisateurs u_eval ON a.evaluateur_id = u_eval.id
                    INNER JOIN covoiturages c ON a.covoiturage_id = c.id
                    WHERE a.evalue_id = :user_id
                    ORDER BY a.date_creation DESC
                    LIMIT :limite";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des avis : " . $e->getMessage());
        }
    }
    
    /**
     * Calculer la note moyenne d'un utilisateur
     */
    public function getNoteMoyenne($user_id) {
        try {
            $sql = "SELECT AVG(note) as note_moyenne, COUNT(*) as nombre_avis 
                    FROM avis 
                    WHERE evalue_id = :user_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':user_id' => $user_id]);
            
            $result = $stmt->fetch();
            
            return [
                'note_moyenne' => $result['note_moyenne'] ? round($result['note_moyenne'], 1) : 0,
                'nombre_avis' => $result['nombre_avis']
            ];
            
        } catch (PDOException $e) {
            throw new Exception("Erreur lors du calcul de la note moyenne : " . $e->getMessage());
        }
    }
    
    /**
     * Vérifier si un utilisateur peut donner un avis
     */
    private function peutDonnerAvis($evaluateur_id, $evalue_id, $covoiturage_id) {
        try {
            // Vérifier que l'utilisateur a participé au covoiturage
            $sql = "SELECT COUNT(*) as count 
                    FROM reservations r
                    INNER JOIN covoiturages c ON r.covoiturage_id = c.id
                    WHERE (r.utilisateur_id = :evaluateur_id AND c.conducteur_id = :evalue_id)
                       OR (c.conducteur_id = :evaluateur_id AND r.utilisateur_id = :evalue_id)
                       AND r.covoiturage_id = :covoiturage_id
                       AND r.statut = 'confirmee'";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':evaluateur_id' => $evaluateur_id,
                ':evalue_id' => $evalue_id,
                ':covoiturage_id' => $covoiturage_id
            ]);
            
            $result = $stmt->fetch();
            return $result['count'] > 0;
            
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Vérifier si un avis existe déjà
     */
    private function avisExiste($evaluateur_id, $evalue_id, $covoiturage_id) {
        try {
            $sql = "SELECT COUNT(*) as count 
                    FROM avis 
                    WHERE evaluateur_id = :evaluateur_id 
                      AND evalue_id = :evalue_id 
                      AND covoiturage_id = :covoiturage_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':evaluateur_id' => $evaluateur_id,
                ':evalue_id' => $evalue_id,
                ':covoiturage_id' => $covoiturage_id
            ]);
            
            $result = $stmt->fetch();
            return $result['count'] > 0;
            
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Mettre à jour la note moyenne dans la table utilisateurs
     */
    private function mettreAJourNoteMoyenne($user_id) {
        try {
            $noteData = $this->getNoteMoyenne($user_id);
            
            $sql = "UPDATE utilisateurs 
                    SET note_moyenne = :note_moyenne 
                    WHERE id = :user_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':note_moyenne' => $noteData['note_moyenne'],
                ':user_id' => $user_id
            ]);
            
        } catch (PDOException $e) {
            // Log l'erreur mais ne pas interrompre le processus
            error_log("Erreur mise à jour note moyenne : " . $e->getMessage());
        }
    }
    
    /**
     * Récupérer les avis en attente pour un utilisateur
     */
    public function getAvisEnAttente($user_id) {
        try {
            $sql = "SELECT DISTINCT 
                           c.id as covoiturage_id,
                           c.ville_depart,
                           c.ville_arrivee,
                           c.date_depart,
                           CASE 
                               WHEN c.conducteur_id = :user_id THEN r.utilisateur_id
                               ELSE c.conducteur_id 
                           END as personne_a_evaluer_id,
                           CASE 
                               WHEN c.conducteur_id = :user_id THEN CONCAT(u_pass.prenom, ' ', u_pass.nom)
                               ELSE CONCAT(u_cond.prenom, ' ', u_cond.nom)
                           END as personne_a_evaluer_nom,
                           CASE 
                               WHEN c.conducteur_id = :user_id THEN 'passager'
                               ELSE 'conducteur'
                           END as type_evaluation
                    FROM reservations r
                    INNER JOIN covoiturages c ON r.covoiturage_id = c.id
                    INNER JOIN utilisateurs u_cond ON c.conducteur_id = u_cond.id
                    INNER JOIN utilisateurs u_pass ON r.utilisateur_id = u_pass.id
                    LEFT JOIN avis a ON (
                        (a.evaluateur_id = :user_id AND a.evalue_id = CASE WHEN c.conducteur_id = :user_id THEN r.utilisateur_id ELSE c.conducteur_id END)
                        AND a.covoiturage_id = c.id
                    )
                    WHERE (c.conducteur_id = :user_id OR r.utilisateur_id = :user_id)
                      AND r.statut = 'confirmee'
                      AND c.date_depart < NOW()
                      AND a.id IS NULL
                    ORDER BY c.date_depart DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':user_id' => $user_id]);
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des avis en attente : " . $e->getMessage());
        }
    }
}
?>