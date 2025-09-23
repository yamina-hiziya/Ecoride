<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Ecoride/index.php
// LIGNE 1 - PAS D'ESPACE AVANT <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);

/**
 * ============================================================================
 * ECORIDE - INDEX PRINCIPAL
 * ============================================================================
 */

// Démarrage de la session (doit être en premier)
session_start();

// Définition du chemin racine du projet
define('ROOT_PATH', __DIR__);

// ============================================================================
// CONFIGURATION DE BASE
// ============================================================================

// Inclusion du fichier de configuration
if (file_exists(ROOT_PATH . '/config.php')) {
    require_once ROOT_PATH . '/config.php';
} else {
    die('❌ Erreur: Fichier config.php manquant - Veuillez le créer !');
}

// ============================================================================
// CHARGEMENT DES CLASSES ESSENTIELLES
// ============================================================================

// Classe d'authentification
if (file_exists(ROOT_PATH . '/src/Classes/Auth.php')) {
    require_once ROOT_PATH . '/src/Classes/Auth.php';
    $auth = new Auth();
} else {
    die('❌ Erreur: Classe Auth manquante dans /src/Classes/Auth.php');
}

// ============================================================================
// GESTION DES PARAMÈTRES
// ============================================================================

// Récupération de la page demandée (accueil par défaut)
$page = $_GET['page'] ?? 'accueil';

// Mode debug (ajoutez ?debug=1 dans l'URL)
$debug = isset($_GET['debug']);

// Nettoyage de la page pour la sécurité
$page = preg_replace('/[^a-zA-Z0-9\-]/', '', $page);

// ============================================================================
// TRAITEMENTS PRIORITAIRES (AVANT HTML) - REDIRECTIONS
// ============================================================================

// Gestion des traitements qui nécessitent des redirections
switch ($page) {
    case 'connexion-traitement':
        // Traitement du formulaire de connexion
        if (file_exists(ROOT_PATH . '/src/Models/Auth/connexion.php')) {
            require_once ROOT_PATH . '/src/Models/Auth/connexion.php';
        } else {
            $_SESSION['error'] = 'Service de connexion temporairement indisponible';
            header('Location: /Ecoride/index.php?page=connexion');
        }
        exit; // IMPORTANT: exit après traitement
        
    case 'inscription-traitement':
        // Traitement du formulaire d'inscription
        if (file_exists(ROOT_PATH . '/src/Models/Auth/inscription.php')) {
            require_once ROOT_PATH . '/src/Models/Auth/inscription.php';
        } else {
            $_SESSION['error'] = 'Service d\'inscription temporairement indisponible';
            header('Location: /Ecoride/index.php?page=inscription');
        }
        exit; // IMPORTANT: exit après traitement
        
    case 'deconnexion':
        // Déconnexion et redirection
        $auth->logout();
        $_SESSION['success'] = 'Vous avez été déconnecté avec succès';
        header('Location: /Ecoride/index.php?page=accueil');
        exit; // IMPORTANT: exit après redirection
}

// ============================================================================
// VÉRIFICATION D'AUTHENTIFICATION PRÉCOCE (AVANT TOUT OUTPUT HTML)
// ============================================================================

$pages_protegees = ['dashboard', 'profil', 'historique', 'proposer-covoiturage', 'mes-covoiturages', 'mes-trajets'];

if (in_array($page, $pages_protegees)) {
    if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
        $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page';
        header('Location: /Ecoride/index.php?page=connexion');
        exit;
    }
}

// Redirection si déjà connecté pour connexion/inscription
if (in_array($page, ['connexion', 'inscription'])) {
    if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']) {
        header('Location: /Ecoride/index.php?page=dashboard');
        exit;
    }
}

