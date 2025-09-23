<?php
// Vue liste des covoiturages - Vue partielle

// Inclusion des classes
if (file_exists(ROOT_PATH . '/src/Classes/Covoiturage.php')) {
    require_once ROOT_PATH . '/src/Classes/Covoiturage.php';
}

// Gestion des paramètres de recherche
$ville_depart = $_GET['ville_depart'] ?? '';
$ville_arrivee = $_GET['ville_arrivee'] ?? '';
$date_depart = $_GET['date_depart'] ?? '';
$nb_passagers = $_GET['nb_passagers'] ?? '';

// Récupération des trajets
try {
    if (class_exists('Covoiturage')) {
        $covoiturage = new Covoiturage();
        $trajets = $covoiturage->rechercherTrajets($ville_depart, $ville_arrivee, $date_depart, $nb_passagers);
    } else {
        // Données de test si classe non disponible
        $trajets = [
            [
                'id' => 1,
                'ville_depart' => 'Paris',
                'ville_arrivee' => 'Lyon',
                'date_depart' => date('Y-m-d', strtotime('+3 days')),
                'heure_depart' => '14:30:00',
                'prix_par_personne' => 25.50,
                'nombre_places' => 3,
                'conducteur_nom' => 'Martin',
                'conducteur_prenom' => 'Jean',
                'description' => 'Trajet direct, ambiance détendue'
            ],
            [
                'id' => 2,
                'ville_depart' => 'Lyon',
                'ville_arrivee' => 'Marseille',
                'date_depart' => date('Y-m-d', strtotime('+5 days')),
                'heure_depart' => '09:15:00',
                'prix_par_personne' => 18.00,
                'nombre_places' => 2,
                'conducteur_nom' => 'Dupont',
                'conducteur_prenom' => 'Marie',
                'description' => 'Voyage en musique'
            ],
            [
                'id' => 3,
                'ville_depart' => 'Paris',
                'ville_arrivee' => 'Bordeaux',
                'date_depart' => date('Y-m-d', strtotime('+7 days')),
                'heure_depart' => '08:00:00',
                'prix_par_personne' => 35.00,
                'nombre_places' => 4,
                'conducteur_nom' => 'Leroy',
                'conducteur_prenom' => 'Pierre',
                'description' => 'Trajet matinal, non-fumeur'
            ]
        ];
    }
} catch (Exception $e) {
    $trajets = [];
    $error = "Erreur lors de la recherche : " . $e->getMessage();
}
?>

<!-- CSS spécifique -->
<link rel="stylesheet" href="/Ecoride/src/public/css/covoiturage.css">

