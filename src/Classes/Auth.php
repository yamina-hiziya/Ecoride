<?php

// ============================================================================
// src/Classes/Auth.php
// Classe pour gérer l’authentification des utilisateurs
// ============================================================================

require_once ROOT_PATH . '/src/Classes/Database.php';

class Auth
{
    private $db;

    public function __construct()
    {
        // Connexion via la classe Database
        $this->db = Database::getConnection();
    }

    /**
     * Inscription utilisateur
     */
    public function register($nom, $email, $password): array
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO utilisateurs (nom, email, mot_de_passe, date_inscription)
                    VALUES (:nom, :email, :mot_de_passe, NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nom'          => $nom,
                ':email'        => $email,
                ':mot_de_passe' => $hashedPassword,
            ]);

            return ['success' => true, 'message' => "Inscription réussie !"];

        } catch (Exception $e) {
            return ['success' => false, 'message' => "Erreur inscription : " . $e->getMessage()];
        }
    }

    /**
     * Connexion utilisateur
     */
    public function login($email, $password): array
    {
        try {
            $sql = "SELECT * FROM utilisateurs WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];

                return ['success' => true, 'message' => "Connexion réussie !"];
            }

            return ['success' => false, 'message' => "Email ou mot de passe incorrect"];

        } catch (Exception $e) {
            return ['success' => false, 'message' => "Erreur connexion : " . $e->getMessage()];
        }
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        session_destroy();
        return ['success' => true, 'message' => "Déconnexion réussie"];
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }
}