// ============================================================================
// DÉBUT DU HTML (APRÈS TOUTES LES REDIRECTIONS)
// ============================================================================
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Meta tags et CSS... -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - <?= ucfirst(str_replace('-', ' ', $page)) ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- ====================================================================
        CSS LOCAUX (VOTRE PROJET)
    ==================================================================== -->
    <!-- CSS communs (toujours chargés) -->
    <?php if (file_exists(ROOT_PATH . '/src/public/css/style.css')): ?>
        <link rel="stylesheet" href="/Ecoride/src/public/css/style.css">
    <?php endif; ?>
    
    <?php if (file_exists(ROOT_PATH . '/src/public/css/common-style.css')): ?>
        <link rel="stylesheet" href="/Ecoride/src/public/css/common-style.css">
    <?php endif; ?>
    
    <!-- CSS du footer -->
    <?php if (file_exists(ROOT_PATH . '/src/public/css/footer.css')): ?>
        <link rel="stylesheet" href="/Ecoride/src/public/css/footer.css">
    <?php endif; ?>
    
    <!-- AJOUTEZ CETTE LIGNE POUR NAV.CSS -->
    <?php if (file_exists(ROOT_PATH . '/src/public/css/nav.css')): ?>
        <link rel="stylesheet" href="/Ecoride/src/public/css/nav.css">
    <?php endif; ?>
    
    <!-- CSS responsive -->
    <?php if (file_exists(ROOT_PATH . '/src/public/css/responsive.css')): ?>
        <link rel="stylesheet" href="/Ecoride/src/public/css/responsive.css">
    <?php endif; ?>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <?php
    if (file_exists(ROOT_PATH . '/src/includes/nav.php')) {
        include ROOT_PATH . '/src/includes/nav.php';
    }
    ?>

    <!-- Contenu principal -->
    <main class="flex-grow-1">
        <?php
        // Messages de session
        if (isset($_SESSION['success'])) {
            echo '<div class="container mt-3">';
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
            echo '<i class="fas fa-check-circle me-2"></i>' . htmlspecialchars($_SESSION['success']);
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            echo '</div></div>';
            unset($_SESSION['success']);
        }
        
        if (isset($_SESSION['error'])) {
            echo '<div class="container mt-3">';
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            echo '<i class="fas fa-exclamation-triangle me-2"></i>' . htmlspecialchars($_SESSION['error']);
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            echo '</div></div>';
            unset($_SESSION['error']);
        }
        
        // Routage des pages (sans les traitements)
        $viewPath = null;
        
        switch ($page) {
            case 'accueil':
                $viewPath = ROOT_PATH . '/src/Views/accueil/accueil.php';
                break;
            
            case 'covoiturage':
                require_once ROOT_PATH . '/src/Classes/Covoiturage.php';
                $viewPath = ROOT_PATH . '/src/Views/covoiturage/liste.php';
                break;
            case 'connexion':
                $viewPath = ROOT_PATH . '/src/Views/compte/connexion.php';
                break;
            case 'inscription':
                $viewPath = ROOT_PATH . '/src/Views/compte/inscription.php';
                break;
                case 'dashboard':
                    if (!isset($_SESSION['user'])) {
                        $_SESSION['error'] = "Vous devez être connecté pour accéder au dashboard";
                        header("Location: index.php?page=connexion");
                        exit;
                    }
                    $viewPath = ROOT_PATH . '/src/Views/compte/dashboard.php';
                    break;
                
                case 'profil':
                    if (!isset($_SESSION['user'])) {
                        $_SESSION['error'] = "Vous devez être connecté pour accéder à votre profil";
                        header("Location: index.php?page=connexion");
                        exit;
                    }
                    $viewPath = ROOT_PATH . '/src/Views/compte/profil.php';
                    break;
                case 'contact':
                    $viewPath = ROOT_PATH . '/src/Views/contact/contact.php';
                    break;
            default:
                $viewPath = ROOT_PATH . '/src/Views/accueil/accueil.php';
                break;
        }
        
        // Inclusion de la vue
        if ($viewPath && file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo '<div class="container mt-5 text-center">';
            echo '<h1>404 - Page non trouvée</h1>';
            echo '<a href="/Ecoride/index.php" class="btn btn-primary">Retour à l\'accueil</a>';
            echo '</div>';
        }
        ?>
    </main>

    <!-- Footer -->
    <?php
    if (file_exists(ROOT_PATH . '/src/includes/footer.php')) {
        include ROOT_PATH . '/src/includes/footer.php';
    }
    ?>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>