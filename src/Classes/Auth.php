<?php
// src/Classes/Auth.php

require_once 'Database.php';

class Auth
{

    private $conn;

    public function __construct()
    {
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function login($email, $password) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        try {
            // Préparer la requête pour trouver l'utilisateur par son email
            $stmt = $this->conn->prepare("SELECT id, pseudo, email, mot_de_passe FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Vérifier si l'utilisateur existe et si le mot de passe est correct
            if ($user && password_verify($password, $user['mot_de_passe'])) {
                // Créer une session pour l'utilisateur
                // Si les identifiants sont corrects, on stocke les infos de l'utilisateur en session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['pseudo'];
                $_SESSION['user_email'] = $user['email'];
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Gérer l'erreur de base de données
            return false;
        }
    }
    
    public function register($nom, $prenom, $email, $password, $telephone, $pseudo) {
        try {
            // Hacher le mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
            // Préparer la requête pour éviter les injections SQL
            $stmt = $this->conn->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, pseudo) VALUES (?, ?, ?, ?, ?, ?)");
            
            // Exécuter la requête
            $stmt->execute([$nom, $prenom, $email, $hashed_password, $telephone, $pseudo]);
            
            // Retourner true si l'exécution a réussi
            return true;
        } catch (PDOException $e) {
            // Journaliser l'erreur pour le débogage
            error_log("Erreur lors de l'insertion dans la base de données : " . $e->getMessage());
            // En cas d'erreur de base de données, on renvoie false
            return false;
        }
    }
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
    }

    public function isLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }
}

// Test de connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=nom_de_la_base', 'utilisateur', 'mot_de_passe');
    echo "Connexion réussie";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
