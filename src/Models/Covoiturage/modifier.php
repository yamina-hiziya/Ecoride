<?php
// src/Models/Covoiturage/modifier.php
require_once ROOT_PATH . '/Classes/Covoiturage.php';
require_once ROOT_PATH . '/Classes/Auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new Auth();
    if (!$auth->isLoggedIn()) {
        header('Location: /Ecoride/src/index.php?page=connexion');
        exit;
    }

    $covoiturage = new Covoiturage();
    if ($covoiturage->updateTrajet($_POST['id'], $_POST['depart'], $_POST['arrivee'], $_POST['date'], $_POST['heure'], $_POST['places'], $_POST['prix'])) {
        $_SESSION['success_message'] = "Le covoiturage a été modifié avec succès !";
        header('Location: /Ecoride/src/index.php?page=mes-covoiturages');
    } else {
        $_SESSION['error_message'] = "Une erreur est survenue lors de la modification.";
        header('Location: /Ecoride/src/index.php?page=modifier-covoiturage&id=' . $_POST['id']);
    }
    exit;
}
?>