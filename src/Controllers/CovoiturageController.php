<?php

require_once "BaseController.php";

class CovoiturageController extends BaseController {
    
    public function liste() {
        try {
            $stmt = $this->db->query("
                SELECT c.*, u.prenom as conducteur_prenom, u.nom as conducteur_nom 
                FROM covoiturages c 
                LEFT JOIN utilisateurs u ON c.conducteur_id = u.id 
                WHERE c.date_depart >= CURDATE() 
                ORDER BY c.date_depart ASC
            ");
            $covoiturages = $stmt->fetchAll();
            
            $data = ["covoiturages" => $covoiturages];
            $this->render(__DIR__ . "/../Views/covoiturage/liste.php", $data);
            
        } catch (Exception $e) {
            $data = ["covoiturages" => [], "error" => "Erreur lors du chargement des covoiturages"];
            $this->render(__DIR__ . "/../Views/covoiturage/liste.php", $data);
        }
    }
    
    public function create() {
        // Vérifier l'authentification
        $this->requireAuth();
        
        // Traitement POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreateForm();
            return;
        }
        
        // Affichage du formulaire
        $this->render(__DIR__ . "/../Views/covoiturage/create.php");
    }
    
    private function handleCreateForm() {
        // Validation et traitement des données
        $ville_depart = trim($_POST['ville_depart'] ?? '');
        $ville_arrivee = trim($_POST['ville_arrivee'] ?? '');
        $date_depart = $_POST['date_depart'] ?? '';
        $heure_depart = $_POST['heure_depart'] ?? '';
        $places_disponibles = intval($_POST['places_disponibles'] ?? 0);
        $prix = floatval($_POST['prix'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        
        // Validations
        if (empty($ville_depart) || empty($ville_arrivee) || empty($date_depart) || empty($heure_depart)) {
            $this->redirectTo('creer-covoiturage', 'Tous les champs obligatoires doivent être remplis', 'error');
        }
        
        if ($places_disponibles < 1 || $places_disponibles > 8) {
            $this->redirectTo('creer-covoiturage', 'Le nombre de places doit être entre 1 et 8', 'error');
        }
        
        if ($prix < 0) {
            $this->redirectTo('creer-covoiturage', 'Le prix ne peut pas être négatif', 'error');
        }
        
        // Vérifier que la date n'est pas dans le passé
        if (strtotime($date_depart) < strtotime(date('Y-m-d'))) {
            $this->redirectTo('creer-covoiturage', 'La date de départ ne peut pas être dans le passé', 'error');
        }
        
        try {
            // Insérer en base de données
            $stmt = $this->db->prepare("
                INSERT INTO covoiturages (
                    conducteur_id, 
                    ville_depart, 
                    ville_arrivee, 
                    date_depart, 
                    heure_depart, 
                    places_disponibles, 
                    prix, 
                    description, 
                    statut, 
                    date_creation
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'actif', NOW())
            ");
            
            $success = $stmt->execute([
                $_SESSION['user_id'],
                $ville_depart,
                $ville_arrivee,
                $date_depart,
                $heure_depart,
                $places_disponibles,
                $prix,
                $description
            ]);
            
            if ($success) {
                // Ajouter des crédits écologiques
                $this->ajouterCreditsEcologiques($_SESSION['user_id'], 5);
                
                $this->redirectTo('mes-trajets', 'Covoiturage créé avec succès ! +5 crédits écologiques', 'success');
            } else {
                $this->redirectTo('creer-covoiturage', 'Erreur lors de la création du covoiturage', 'error');
            }
            
        } catch (Exception $e) {
            $this->redirectTo('creer-covoiturage', 'Erreur technique : ' . $e->getMessage(), 'error');
        }
    }
    
    private function ajouterCreditsEcologiques($userId, $credits) {
        try {
            $stmt = $this->db->prepare("
                UPDATE utilisateurs 
                SET credits_ecologiques = credits_ecologiques + ? 
                WHERE id = ?
            ");
            $stmt->execute([$credits, $userId]);
            
            // Mettre à jour la session
            if (isset($_SESSION['user_credits'])) {
                $_SESSION['user_credits'] += $credits;
            }
        } catch (Exception $e) {
            // Log l'erreur mais ne pas interrompre le processus
            error_log("Erreur ajout crédits : " . $e->getMessage());
        }
    }
    
    public function details($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT c.*, 
                       u.prenom as conducteur_prenom, 
                       u.nom as conducteur_nom,
                       u.telephone as conducteur_telephone,
                       u.email as conducteur_email
                FROM covoiturages c 
                LEFT JOIN utilisateurs u ON c.conducteur_id = u.id 
                WHERE c.id = ?
            ");
            $stmt->execute([$id]);
            $covoiturage = $stmt->fetch();
            
            if (!$covoiturage) {
                $this->redirectTo('covoiturage', 'Covoiturage non trouvé', 'error');
            }
            
            $data = ["covoiturage" => $covoiturage];
            $this->render(__DIR__ . "/../Views/covoiturage/details.php", $data);
            
        } catch (Exception $e) {
            $this->redirectTo('covoiturage', 'Erreur lors du chargement des détails', 'error');
        }
    }
    
    public function reserver($id) {
        $this->requireAuth();
        
        try {
            // Vérifier que le covoiturage existe et a des places
            $stmt = $this->db->prepare("
                SELECT * FROM covoiturages 
                WHERE id = ? AND places_disponibles > 0 AND statut = 'actif'
            ");
            $stmt->execute([$id]);
            $covoiturage = $stmt->fetch();
            
            if (!$covoiturage) {
                $this->redirectTo('covoiturage', 'Covoiturage non disponible', 'error');
            }
            
            // Vérifier que l'utilisateur n'est pas le conducteur
            if ($covoiturage['conducteur_id'] == $_SESSION['user_id']) {
                $this->redirectTo('covoiturage', 'Vous ne pouvez pas réserver votre propre covoiturage', 'error');
            }
            
            // Vérifier que l'utilisateur n'a pas déjà réservé
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count FROM reservations 
                WHERE covoiturage_id = ? AND utilisateur_id = ?
            ");
            $stmt->execute([$id, $_SESSION['user_id']]);
            $existing = $stmt->fetch()['count'];
            
            if ($existing > 0) {
                $this->redirectTo('covoiturage', 'Vous avez déjà réservé ce covoiturage', 'error');
            }
            
            // Commencer la transaction
            $this->db->beginTransaction();
            
            // Créer la réservation
            $stmt = $this->db->prepare("
                INSERT INTO reservations (covoiturage_id, utilisateur_id, date_reservation, statut) 
                VALUES (?, ?, NOW(), 'confirmee')
            ");
            $stmt->execute([$id, $_SESSION['user_id']]);
            
            // Décrémenter les places disponibles
            $stmt = $this->db->prepare("
                UPDATE covoiturages 
                SET places_disponibles = places_disponibles - 1 
                WHERE id = ?
            ");
            $stmt->execute([$id]);
            
            // Ajouter des crédits écologiques
            $this->ajouterCreditsEcologiques($_SESSION['user_id'], 3);
            
            $this->db->commit();
            
            $this->redirectTo('mes-trajets', 'Réservation confirmée ! +3 crédits écologiques', 'success');
            
        } catch (Exception $e) {
            $this->db->rollback();
            $this->redirectTo('covoiturage', 'Erreur lors de la réservation', 'error');
        }
    }
}
?>