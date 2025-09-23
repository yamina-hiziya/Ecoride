<?php

require_once "BaseController.php";

class AuthController extends BaseController {
    
    public function login() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = trim($_POST["email"] ?? "");
            $password = $_POST["password"] ?? "";
            
            if (empty($email) || empty($password)) {
                $this->redirectTo("connexion", "Veuillez remplir tous les champs", "error");
                return;
            }
            
            try {
                // Connexion directe à la base de données avec gestion d'erreurs
                require_once __DIR__ . "/../Classes/Database.php";
                
                // Vérifier que Database existe
                if (!class_exists('Database')) {
                    throw new Exception("Classe Database non trouvée");
                }
                
                $db = Database::getInstance();
                
                // Vérifier que $db est bien un objet PDO
                if (!($db instanceof PDO)) {
                    throw new Exception("Database::getInstance() ne retourne pas un objet PDO");
                }
                
                // Chercher l'utilisateur
                $stmt = $db->prepare("SELECT id, nom, prenom, email, mot_de_passe, role FROM utilisateurs WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($password, $user['mot_de_passe'])) {
                    // ✅ CONNEXION RÉUSSIE
                    session_start();
                    $_SESSION["is_logged_in"] = true;
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["user_name"] = $user["nom"] . " " . $user["prenom"];
                    $_SESSION["user_role"] = $user["role"] ?? "user";
                    
                    // Redirection selon le rôle
                    if (in_array($user["role"], ['admin', 'employe'])) {
                        $this->redirectTo("dashboard", "Connexion administrateur réussie !", "success");
                    } else {
                        $this->redirectTo("dashboard", "Connexion réussie ! Bienvenue !", "success");
                    }
                } else {
                    $this->redirectTo("connexion", "Email ou mot de passe incorrect", "error");
                }
                
            } catch (Exception $e) {
                // Log l'erreur pour debug
                error_log("Erreur AuthController login: " . $e->getMessage());
                $this->redirectTo("connexion", "Erreur de connexion : " . $e->getMessage(), "error");
            }
        }
        
        // Afficher le formulaire
        $this->render(__DIR__ . "/../Views/compte/connexion.php");
    }
    
    public function register() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nom = trim($_POST["nom"] ?? "");
            $prenom = trim($_POST["prenom"] ?? "");
            $email = trim($_POST["email"] ?? "");
            $password = $_POST["password"] ?? "";
            $password_confirm = $_POST["password_confirm"] ?? "";
            $telephone = trim($_POST["telephone"] ?? "");
            $pseudo = trim($_POST["pseudo"] ?? "");
            
            // Validations
            if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
                $this->redirectTo("inscription", "Tous les champs obligatoires doivent être remplis", "error");
            }
            
            if ($password !== $password_confirm) {
                $this->redirectTo("inscription", "Les mots de passe ne correspondent pas", "error");
            }
            
            if (strlen($password) < 6) {
                $this->redirectTo("inscription", "Le mot de passe doit contenir au moins 6 caractères", "error");
            }
            
            $result = $this->auth->register($nom, $prenom, $email, $password, $telephone, $pseudo);
            
            if ($result && $result["success"]) {
                $this->redirectTo("connexion", "Inscription réussie ! Vous pouvez maintenant vous connecter", "success");
            } else {
                $this->redirectTo("inscription", $result["message"] ?? "Erreur lors de l inscription", "error");
            }
        }
        
        // Afficher le formulaire
        $this->render(__DIR__ . "/../Views/compte/inscription.php");
    }
    
    public function logout() {
        $this->auth->logout();
        $this->redirectTo("accueil", "Vous avez été déconnecté avec succès", "success");
    }
}
?>