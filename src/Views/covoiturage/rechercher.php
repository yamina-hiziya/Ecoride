<?php

// Récupération des paramètres de recherche
$depart = $_GET['depart'] ?? '';
$arrivee = $_GET['arrivee'] ?? '';
$date = $_GET['date'] ?? '';

require_once ROOT_PATH . '/Classes/Covoiturage.php';
$covoiturageManager = new Covoiturage();

// Si recherche effectuée
if (!empty($depart) || !empty($arrivee) || !empty($date)) {
    $trajets = $covoiturageManager->rechercherTrajets($depart, $arrivee, $date);
} else {
    $trajets = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Rechercher un covoiturage - Ecoride</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">🌱 EcoRide</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?page=dashboard">Dashboard</a>
                <a class="nav-link" href="index.php?page=covoiturage">Covoiturages</a>
                <a class="nav-link active" href="index.php?page=rechercher-covoiturage">Rechercher</a>
            </div>
        </div>
    </nav>

    <main class="container mt-5">
        <div class="row mb-4">
            <div class="col-12">
                <div class="bg-primary text-white p-4 rounded">
                    <h1 class="mb-2">🔍 Rechercher un covoiturage</h1>
                    <p class="lead mb-0">Trouvez le trajet parfait ! 🌱</p>
                </div>
            </div>
        </div>

        <!-- Formulaire de recherche -->
        <div class="card mb-4 shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">🎯 Recherche</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="index.php">
                    <input type="hidden" name="page" value="rechercher-covoiturage">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">🏁 Départ</label>
                            <input type="text" class="form-control" name="depart" value="<?= htmlspecialchars($depart) ?>" placeholder="Ville de départ">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">🎯 Arrivée</label>
                            <input type="text" class="form-control" name="arrivee" value="<?= htmlspecialchars($arrivee) ?>" placeholder="Ville d'arrivée">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">📅 Date</label>
                            <input type="date" class="form-control" name="date" value="<?= htmlspecialchars($date) ?>">
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary">🔍 Rechercher</button>
                        <a href="index.php?page=covoiturage" class="btn btn-outline-secondary">📋 Tous les trajets</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Résultats -->
        <?php if (!empty($depart) || !empty($arrivee) || !empty($date)): ?>
            <h2>🎯 Résultats (<?= count($trajets) ?> trouvé(s))</h2>
            
            <?php if (empty($trajets)): ?>
                <div class="alert alert-info text-center">
                    <h4>🚫 Aucun trajet trouvé</h4>
                    <p>Essayez avec d'autres critères</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($trajets as $trajet): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5><?= htmlspecialchars($trajet['ville_depart']) ?> → <?= htmlspecialchars($trajet['ville_arrivee']) ?></h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>📅 Date :</strong> <?= date('d/m/Y', strtotime($trajet['date_depart'])) ?></p>
                                    <p><strong>🕐 Heure :</strong> <?= date('H:i', strtotime($trajet['heure_depart'])) ?></p>
                                    <p><strong>👥 Places :</strong> <?= $trajet['places_restantes'] ?></p>
                                    <p><strong>💰 Prix :</strong> <?= $trajet['prix_par_place'] ?> €</p>
                                    <p><strong>👤 Conducteur :</strong> <?= htmlspecialchars($trajet['prenom']) ?></p>
                                </div>
                                <div class="card-footer">
                                    <a href="index.php?page=covoiturage-detail&id=<?= $trajet['id'] ?>" class="btn btn-primary">Voir détails</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p>&copy; <?= date('Y') ?> EcoRide. Voyagez écologique ! 🌱</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>