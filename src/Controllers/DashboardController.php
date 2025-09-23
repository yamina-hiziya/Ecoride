<?php

require_once "BaseController.php";

class DashboardController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        
        // Récupérer statistiques utilisateur
        $stats = $this->getUserStats($user["id"]);
        
        $data = [
            "user" => $user,
            "stats" => $stats
        ];
        
        $this->render(__DIR__ . "/../Views/dashboard/dashboard.php", $data);
    }
    
    private function getUserStats($userId) {
        try {
            // Nombre de trajets proposés
            $stmt = $this->db->prepare("SELECT COUNT(*) as trajets_proposes FROM covoiturages WHERE conducteur_id = ?");
            $stmt->execute([$userId]);
            $trajets_proposes = $stmt->fetch()["trajets_proposes"] ?? 0;
            
            // Nombre de réservations
            $stmt = $this->db->prepare("SELECT COUNT(*) as reservations FROM reservations WHERE utilisateur_id = ?");
            $stmt->execute([$userId]);
            $reservations = $stmt->fetch()["reservations"] ?? 0;
            
            // Crédits écologiques
            $stmt = $this->db->prepare("SELECT credits_ecologiques FROM utilisateurs WHERE id = ?");
            $stmt->execute([$userId]);
            $credits = $stmt->fetch()["credits_ecologiques"] ?? 0;
            
            return [
                "trajets_proposes" => $trajets_proposes,
                "reservations" => $reservations,
                "credits_ecologiques" => $credits,
                "niveau" => $this->calculateLevel($credits)
            ];
        } catch (Exception $e) {
            return [
                "trajets_proposes" => 0,
                "reservations" => 0,
                "credits_ecologiques" => 0,
                "niveau" => "Débutant"
            ];
        }
    }
    
    private function calculateLevel($credits) {
        if ($credits >= 100) return "Expert";
        if ($credits >= 50) return "Avancé";
        if ($credits >= 20) return "Intermédiaire";
        return "Débutant";
    }
}
?>