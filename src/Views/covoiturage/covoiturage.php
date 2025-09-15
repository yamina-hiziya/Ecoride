<?php
require_once ROOT_PATH . '/Classes/Covoiturage.php';
$covoiturageManager = new Covoiturage();
$trajets = $covoiturageManager->getPublicTrajets();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Covoiturages - Ecoride</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Ecoride/src/public/css/style.css">
</head>
<body>
    <?php include ROOT_PATH . '/includes/nav.php'; ?>
    <main class="container mt-5">
        <h1 class="text-center">Trouver un covoiturage</h1>
        <div class="card my-4 p-4 shadow-sm">
            <h5 class="mb-3">Rechercher un trajet</h5>
            <form action="index.php?page=recherche" method="GET">
                <input type="hidden" name="page" value="recherche">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="depart" class="form-label">Départ</label>
                        <input type="text" class="form-control" id="depart" name="depart" placeholder="Ville de départ">
                    </div>
                    <div class="col-md-6">
                        <label for="arrivee" class="form-label">Arrivée</label>
                        <input type="text" class="form-control" id="arrivee" name="arrivee" placeholder="Ville d'arrivée">
                    </div>
                    <div class="col-md-6">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date">
                    </div>
                    <div class="col-md-6">
                        <label for="places" class="form-label">Places</label>
                        <input type="number" class="form-control" id="places" name="places" min="1">
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
            </form>
        </div>
        <hr class="my-4">
        <div class="text-center my-4">
            <a href="index.php?page=proposer-covoiturage" class="btn btn-success">Proposer un nouveau covoiturage</a>
        </div>
        <h2>Tous les trajets disponibles</h2>
        <div class="row">
            <?php if (empty($trajets)): ?>
                <p class="text-center">Aucun covoiturage n'est encore disponible.</p>
            <?php else: ?>
                <?php foreach ($trajets as $trajet): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($trajet->lieu_depart) ?> → <?= htmlspecialchars($trajet->lieu_arrivee) ?></h5>
                                <p class="card-text">
                                    <strong>Date :</strong> <?= htmlspecialchars($trajet->date_depart) ?><br>
                                    <strong>Heure :</strong> <?= htmlspecialchars($trajet->heure_depart) ?><br>
                                    <strong>Places disponibles :</strong> <?= htmlspecialchars($trajet->nombre_places) ?><br>
                                    <strong>Prix par personne :</strong> <?= htmlspecialchars($trajet->prix_par_personne) ?> €
                                </p>
                                <a href="index.php?page=covoiturage-detail&id=<?= $trajet->id ?>" class="btn btn-primary">Voir les détails</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    <?php include ROOT_PATH . '/includes/footer.php'; ?>
</body>
</html>