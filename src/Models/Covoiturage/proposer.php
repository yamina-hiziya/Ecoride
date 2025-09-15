<?php
// src/Models/Covoiturage/proposer.php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once ROOT_PATH . '/Classes/Covoiturage.php';
    
    $covoiturageManager = new Covoiturage();
    
    $lieu_depart = $_POST['lieu_depart'];
    $lieu_arrivee = $_POST['lieu_arrivee'];
    $date_depart = $_POST['date_depart'];
    $heure_depart = $_POST['heure_depart'];
    $nombre_places = $_POST['nombre_places'];
    $prix_par_personne = $_POST['prix_par_personne'];
    $conducteur_id = $_SESSION['user_id']; // Assurez-vous que l'ID de l'utilisateur est stocké en session

    if ($covoiturageManager->createTrajet($lieu_depart, $lieu_arrivee, $date_depart, $heure_depart, $nombre_places, $prix_par_personne, $conducteur_id)) {
        header('Location: index.php?page=dashboard');
        exit;
    } else {
        $_SESSION['error_message'] = "Une erreur est survenue lors de la proposition du trajet.";
        header('Location: index.php?page=proposer-covoiturage');
        exit;
    }
}
?>