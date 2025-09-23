<?php

// Toujours démarrer la session si pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier que la requête est bien en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Méthode non autorisée';
    header('Location: /Ecoride/index.php?page=connexion');
    exit;
}

// Récupérer et nettoyer les données
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validation des champs
if (empty($email) || empty($password)) {
    $_SESSION['error'] = 'Veuillez remplir tous les champs';
    header('Location: /Ecoride/index.php?page=connexion');
    exit;
}

// Vérifier format email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Format d\'email invalide';
    header('Location: /Ecoride/index.php?page=connexion');
    exit;
}

try {
    // Charger la classe Auth
    require_once ROOT_PATH . '/src/Classes/Auth.php';
    $auth = new Auth();

    // Tentative de connexion
    if ($auth->login($email, $password)) {
        $_SESSION['success'] = 'Connexion réussie ! Bienvenue ' . ($_SESSION['user_prenom'] ?? '');

        // Redirection après connexion (dashboard par défaut)
        $redirect = $_SESSION['redirect_after_login'] ?? 'dashboard';
        unset($_SESSION['redirect_after_login']);

        header('Location: /Ecoride/index.php?page=' . $redirect);
        exit;
    } else {
        $_SESSION['error'] = 'Email ou mot de passe incorrect';
        header('Location: /Ecoride/index.php?page=connexion');
        exit;
    }

} catch (Exception $e) {
    error_log("Erreur connexion: " . $e->getMessage());
    $_SESSION['error'] = 'Erreur technique lors de la connexion';
    header('Location: /Ecoride/index.php?page=connexion');
    exit;
}
