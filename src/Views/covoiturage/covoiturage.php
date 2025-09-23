<?php

require_once ROOT_PATH . '/Classes/Covoiturage.php';
$covoiturageManager = new Covoiturage();
$trajets = $covoiturageManager->getPublicTrajets();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Covoiturages - EcoRide</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Ecoride/src/public/css/common-style.css">
    <link rel="stylesheet" href="/Ecoride/src/public/css/covoiturage.css">
</head>

<body>
    <?php include ROOT_PATH . '/includes/nav.php'; ?>
    
    <!-- Hero Section Covoiturage -->
    <div class="covoiturage-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="hero-title">🔍 Trouver un covoiturage</h1>
                    <p class="hero-subtitle">Recherchez le trajet parfait et voyagez de manière écologique ! 🌱</p>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="hero-icon">🚗💚</div>
                </div>
            </div>
        </div>
    </div>

    <main class="container my-5">
        <!-- Formulaire de recherche harmonisé -->
        <div class="filters-card">
            <h5 class="filter-title">🔎 Rechercher un trajet</h5>
            <form action="index.php?page=rechercher-covoiturage" method="GET">
                <input type="hidden" name="page" value="rechercher-covoiturage">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="depart" class="filter-label">🏁 Ville de départ</label>
                        <input type="text" class="form-control eco-input" id="depart" name="depart" 
                               placeholder="Ex: Paris, Lyon, Marseille..." 
                               value="<?php echo htmlspecialchars($_GET['depart'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="arrivee" class="filter-label">🎯 Ville d'arrivée</label>
                        <input type="text" class="form-control eco-input" id="arrivee" name="arrivee" 
                               placeholder="Ex: Nice, Bordeaux, Lille..."
                               value="<?php echo htmlspecialchars($_GET['arrivee'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="date" class="filter-label">📅 Date de départ</label>
                        <input type="date" class="form-control eco-input" id="date" name="date" 
                               min="<?php echo date('Y-m-d'); ?>"
                               value="<?php echo htmlspecialchars($_GET['date'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="places" class="filter-label">👥 Nombre de places</label>
                        <select class="form-control eco-input" id="places" name="places">
                            <option value="">Peu importe</option>
                            <option value="1" <?php echo ($_GET['places'] ?? '') == '1' ? 'selected' : ''; ?>>1 place</option>
                            <option value="2" <?php echo ($_GET['places'] ?? '') == '2' ? 'selected' : ''; ?>>2 places</option>
                            <option value="3" <?php echo ($_GET['places'] ?? '') == '3' ? 'selected' : ''; ?>>3 places</option>
                            <option value="4" <?php echo ($_GET['places'] ?? '') == '4' ? 'selected' : ''; ?>>4+ places</option>
                        </select>
                    </div>
                </div>
                
                <!-- Filtres avancés -->
                <div class="row g-3 mt-3">
                    <div class="col-md-4">
                        <label for="prix_max" class="filter-label">💰 Prix maximum</label>
                        <input type="number" class="form-control eco-input" id="prix_max" name="prix_max" 
                               placeholder="Ex: 50" min="0" step="5"
                               value="<?php echo htmlspecialchars($_GET['prix_max'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <div class="filter-group">
                            <label class="filter-label">🚭 Préférences</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="non_fumeur" name="non_fumeur" 
                                       <?php echo isset($_GET['non_fumeur']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="non_fumeur">
                                    Non fumeur uniquement
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="d-grid w-100">
                            <button type="submit" class="btn-eco-large">
                                🔍 Rechercher
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistiques rapides -->
        <div class="row mb-4">
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">🚗</div>
                    <div class="stat-number"><?php echo count($trajets); ?></div>
                    <div class="stat-label">Trajets disponibles</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">🌱</div>
                    <div class="stat-number"><?php echo array_sum(array_column($trajets, 'credits_eco_gagne')); ?></div>
                    <div class="stat-label">Crédits écologiques</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-number"><?php echo number_format(array_sum(array_column($trajets, 'prix_par_place')) / count($trajets), 0); ?>€</div>
                    <div class="stat-label">Prix moyen</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number"><?php echo array_sum(array_column($trajets, 'places_restantes')); ?></div>
                    <div class="stat-label">Places libres</div>
                </div>
            </div>
        </div>

        <!-- Liste des trajets -->
        <div class="trajets-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title-eco">Trajets disponibles</h2>
                <div class="view-options">
                    <button class="btn btn-outline-eco active" data-view="grid">
                        📋 Liste
                    </button>
                    <button class="btn btn-outline-eco" data-view="map">
                        🗺️ Carte
                    </button>
                </div>
            </div>

            <?php if (empty($trajets)): ?>
                <div class="empty-state-eco">
                    <h4>Aucun covoiturage disponible</h4>
                    <p>Soyez le premier à proposer un trajet écologique !</p>
                    <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']): ?>
                        <a href="index.php?page=proposer-covoiturage" class="btn-eco">Proposer un trajet</a>
                    <?php else: ?>
                        <a href="index.php?page=inscription" class="btn-eco">S'inscrire pour proposer</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="trajets-grid">
                    <?php foreach ($trajets as $trajet): ?>
                        <div class="trajet-item">
                            <!-- Route principale -->
                            <div class="trajet-route">
                                <div class="route-cities">
                                    <?= htmlspecialchars($trajet['ville_depart']) ?> → <?= htmlspecialchars($trajet['ville_arrivee']) ?>
                                </div>
                                <div class="route-price">
                                    <?= htmlspecialchars($trajet['prix_par_place']) ?>€
                                </div>
                            </div>

                            <!-- Détails du trajet -->
                            <div class="trajet-details">
                                <div class="detail-badge">
                                    <span>📅</span>
                                    <span><?= date('d/m/Y', strtotime($trajet['date_depart'])) ?></span>
                                </div>
                                <div class="detail-badge">
                                    <span>🕐</span>
                                    <span><?= date('H:i', strtotime($trajet['heure_depart'])) ?></span>
                                </div>
                                <div class="detail-badge">
                                    <span>👥</span>
                                    <span><?= $trajet['places_restantes'] ?> places libres</span>
                                </div>
                                <div class="detail-badge">
                                    <span>🌿</span>
                                    <span><?= $trajet['credits_eco_gagne'] ?> crédits éco</span>
                                </div>
                            </div>

                            <!-- Informations conducteur -->
                            <div class="trajet-driver">
                                <div class="driver-avatar">
                                    <?= strtoupper(substr($trajet['prenom'], 0, 1)) ?>
                                </div>
                                <div class="driver-info">
                                    <h6><?= htmlspecialchars($trajet['prenom'] . ' ' . substr($trajet['nom'], 0, 1)) ?>.</h6>
                                    <div class="driver-rating">
                                        ⭐⭐⭐⭐⭐ • <?= $trajet['voiture_marque'] ?> <?= $trajet['voiture_modele'] ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Description si disponible -->
                            <?php if (!empty($trajet['description'])): ?>
                                <div class="trajet-description">
                                    <em>"<?= htmlspecialchars(substr($trajet['description'], 0, 100)) ?><?= strlen($trajet['description']) > 100 ? '...' : '' ?>"</em>
                                </div>
                            <?php endif; ?>

                            <!-- Actions -->
                            <div class="trajet-actions">
                                <a href="index.php?page=covoiturage-detail&id=<?= $trajet['id'] ?>" 
                                   class="btn btn-outline-eco">
                                    📋 Voir détails
                                </a>
                                <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']): ?>
                                    <a href="index.php?page=reserver-trajet&id=<?= $trajet['id'] ?>" 
                                       class="btn-eco">
                                        🎫 Réserver
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?page=connexion" class="btn-eco">
                                        🔐 Se connecter pour réserver
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination si nécessaire -->
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Navigation des trajets">
                        <ul class="pagination pagination-eco">
                            <li class="page-item disabled">
                                <span class="page-link">Précédent</span>
                            </li>
                            <li class="page-item active">
                                <span class="page-link">1</span>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Suivant</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include ROOT_PATH . '/includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Basculer entre vue liste et carte
        document.querySelectorAll('[data-view]').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('[data-view]').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Ici vous pouvez ajouter la logique pour changer la vue
                const view = this.getAttribute('data-view');
                console.log('Vue sélectionnée:', view);
            });
        });
    </script>
</body>
</html>