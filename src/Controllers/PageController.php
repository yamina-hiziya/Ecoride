<?php

require_once "BaseController.php";

class PageController extends BaseController {
    
    public function accueil() {
        // Récupérer quelques statistiques pour la page d'accueil
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as total_users FROM utilisateurs");
            $total_users = $stmt->fetch()["total_users"] ?? 0;
            
            $stmt = $this->db->query("SELECT COUNT(*) as total_trajets FROM covoiturages");
            $total_trajets = $stmt->fetch()["total_trajets"] ?? 0;
            
            $stmt = $this->db->query("SELECT SUM(credits_ecologiques) as total_credits FROM utilisateurs");
            $total_credits = $stmt->fetch()["total_credits"] ?? 0;
            
            $stats = [
                "total_users" => $total_users,
                "total_trajets" => $total_trajets,
                "total_credits" => $total_credits
            ];
            
        } catch (Exception $e) {
            $stats = [
                "total_users" => 0,
                "total_trajets" => 0,
                "total_credits" => 0
            ];
        }
        
        $data = ["stats" => $stats];
        $this->render(__DIR__ . "/../Views/accueil/accueil.php", $data);
    }
    
    public function contact() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->handleContact();
        }
        
        $this->render(__DIR__ . "/../Views/contact/contact.php");
    }
    
    private function handleContact() {
        $nom = trim($_POST["nom"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $message = trim($_POST["message"] ?? "");
        
        if (empty($nom) || empty($email) || empty($message)) {
            $this->redirectTo("contact", "Tous les champs sont obligatoires", "error");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirectTo("contact", "Email invalide", "error");
        }
        
        // Simuler envoi (pas de table messages_contact pour le moment)
        $this->redirectTo("contact", "Message envoyé avec succès ! Nous vous répondrons bientôt.", "success");
    }
}
?>