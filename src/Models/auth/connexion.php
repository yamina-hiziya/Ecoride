<?php
// src/Models/Auth/connexion.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On récupère les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // --- LOGIQUE DE VÉRIFICATION (pour le moment, c'est une simulation) ---
    // Dans une vraie application, tu ferais ceci :
    // 1. Connexion à la base de données.
    // 2. Recherche d'un utilisateur avec l'email fourni.
    // 3. Vérification si le mot de passe correspond au mot de passe haché stocké.

    // Pour le moment, on va simuler un utilisateur "test"
    $user_exists = ($email === 'test@example.com' && $password === 'password');

    if ($user_exists) {
        // L'utilisateur est authentifié avec succès
        // On stocke les informations de l'utilisateur en session
        $_SESSION['user_id'] = 1;
        $_SESSION['user_email'] = $email;
        $_SESSION['is_logged_in'] = true;

        // On redirige l'utilisateur vers son tableau de bord
        header('Location: index.php?page=dashboard');
        exit;
    } else {
        // La connexion a échoué
        $_SESSION['error_message'] = "Email ou mot de passe incorrect.";

        // On redirige l'utilisateur vers la page de connexion avec un message d'erreur
        header('Location: index.php?page=connexion');
        exit;
    }
} else {
    // Si la requête n'est pas en POST, on redirige vers la page de connexion
    header('Location: index.php?page=connexion');
    exit;
}
