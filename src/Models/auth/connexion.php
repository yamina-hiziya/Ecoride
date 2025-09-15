<?php
// filepath: /src/Models/Auth/connexion.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et validation des données du formulaire
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation basique
    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Email et mot de passe requis";
        header('Location: index.php?page=connexion');
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Format d'email invalide";
        header('Location: index.php?page=connexion');
        exit;
    }
    
    try {
        // Utilisation de la classe Auth (déjà incluse dans index.php)
        $auth = new Auth();
        $result = $auth->login($email, $password);
        
        if ($result) {
            // Connexion réussie
            $_SESSION['success_message'] = "Connexion réussie ! Bienvenue.";
            header('Location: index.php?page=dashboard');
            exit;
        } else {
            // Échec de la connexion
            $_SESSION['error_message'] = "Email ou mot de passe incorrect";
            header('Location: index.php?page=connexion');
            exit;
        }
        
    } catch (Exception $e) {
        // Erreur système
        error_log("Erreur lors de la connexion : " . $e->getMessage());
        $_SESSION['error_message'] = "Une erreur est survenue lors de la connexion. Veuillez réessayer.";
        header('Location: index.php?page=connexion');
        exit;
    }
    
} else {
    // Si la requête n'est pas en POST, rediriger vers la page de connexion
    header('Location: index.php?page=connexion');
    exit;
}
?>