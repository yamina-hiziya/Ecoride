<?php
// ============================================================================
// CONFIGURATION ECORIDE - Paramètres de l'application
// ============================================================================

// Configuration de la base de données
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'ecoride_db');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_CHARSET', 'utf8mb4');
}

// Configuration des chemins
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__);
}
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/Ecoride');
}

// Configuration des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuration de session AVANT session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 7200, // 2 heures
        'path' => '/',
        'domain' => '',
        'secure' => false, // Mettre true en HTTPS
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
}

// Timezone
date_default_timezone_set('Europe/Paris');

// Fonctions utilitaires
if (!function_exists('debug')) {
    function debug($var, $die = false) {
        echo "<pre style='background: #f8f9fa; padding: 10px; border: 1px solid #dee2e6; margin: 10px 0;'>";
        print_r($var);
        echo "</pre>";
        if ($die) die();
    }
}

if (!function_exists('sanitize')) {
    function sanitize($string) {
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('redirectTo')) {
    function redirectTo($page) {
        header("Location: http://localhost/Ecoride/index.php?page=" . $page);
        exit;
    }
}
?>