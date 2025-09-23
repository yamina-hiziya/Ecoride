<?php
// Vue proposer un covoiturage - Vue partielle

// Vérification d'authentification
if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    echo '<div class="alert alert-warning">Vous devez être connecté pour proposer un trajet.</div>';
    echo '<a href="index.php?page=connexion" class="btn btn-primary">Se connecter</a>';
    return;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'Utilisateur';
?>

<!-- CSS spécifique -->
<link rel="stylesheet" href="/Ecoride/src/public/css/covoiturage.css">

<div class="container mt-4">
    <!-- Messages de session -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-4 text-success">🚗 Proposer un trajet</h1>
            <p class="lead text-muted">Créez votre covoiturage et gagnez des crédits écologiques !</p>
        </div>
    </div>

    <!-- Formulaire principal -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">📍 Détails du trajet</h3>
                </div>
                <div class="card-body p-4">
                    <form action="index.php?page=proposer-traitement" method="POST" novalidate>
                        
                        <!-- Trajet -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="ville_depart" class="form-label">
                                    <strong>🎯 Ville de départ *</strong>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="ville_depart" 
                                       name="ville_depart" 
                                       required 
                                       placeholder="Paris, Lyon, Marseille..."
                                       value="<?= htmlspecialchars($_POST['ville_depart'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="ville_arrivee" class="form-label">
                                    <strong>🏁 Ville d'arrivée *</strong>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="ville_arrivee" 
                                       name="ville_arrivee" 
                                       required 
                                       placeholder="Nice, Toulouse, Bordeaux..."
                                       value="<?= htmlspecialchars($_POST['ville_arrivee'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- Adresses précises -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="adresse_depart" class="form-label">
                                    <strong>📍 Adresse de départ</strong>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="adresse_depart" 
                                       name="adresse_depart" 
                                       placeholder="Adresse précise, gare, parking..."
                                       value="<?= htmlspecialchars($_POST['adresse_depart'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="adresse_arrivee" class="form-label">
                                    <strong>📍 Adresse d'arrivée</strong>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="adresse_arrivee" 
                                       name="adresse_arrivee" 
                                       placeholder="Adresse précise, gare, parking..."
                                       value="<?= htmlspecialchars($_POST['adresse_arrivee'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- Date et heure -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="date_depart" class="form-label">
                                    <strong>📅 Date de départ *</strong>
                                </label>
                                <input type="date" 
                                       class="form-control form-control-lg" 
                                       id="date_depart" 
                                       name="date_depart" 
                                       required 
                                       min="<?= date('Y-m-d') ?>"
                                       value="<?= htmlspecialchars($_POST['date_depart'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="heure_depart" class="form-label">
                                    <strong>🕐 Heure de départ *</strong>
                                </label>
                                <input type="time" 
                                       class="form-control form-control-lg" 
                                       id="heure_depart" 
                                       name="heure_depart" 
                                       required 
                                       value="<?= htmlspecialchars($_POST['heure_depart'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="duree_estimee" class="form-label">
                                    <strong>⏱️ Durée estimée</strong>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="duree_estimee" 
                                       name="duree_estimee" 
                                       placeholder="2h30"
                                       value="<?= htmlspecialchars($_POST['duree_estimee'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- Places et prix -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="nombre_places" class="form-label">
                                    <strong>👥 Places disponibles *</strong>
                                </label>
                                <select class="form-select form-select-lg" 
                                        id="nombre_places" 
                                        name="nombre_places" 
                                        required>
                                    <option value="">Choisir...</option>
                                    <?php for($i = 1; $i <= 8; $i++): ?>
                                        <option value="<?= $i ?>" <?= (($_POST['nombre_places'] ?? '') == $i) ? 'selected' : '' ?>>
                                            <?= $i ?> place<?= $i > 1 ? 's' : '' ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="prix_par_personne" class="form-label">
                                    <strong>💰 Prix par personne *</strong>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control form-control-lg" 
                                           id="prix_par_personne" 
                                           name="prix_par_personne" 
                                           required 
                                           min="0" 
                                           step="0.50"
                                           placeholder="15.00"
                                           value="<?= htmlspecialchars($_POST['prix_par_personne'] ?? '') ?>">
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="prix_total" class="form-label">
                                    <strong>💵 Prix total estimé</strong>
                                </label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           id="prix_total" 
                                           readonly 
                                           placeholder="Calculé automatiquement">
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <strong>📝 Description du trajet</strong>
                            </label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Décrivez votre trajet : arrêts prévus, bagages autorisés, ambiance souhaitée..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                            <div class="form-text">
                                Ajoutez des détails pour rassurer les passagers
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-success">🚗 Options du trajet</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="fumeur_autorise" 
                                           name="fumeur_autorise" 
                                           value="1">
                                    <label class="form-check-label" for="fumeur_autorise">
                                        🚬 Fumeur autorisé
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="animaux_autorises" 
                                           name="animaux_autorises" 
                                           value="1">
                                    <label class="form-check-label" for="animaux_autorises">
                                        🐕 Animaux autorisés
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="reservation_instantanee" 
                                           name="reservation_instantanee" 
                                           value="1" 
                                           checked>
                                    <label class="form-check-label" for="reservation_instantanee">
                                        ⚡ Réservation instantanée
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="musique_autorisee" 
                                           name="musique_autorisee" 
                                           value="1" 
                                           checked>
                                    <label class="form-check-label" for="musique_autorisee">
                                        🎵 Musique autorisée
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="bagages_volumineux" 
                                           name="bagages_volumineux" 
                                           value="1">
                                    <label class="form-check-label" for="bagages_volumineux">
                                        🧳 Bagages volumineux autorisés
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="detour_possible" 
                                           name="detour_possible" 
                                           value="1">
                                    <label class="form-check-label" for="detour_possible">
                                        🔄 Détour possible
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Véhicule -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-success">🚙 Informations véhicule</h5>
                            </div>
                            <div class="col-md-4">
                                <label for="marque_vehicule" class="form-label">Marque</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="marque_vehicule" 
                                       name="marque_vehicule" 
                                       placeholder="Renault, Peugeot..."
                                       value="<?= htmlspecialchars($_POST['marque_vehicule'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="modele_vehicule" class="form-label">Modèle</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="modele_vehicule" 
                                       name="modele_vehicule" 
                                       placeholder="Clio, 208..."
                                       value="<?= htmlspecialchars($_POST['modele_vehicule'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="couleur_vehicule" class="form-label">Couleur</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="couleur_vehicule" 
                                       name="couleur_vehicule" 
                                       placeholder="Noir, Blanc, Rouge..."
                                       value="<?= htmlspecialchars($_POST['couleur_vehicule'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php?page=mes-trajets" class="btn btn-outline-secondary btn-lg me-md-2">
                                ❌ Annuler
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                🚀 Publier le trajet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Conseils -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h4 class="text-success">💡 Conseils pour un trajet réussi</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <h6>📍 Soyez précis</h6>
                            <p class="small text-muted">Indiquez des points de rendez-vous faciles à trouver</p>
                        </div>
                        <div class="col-md-4">
                            <h6>💰 Prix juste</h6>
                            <p class="small text-muted">Calculez selon les frais réels (essence, péages)</p>
                        </div>
                        <div class="col-md-4">
                            <h6>📱 Restez disponible</h6>
                            <p class="small text-muted">Répondez rapidement aux demandes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script de calcul automatique -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const places = document.getElementById('nombre_places');
    const prix = document.getElementById('prix_par_personne');
    const total = document.getElementById('prix_total');
    
    function calculerTotal() {
        const nbPlaces = parseInt(places.value) || 0;
        const prixUnite = parseFloat(prix.value) || 0;
        const prixTotal = nbPlaces * prixUnite;
        total.value = prixTotal > 0 ? prixTotal.toFixed(2) : '';
    }
    
    places.addEventListener('change', calculerTotal);
    prix.addEventListener('input', calculerTotal);
    
    // Validation du formulaire
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>