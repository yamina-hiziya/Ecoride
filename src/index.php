<?php
// src/index.php

session_start();

// Définir le chemin racine du projet (il pointe sur le dossier src)
define('ROOT_PATH', __DIR__);

$page = 'accueil';

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

$viewPath = '';
$auth = null;

if (in_array($page, ['connexion', 'inscription', 'deconnexion', 'dashboard', 'mes-covoiturages', 'proposer-covoiturage'])) {
    require_once ROOT_PATH . '/Classes/Auth.php';
    $auth = new Auth();
}

if (in_array($page, ['dashboard', 'mes-covoiturages', 'proposer-covoiturage']) && (!$auth || !$auth->isLoggedIn())) {
    $_SESSION['error_message'] = "Vous devez être connecté pour accéder à cette page.";
    header('Location: index.php?page=connexion');
    exit;
}

switch ($page) {
    case 'accueil':
        $viewPath = ROOT_PATH . '/Views/accueil/accueil.php';
        break;
    case 'covoiturage':
            require_once ROOT_PATH . '/Classes/Covoiturage.php';
            $viewPath = ROOT_PATH . '/Views/covoiturage/covoiturage.php';
            break;
    case 'covoiturage-detail':
        require_once ROOT_PATH . '/Classes/Covoiturage.php';
        $viewPath = ROOT_PATH . '/Views/covoiturage/covoiturage-detail.php';
        break;
    case 'contact':
        $viewPath = ROOT_PATH . '/Views/contact/contact.php';
        break;
    case 'connexion':
        $viewPath = ROOT_PATH . '/Views/compte/connexion.php';
        break;
    case 'inscription':
        $viewPath = ROOT_PATH . '/Views/compte/inscription.php';
        break;
    case 'deconnexion':
        $auth->logout();
        header('Location: index.php?page=accueil');
        exit;
        break;
    case 'dashboard':
        $viewPath = ROOT_PATH . '/Views/compte/dashboard.php';
        break;
    case 'mes-covoiturages':
        require_once ROOT_PATH . '/Classes/Covoiturage.php';
        $viewPath = ROOT_PATH . '/Views/compte/mes-covoiturages.php';
        break;
    case 'proposer-covoiturage':
        $viewPath = ROOT_PATH . '/Views/compte/proposer-covoiturage.php';
        break;
    case 'modifier-covoiturage':
        $viewPath = ROOT_PATH . '/Views/compte/modifier-covoiturage.php';
        break;
    case 'modifier-covoiturage-traitement':
        include ROOT_PATH . '/Models/Covoiturage/modifier.php';
        break;
    case 'supprimer-covoiturage-traitement':
        include ROOT_PATH . '/Models/Covoiturage/supprimer.php';
        break;
    case 'connexion-traitement':
        require_once ROOT_PATH . '/Classes/Auth.php';
        include ROOT_PATH . '/Models/Auth/connexion.php';
        break;
    case 'inscription-traitement':
        include ROOT_PATH . '/Models/Auth/inscription.php';
        break;
    case 'proposer-covoiturage-traitement':
        include ROOT_PATH . '/Models/Covoiturage/proposer.php';
        break;
    default:
        $viewPath = '';
        break;
}

if (file_exists($viewPath) && !empty($viewPath)) {
    include $viewPath;
} else {
    http_response_code(404);
    echo "<h1>Erreur 404 - Page non trouvée</h1>";
}