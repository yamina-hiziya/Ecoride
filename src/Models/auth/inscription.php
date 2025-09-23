<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des données du formulaire
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $telephone = trim($_POST['telephone'] ?? '');
    $pseudo = trim($_POST['pseudo'] ?? '');
    
    // Validation des données
    $errors = [];
    
    if (empty($nom)) $errors[] = "Le nom est requis";
    if (empty($prenom)) $errors[] = "Le prénom est requis";
    if (empty($email)) $errors[] = "L'email est requis";
    if (empty($password)) $errors[] = "Le mot de passe est requis";
    if (empty($pseudo)) $errors[] = "Le pseudo est requis";
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format d'email invalide";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
    }
    
    // Si il y a des erreurs de validation
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode('<br>', $errors);
        header('Location: index.php?page=inscription');
        exit;
    }
    
    try {
        // Inclure la classe Auth si elle n'est pas déjà incluse
        if (!class_exists('Auth')) {
            require_once ROOT_PATH . '/Classes/Auth.php';
        }
        
        $auth = new Auth();
        $result = $auth->register($nom, $prenom, $email, $password, $telephone, $pseudo);
        
        if ($result) {
            // Inscription réussie
            $_SESSION['success_message'] = "🎉 Bienvenue sur EcoRide ! Votre compte a été créé avec succès.<br>
                                           💚 Vous avez reçu <strong>20 crédits écologiques</strong> de bienvenue !<br>
                                           Vous pouvez maintenant vous connecter.";
            header('Location: index.php?page=connexion');
            exit;
        } else {
            // Échec de l'inscription (email ou pseudo déjà utilisé)
            $_SESSION['error_message'] = "Erreur : L'email ou le pseudo est déjà utilisé. Veuillez en choisir un autre.";
            header('Location: index.php?page=inscription');
            exit;
        }
        
    } catch (Exception $e) {
        // Erreur système
        error_log("Erreur lors de l'inscription : " . $e->getMessage());
        $_SESSION['error_message'] = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
        header('Location: index.php?page=inscription');
        exit;
    }
    
} else {
    // Si la requête n'est pas en POST, rediriger vers l'inscription
    header('Location: index.php?page=inscription');
    exit;
}
?>
