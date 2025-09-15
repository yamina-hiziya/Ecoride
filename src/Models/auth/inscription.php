<?php
// src/Models/Auth/inscription.php

// Vérifie que la requête a été envoyée en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On récupère les données du formulaire
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Chiffrez le mot de passe avant de le stocker
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // Ici, vous insérez $hashed_password dans la base de données, pas le mot de passe d'origine.

    // Dans une vraie application, on ferait ceci :
    // 1. Validation des données
    // 2. Hashage du mot de passe (sécurité)
    // 3. Connexion à la base de données
    // 4. Insertion de l'utilisateur dans la base de données

    // Pour l'instant, on va juste simuler une réussite et rediriger
    // On met un message de confirmation en session
    $_SESSION['success_message'] = "Bienvenue sur Ecoride, votre compte a été créé avec succès !";

    // On redirige l'utilisateur vers la page de connexion
    header('Location: index.php?page=connexion');
    exit;
} else {
    // Si la requête n'est pas en POST, on redirige vers l'inscription
    header('Location: index.php?page=inscription');
    exit;
}
