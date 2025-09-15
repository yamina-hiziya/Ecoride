<?php
// src/Models/Covoiturage/supprimer.php
require_once ROOT_PATH . '/Classes/Covoiturage.php';
require_once ROOT_PATH . '/Classes/Auth.php';

if (isset($_GET['id'])) {
    $auth = new Auth();
    if (!$auth->isLoggedIn()) {
        header('Location: /Ecoride/src/index.php?page=connexion');
        exit;
    }

    $covoiturage = new Covoiturage();
    if ($covoiturage->deleteTrajet($_GET['id'])) {
        $_SESSION['success_message'] = "Le covoiturage a été supprimé avec succès.";
    } else {
        $_SESSION['error_message'] = "Une erreur est survenue lors de la suppression.";
    }

    header('Location: /Ecoride/src/index.php?page=mes-covoiturages');
    exit;
}
?>