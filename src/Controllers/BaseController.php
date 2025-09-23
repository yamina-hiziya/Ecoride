<?php

abstract class BaseController {
    protected $auth;
    protected $db;
    
    public function __construct() {
        if (!class_exists("Database")) {
            require_once __DIR__ . "/../Classes/Database.php";
        }
        if (!class_exists("Auth")) {
            require_once __DIR__ . "/../Classes/Auth.php";
        }
        
        $this->db = Database::getInstance()->getConnection();
        $this->auth = new Auth();
        $this->startSession();
    }
    
    protected function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    protected function isLoggedIn() {
        return isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"] === true;
    }
    
    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            $_SESSION["error_message"] = "Vous devez être connecté pour accéder à cette page";
            header("Location: index.php?page=connexion");
            exit;
        }
    }
    
    protected function redirectTo($page, $message = null, $type = "info") {
        if ($message) {
            $_SESSION[$type . "_message"] = $message;
        }
        header("Location: index.php?page=$page");
        exit;
    }
    
    protected function render($view, $data = []) {
        extract($data);
        if (file_exists($view)) {
            include $view;
        } else {
            echo "Vue non trouvée : $view";
        }
    }
    
    protected function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            "id" => $_SESSION["user_id"] ?? null,
            "email" => $_SESSION["user_email"] ?? null,
            "nom" => $_SESSION["user_nom"] ?? null,
            "prenom" => $_SESSION["user_prenom"] ?? null,
            "role" => $_SESSION["user_role"] ?? null,
            "credits" => $_SESSION["user_credits"] ?? 0
        ];
    }
}
?>