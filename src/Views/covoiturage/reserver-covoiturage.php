<?php

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    $_SESSION['error_message'] = "Vous devez être connecté pour réserver un covoiturage.";
    header('Location: index.php?page=connexion');
    exit;
}

// Traitement du formulaire de réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once ROOT_PATH . '/Classes/Covoiturage.php';
    $covoiturageManager = new Covoiturage();
    
    $covoiturageId = $_POST['covoiturage_id'] ?? null;
    $nombrePlaces = $_POST['nombre_places'] ?? null;
    $passagerId = $_SESSION['user_id'];
    
    if (!$covoiturageId || !$nombrePlaces) {
        $_SESSION['error_message'] = "Données manquantes pour la réservation.";
        header('Location: index.php?page=covoiturage');
        exit;
    }
    
    // Récupérer les détails du covoiturage
    $trajet = $covoiturageManager->getCovoiturageDetail($covoiturageId);
    
    if (!$trajet) {
        $_SESSION['error_message'] = "Ce covoiturage n'existe pas.";
        header('Location: index.php?page=covoiturage');
        exit;
    }
    
    // Vérifications de sécurité
    if ($trajet['conducteur_id'] == $passagerId) {
        $_SESSION['error_message'] = "Vous ne pouvez pas réserver votre propre trajet.";
        header('Location: index.php?page=covoiturage-detail&id=' . $covoiturageId);
        exit;
    }
    
    if ($trajet['statut'] != 'actif') {
        $_SESSION['error_message'] = "Ce trajet n'est plus disponible.";
        header('Location: index.php?page=covoiturage-detail&id=' . $covoiturageId);
        exit;
    }
    
    if ($trajet['places_restantes'] < $nombrePlaces) {
        $_SESSION['error_message'] = "Il n'y a plus assez de places disponibles.";
        header('Location: index.php?page=covoiturage-detail&id=' . $covoiturageId);
        exit;
    }
    
    // Calculer le prix total
    $prixTotal = $trajet['prix_par_place'] * $nombrePlaces;
    
    // Effectuer la réservation
    $reservationId = $covoiturageManager->creerReservation(
        $covoiturageId, 
        $passagerId, 
        $nombrePlaces, 
        $prixTotal
    );
    
    if ($reservationId) {
        $_SESSION['success_message'] = "Réservation confirmée ! Vous recevrez bientôt les détails par email.";
        header('Location: index.php?page=mes-covoiturages');
        exit;
    } else {
        $_SESSION['error_message'] = "Erreur lors de la réservation. Veuillez réessayer.";
        header('Location: index.php?page=covoiturage-detail&id=' . $covoiturageId);
        exit;
    }
}

// Si on arrive ici en GET, rediriger vers la liste
header('Location: index.php?page=covoiturage');
exit;
?>