<div class="covoiturage-container">
    <div class="container mt-4">
        <!-- Header avec recherche -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 class="display-4 text-success">🚗 Trajets disponibles</h1>
                <p class="lead text-muted">Trouvez le covoiturage parfait pour votre voyage</p>
            </div>
        </div>
        
        <!-- Messages d'erreur -->
        <?php if (isset($error)): ?>
            <div class="alert alert-warning">
                <strong>⚠️ Attention :</strong> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <!-- Formulaire de recherche -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white">
                <h3 class="card-title mb-0">🔍 Rechercher un trajet</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="">
                    <input type="hidden" name="page" value="covoiturage">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="ville_depart" class="form-label">🎯 Départ</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="ville_depart"
                                   name="ville_depart"
                                   placeholder="Paris, Lyon..."
                                   value="<?= htmlspecialchars($ville_depart) ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="ville_arrivee" class="form-label">🏁 Arrivée</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="ville_arrivee"
                                   name="ville_arrivee"
                                   placeholder="Marseille, Bordeaux..."
                                   value="<?= htmlspecialchars($ville_arrivee) ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="date_depart" class="form-label">📅 Date</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_depart"
                                   name="date_depart"
                                   min="<?= date('Y-m-d') ?>"
                                   value="<?= htmlspecialchars($date_depart) ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="nb_passagers" class="form-label">👥 Passagers</label>
                            <select class="form-select" id="nb_passagers" name="nb_passagers">
                                <option value="">Tous</option>
                                <?php for($i = 1; $i <= 8; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($nb_passagers == $i) ? 'selected' : '' ?>>
                                        <?= $i ?> passager<?= $i > 1 ? 's' : '' ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    🔍 Rechercher
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Résultats -->
        <div class="row">
            <div class="col-12">
                <?php if (empty($trajets)): ?>
                    <div class="alert alert-info text-center">
                        <h4>🔍 Aucun trajet trouvé</h4>
                        <p>Essayez de modifier vos critères de recherche ou <a href="index.php?page=proposer-covoiturage" class="text-success">proposez votre propre trajet</a> !</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3><?= count($trajets) ?> trajet<?= count($trajets) > 1 ? 's' : '' ?> trouvé<?= count($trajets) > 1 ? 's' : '' ?></h3>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-success active" data-bs-toggle="button">
                                📋 Liste
                            </button>
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="button">
                                🗺️ Carte
                            </button>
                        </div>
                    </div>

                    <!-- Liste des trajets -->
                    <div class="trajets-liste">
                        <?php foreach ($trajets as $trajet): ?>
                            <div class="card mb-3 trajet-card shadow-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <!-- Informations trajet -->
                                        <div class="col-md-6">
                                            <div class="trajet-route mb-2">
                                                <h5 class="mb-1">
                                                    <span class="text-success">🎯 <?= htmlspecialchars($trajet['ville_depart'] ?? 'N/A') ?></span>
                                                    <i class="fas fa-arrow-right mx-2"></i>
                                                    <span class="text-primary">🏁 <?= htmlspecialchars($trajet['ville_arrivee'] ?? 'N/A') ?></span>
                                                </h5>
                                            </div>
                                            
                                            <div class="trajet-datetime mb-2">
                                                <span class="badge bg-light text-dark me-2">
                                                    📅 <?= date('d/m/Y', strtotime($trajet['date_depart'] ?? '')) ?>
                                                </span>
                                                <span class="badge bg-light text-dark">
                                                    🕐 <?= date('H:i', strtotime($trajet['heure_depart'] ?? '')) ?>
                                                </span>
                                            </div>

                                            <div class="conducteur-info">
                                                <small class="text-muted">
                                                    👤 Conducteur : 
                                                    <strong>
                                                        <?= htmlspecialchars(($trajet['conducteur_prenom'] ?? '') . ' ' . ($trajet['conducteur_nom'] ?? '')) ?>
                                                    </strong>
                                                </small>
                                            </div>

                                            <?php if (!empty($trajet['description'])): ?>
                                                <div class="trajet-description mt-2">
                                                    <small class="text-muted">
                                                        💬 <?= htmlspecialchars($trajet['description']) ?>
                                                    </small>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Prix et places -->
                                        <div class="col-md-3 text-center">
                                            <div class="prix-info mb-2">
                                                <h4 class="text-success mb-1">
                                                    <?= number_format($trajet['prix_par_personne'] ?? $trajet['prix'] ?? 0, 2) ?> €
                                                </h4>
                                                <small class="text-muted">par personne</small>
                                            </div>
                                            
                                            <div class="places-info">
                                                <?php 
                                                $places = $trajet['nombre_places'] ?? $trajet['places_disponibles'] ?? 0;
                                                $couleur = $places > 2 ? 'success' : ($places > 0 ? 'warning' : 'danger');
                                                ?>
                                                <span class="badge bg-<?= $couleur ?> mb-1">
                                                    👥 <?= $places ?> place<?= $places > 1 ? 's' : '' ?>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="col-md-3 text-end">
                                            <div class="actions-buttons">
                                                <a href="index.php?page=trajet-details&id=<?= $trajet['id'] ?>" 
                                                   class="btn btn-outline-primary btn-sm mb-1 d-block">
                                                    👁️ Voir détails
                                                </a>
                                                
                                                <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']): ?>
                                                    <?php if ($places > 0): ?>
                                                        <a href="index.php?page=reserver-trajet&id=<?= $trajet['id'] ?>" 
                                                           class="btn btn-success btn-sm d-block">
                                                            🚗 Réserver
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn btn-secondary btn-sm d-block" disabled>
                                                            ❌ Complet
                                                        </button>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <a href="index.php?page=connexion" 
                                                       class="btn btn-outline-success btn-sm d-block">
                                                        🔑 Se connecter
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">📢 Vous ne trouvez pas votre trajet ?</h5>
                        <p class="card-text">Proposez votre propre covoiturage et gagnez des crédits écologiques !</p>
                        <a href="index.php?page=proposer-covoiturage" class="btn btn-success btn-lg">
                            🚗 Proposer un trajet
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script pour recherche en temps réel -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit du formulaire après changement
    const searchForm = document.querySelector('form');
    const inputs = searchForm.querySelectorAll('input, select');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            // Optionnel : auto-submit après changement
            // searchForm.submit();
        });
    });
    
    // Animation des cartes
    const cards = document.querySelectorAll('.trajet-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = (index * 0.1) + 's';
        card.classList.add('fade-in');
    });
});
</script>

<style>
/* Animations pour les cartes */
.fade-in {
    animation: fadeInUp 0.6s ease-out both;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.trajet-card {
    transition: all 0.3s ease;
    border-left: 4px solid #28a745;
}

.trajet-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
}

.prix-info h4 {
    font-weight: bold;
}

.actions-buttons .btn {
    min-width: 120px;
}
</style>