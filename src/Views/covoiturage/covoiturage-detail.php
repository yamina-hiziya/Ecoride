<?php

// Récupérer l'ID du covoiturage
$covoiturageId = $_GET['id'] ?? null;

if (!$covoiturageId) {
    header('Location: index.php?page=covoiturage');
    exit;
}

require_once ROOT_PATH . '/Classes/Covoiturage.php';
$covoiturageManager = new Covoiturage();

// Récupérer les détails du covoiturage
$trajet = $covoiturageManager->getCovoiturageDetail($covoiturageId);

if (!$trajet) {
    $_SESSION['error_message'] = "Ce covoiturage n'existe pas ou n'est plus disponible.";
    header('Location: index.php?page=covoiturage');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Détail du covoiturage - Ecoride</title>
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
                <a class="nav-link" href="index.php?page=rechercher-covoiturage">Rechercher</a>
                <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']): ?>
                    <a class="nav-link" href="index.php?page=mes-covoiturages">Mes trajets</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container mt-5">
        <!-- Bouton retour -->
        <a href="index.php?page=covoiturage" class="btn btn-outline-secondary mb-4">← Retour aux covoiturages</a>

        <div class="row">
            <!-- Détails du trajet -->
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">
                            🏁 <?= htmlspecialchars($trajet['ville_depart']) ?> 
                            →
                            🎯 <?= htmlspecialchars($trajet['ville_arrivee']) ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <!-- Informations principales -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="text-primary">📅 Date et heure</h5>
                                <p class="fs-5 mb-1">
                                    <strong><?= date('d/m/Y', strtotime($trajet['date_depart'])) ?></strong>
                                </p>
                                <p class="fs-5 text-primary">
                                    <strong>🕐 <?= date('H:i', strtotime($trajet['heure_depart'])) ?></strong>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-success">💰 Prix</h5>
                                <p class="fs-3 text-success mb-1">
                                    <strong><?= number_format($trajet['prix_par_place'], 2) ?> €</strong>
                                </p>
                                <small class="text-muted">par personne</small>
                            </div>
                        </div>

                        <!-- Places et statut -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="text-info">👥 Places disponibles</h5>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success fs-6 me-2">
                                        <?= $trajet['places_restantes'] ?> / <?= $trajet['nombre_places_disponibles'] ?>
                                    </span>
                                    <?php if ($trajet['places_restantes'] > 0): ?>
                                        <span class="text-success">Places disponibles</span>
                                    <?php else: ?>
                                        <span class="text-danger">Complet</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-warning">📊 Statut</h5>
                                <span class="badge bg-<?= $trajet['statut'] == 'actif' ? 'success' : 'secondary' ?> fs-6">
                                    <?= ucfirst($trajet['statut']) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        <?php if (!empty($trajet['description'])): ?>
                            <div class="mb-4">
                                <h5 class="text-primary">💬 Description</h5>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($trajet['description'])) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Préférences -->
                        <div class="mb-4">
                            <h5 class="text-primary">🔧 Préférences du trajet</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <?php if ($trajet['fumeur_autorise']): ?>
                                            <span class="badge bg-warning me-2">🚬</span>
                                            <span>Fumeur autorisé</span>
                                        <?php else: ?>
                                            <span class="badge bg-success me-2">🚭</span>
                                            <span>Non-fumeur</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <?php if ($trajet['animaux_autorises']): ?>
                                            <span class="badge bg-info me-2">🐕</span>
                                            <span>Animaux autorisés</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary me-2">🚫</span>
                                            <span>Pas d'animaux</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <?php if ($trajet['bagage_autorise']): ?>
                                            <span class="badge bg-primary me-2">🧳</span>
                                            <span>Bagages autorisés</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary me-2">🚫</span>
                                            <span>Pas de bagages</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Véhicule -->
                        <?php if (!empty($trajet['voiture_marque']) || !empty($trajet['voiture_modele'])): ?>
                            <div class="mb-4">
                                <h5 class="text-primary">🚗 Véhicule</h5>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1">
                                        <strong><?= htmlspecialchars($trajet['voiture_marque']) ?> 
                                        <?= htmlspecialchars($trajet['voiture_modele']) ?></strong>
                                    </p>
                                    <?php if (!empty($trajet['voiture_couleur'])): ?>
                                        <p class="mb-0 text-muted">
                                            Couleur : <?= htmlspecialchars($trajet['voiture_couleur']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Impact écologique -->
                        <div class="alert alert-success">
                            <h6 class="alert-heading">🌱 Impact écologique</h6>
                            <p class="mb-1">
                                <strong>+<?= $trajet['credits_eco_gagne'] ?> crédits éco</strong> gagnés
                            </p>
                            <p class="mb-0">
                                <small>≈ <?= $trajet['credits_eco_gagne'] * 2 ?> kg de CO₂ économisés ! 🌍</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Conducteur et réservation -->
            <div class="col-lg-4">
                <!-- Informations conducteur -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">👤 Conducteur</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; font-size: 2rem;">
                                <?= strtoupper(substr($trajet['prenom'], 0, 1) . substr($trajet['nom'], 0, 1)) ?>
                            </div>
                        </div>
                        <h5><?= htmlspecialchars($trajet['prenom']) ?> <?= htmlspecialchars(substr($trajet['nom'], 0, 1)) ?>.</h5>
                        <?php if (!empty($trajet['pseudo'])): ?>
                            <p class="text-muted mb-2">@<?= htmlspecialchars($trajet['pseudo']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions de réservation -->
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">💺 Réservation</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']): ?>
                            <div class="text-center">
                                <p class="text-muted">Connectez-vous pour réserver ce trajet</p>
                                <a href="index.php?page=connexion" class="btn btn-primary">
                                    🔐 Se connecter
                                </a>
                            </div>
                        <?php elseif ($_SESSION['user_id'] == $trajet['conducteur_id']): ?>
                            <div class="text-center">
                                <p class="text-info">🚗 C'est votre trajet !</p>
                                <a href="index.php?page=mes-covoiturages" class="btn btn-warning">
                                    📊 Gérer mes trajets
                                </a>
                            </div>
                        <?php elseif ($trajet['statut'] != 'actif'): ?>
                            <div class="text-center">
                                <p class="text-danger">Ce trajet n'est plus actif</p>
                                <a href="index.php?page=covoiturage" class="btn btn-outline-primary">
                                    📋 Voir d'autres trajets
                                </a>
                            </div>
                        <?php elseif ($trajet['places_restantes'] <= 0): ?>
                            <div class="text-center">
                                <p class="text-danger">Ce trajet est complet</p>
                                <a href="index.php?page=rechercher-covoiturage" class="btn btn-outline-primary">
                                    🔍 Rechercher un autre trajet
                                </a>
                            </div>
                        <?php else: ?>
                            <form method="POST" action="index.php?page=reserver-covoiturage">
                                <input type="hidden" name="covoiturage_id" value="<?= $trajet['id'] ?>">
                                
                                <div class="mb-3">
                                    <label for="nombre_places" class="form-label">Nombre de places :</label>
                                    <select class="form-select" name="nombre_places" id="nombre_places" required>
                                        <?php for ($i = 1; $i <= min($trajet['places_restantes'], 4); $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?> place<?= $i > 1 ? 's' : '' ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <div class="bg-light p-2 rounded">
                                        <strong>Prix total : <span id="prix-total"><?= number_format($trajet['prix_par_place'], 2) ?></span> €</strong>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success w-100 btn-lg">
                                    ✅ Réserver ce trajet
                                </button>
                            </form>

                            <script>
                                document.getElementById('nombre_places').addEventListener('change', function() {
                                    const places = this.value;
                                    const prixUnitaire = <?= $trajet['prix_par_place'] ?>;
                                    const prixTotal = (places * prixUnitaire).toFixed(2);
                                    document.getElementById('prix-total').textContent = prixTotal;
                                });
                            </script>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions alternatives -->
                <div class="card mt-4 bg-light">
                    <div class="card-body">
                        <h6 class="text-primary">💡 Alternatives</h6>
                        <div class="d-grid gap-2">
                            <a href="index.php?page=rechercher-covoiturage&depart=<?= urlencode($trajet['ville_depart']) ?>" 
                               class="btn btn-outline-primary btn-sm">
                                🔍 Autres trajets depuis <?= htmlspecialchars($trajet['ville_depart']) ?>
                            </a>
                            <a href="index.php?page=rechercher-covoiturage&arrivee=<?= urlencode($trajet['ville_arrivee']) ?>" 
                               class="btn btn-outline-primary btn-sm">
                                🔍 Autres trajets vers <?= htmlspecialchars($trajet['ville_arrivee']) ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p>&copy; <?= date('Y') ?> EcoRide. Voyagez écologique ! 🌱</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>