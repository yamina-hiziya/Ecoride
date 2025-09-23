<?php

require_once ROOT_PATH . '/Classes/Avis.php';

if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header('Location: index.php?page=connexion');
    exit;
}

$avisManager = new Avis();
$user_id = $_SESSION['user_id'];

// Récupérer les avis en attente
$avisEnAttente = $avisManager->getAvisEnAttente($user_id);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $evaluateur_id = $_SESSION['user_id'];
    $evalue_id = $_POST['evalue_id'];
    $covoiturage_id = $_POST['covoiturage_id'];
    $note = $_POST['note'];
    $commentaire = $_POST['commentaire'] ?? null;
    
    $result = $avisManager->creerAvis($evaluateur_id, $evalue_id, $covoiturage_id, $note, $commentaire);
    
    if ($result['success']) {
        $message_success = $result['message'];
        // Recharger les avis en attente
        $avisEnAttente = $avisManager->getAvisEnAttente($user_id);
    } else {
        $message_error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donner un avis - EcoRide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Ecoride/src/public/css/common-style.css">
    <link rel="stylesheet" href="/Ecoride/src/public/css/avis.css">
</head>

<body>
    <?php include ROOT_PATH . '/includes/nav.php'; ?>

    <div class="avis-hero">
        <div class="container">
            <h1>⭐ Donner un avis</h1>
            <p>Votre avis aide la communauté EcoRide à voyager en confiance</p>
        </div>
    </div>

    <main class="container my-5">
        <?php if (isset($message_success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ <?= htmlspecialchars($message_success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($message_error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ❌ <?= htmlspecialchars($message_error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($avisEnAttente)): ?>
            <div class="empty-state-avis">
                <div class="empty-icon">🌟</div>
                <h3>Aucun avis en attente</h3>
                <p>Vous n'avez pas de trajets terminés à évaluer pour le moment.</p>
                <a href="index.php?page=covoiturage" class="btn-eco">Rechercher un trajet</a>
            </div>
        <?php else: ?>
            <div class="avis-en-attente">
                <h3>Trajets à évaluer (<?= count($avisEnAttente) ?>)</h3>
                
                <div class="row">
                    <?php foreach ($avisEnAttente as $trajet): ?>
                        <div class="col-lg-6 mb-4">
                            <div class="avis-card">
                                <div class="trajet-info">
                                    <h5><?= htmlspecialchars($trajet['ville_depart']) ?> → <?= htmlspecialchars($trajet['ville_arrivee']) ?></h5>
                                    <p class="trajet-date">📅 <?= date('d/m/Y', strtotime($trajet['date_depart'])) ?></p>
                                    <p class="personne-evaluer">
                                        Évaluer <?= htmlspecialchars($trajet['personne_a_evaluer_nom']) ?> 
                                        <span class="badge bg-info"><?= $trajet['type_evaluation'] ?></span>
                                    </p>
                                </div>
                                
                                <form method="post" class="avis-form">
                                    <input type="hidden" name="evalue_id" value="<?= $trajet['personne_a_evaluer_id'] ?>">
                                    <input type="hidden" name="covoiturage_id" value="<?= $trajet['covoiturage_id'] ?>">
                                    
                                    <div class="rating-section">
                                        <label class="form-label">Note sur 5 ⭐</label>
                                        <div class="star-rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <input type="radio" name="note" value="<?= $i ?>" id="star<?= $i ?>-<?= $trajet['covoiturage_id'] ?>" required>
                                                <label for="star<?= $i ?>-<?= $trajet['covoiturage_id'] ?>" class="star">⭐</label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="commentaire-<?= $trajet['covoiturage_id'] ?>" class="form-label">Commentaire (optionnel)</label>
                                        <textarea class="form-control eco-input" 
                                                  id="commentaire-<?= $trajet['covoiturage_id'] ?>" 
                                                  name="commentaire" 
                                                  rows="3" 
                                                  placeholder="Partagez votre expérience..."></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn-eco w-100">
                                        ⭐ Donner mon avis
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Mes avis donnés -->
        <div class="mes-avis-section mt-5">
            <h3>📊 Mes avis récents</h3>
            <div class="text-center">
                <a href="index.php?page=historique-avis" class="btn btn-outline-eco">
                    Voir tous mes avis →
                </a>
            </div>
        </div>
    </main>

    <?php include ROOT_PATH . '/includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Ecoride/src/public/js/avis.js"></script>
</body>
</html>