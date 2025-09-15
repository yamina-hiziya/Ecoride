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
        try {
            // Préparer la requête pour récupérer l'utilisateur
            $stmt = $this->conn->prepare("SELECT id, nom, prenom, email, mot_de_passe FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Vérifier si l'utilisateur existe et si le mot de passe est correct
            if ($user && password_verify($password, $user['mot_de_passe'])) {
                // Démarrer la session
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                
                // Stocker les informations de l'utilisateur en session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_prenom'] = $user['prenom'];
                $_SESSION['user_email'] = $user['email'];
                
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la connexion : " . $e->getMessage());
            return false;
        }
    }
    
    public function register($nom, $prenom, $email, $password, $telephone, $pseudo) {
        try {
            // Vérifier si l'email existe déjà
            $stmt = $this->conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                return false; // Email déjà utilisé
            }
            
            // Vérifier si le pseudo existe déjà
            $stmt = $this->conn->prepare("SELECT id FROM utilisateurs WHERE pseudo = ?");
            $stmt->execute([$pseudo]);
            if ($stmt->fetch()) {
                return false; // Pseudo déjà utilisé
            }
            
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
    
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'nom' => $_SESSION['user_nom'],
                'prenom' => $_SESSION['user_prenom'],
                'email' => $_SESSION['user_email']
            ];
        }
        return null;
    }
}




