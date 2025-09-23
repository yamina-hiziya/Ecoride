<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Ecoride/src/Views/compte/profil.php
// Page profil utilisateur - Vue

// Vérifier l'authentification
if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header('Location: /Ecoride/index.php?page=connexion');
    exit;
}

// Récupérer les informations utilisateur
$user_id = $_SESSION['user_id'] ?? 0;
$user_prenom = $_SESSION['user_prenom'] ?? '';
$user_nom = $_SESSION['user_nom'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';
$user_telephone = $_SESSION['user_telephone'] ?? '';
$user_role = $_SESSION['user_role'] ?? 'user';

// Informations étendues du profil (simulées)
$profil_info = [
    'date_inscription' => '2023-06-15',
    'derniere_connexion' => '2024-01-20 14:30:00',
    'note_moyenne' => 4.8,
    'nb_evaluations' => 23,
    'vehicule_marque' => 'Renault',
    'vehicule_modele' => 'Clio V',
    'vehicule_couleur' => 'Bleu',
    'vehicule_places' => 4,
    'preferences_musique' => true,
    'preferences_animaux' => false,
    'preferences_fumeur' => false,
    'bio' => 'Passionné de voyages et d\'écologie, je partage mes trajets pour réduire notre impact environnemental.'
];
?>

<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-success fw-bold">
                        <i class="fas fa-user-edit me-3"></i>
                        Mon profil
                    </h1>
                    <p class="text-muted mb-0">
                        Gérez vos informations personnelles et préférences
                    </p>
                </div>
                <div class="text-end">
                    <a href="/Ecoride/index.php?page=dashboard" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row">
        <!-- Colonne gauche - Informations principales -->
        <div class="col-lg-8 mb-4">
            <!-- Informations personnelles -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user me-2 text-primary"></i>
                        Informations personnelles
                    </h5>
                </div>
                <div class="card-body">
                    <form action="/Ecoride/index.php?page=profil-traitement" method="POST" class="needs-validation" novalidate>
                        <div class="row">
                            <!-- Prénom -->
                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="form-label fw-semibold">
                                    <i class="fas fa-user me-2 text-success"></i>Prénom *
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="prenom" 
                                       name="prenom" 
                                       value="<?= htmlspecialchars($user_prenom) ?>"
                                       required>
                                <div class="invalid-feedback">
                                    Veuillez saisir votre prénom.
                                </div>
                            </div>

                            <!-- Nom -->
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label fw-semibold">
                                    <i class="fas fa-user me-2 text-success"></i>Nom *
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nom" 
                                       name="nom" 
                                       value="<?= htmlspecialchars($user_nom) ?>"
                                       required>
                                <div class="invalid-feedback">
                                    Veuillez saisir votre nom.
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-2 text-success"></i>Adresse email *
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars($user_email) ?>"
                                   required>
                            <div class="invalid-feedback">
                                Veuillez saisir une adresse email valide.
                            </div>
                        </div>

                        <!-- Téléphone -->
                        <div class="mb-3">
                            <label for="telephone" class="form-label fw-semibold">
                                <i class="fas fa-phone me-2 text-success"></i>Téléphone
                            </label>
                            <input type="tel" 
                                   class="form-control" 
                                   id="telephone" 
                                   name="telephone" 
                                   value="<?= htmlspecialchars($user_telephone) ?>"
                                   placeholder="06 12 34 56 78">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Optionnel - permet aux autres utilisateurs de vous contacter
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="mb-4">
                            <label for="bio" class="form-label fw-semibold">
                                <i class="fas fa-pen me-2 text-success"></i>À propos de moi
                            </label>
                            <textarea class="form-control" 
                                      id="bio" 
                                      name="bio" 
                                      rows="4" 
                                      placeholder="Présentez-vous en quelques mots..."><?= htmlspecialchars($profil_info['bio']) ?></textarea>
                            <div class="form-text">
                                Maximum 500 caractères
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Véhicule -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-car me-2 text-primary"></i>
                        Mon véhicule
                    </h5>
                </div>
                <div class="card-body">
                    <form action="/Ecoride/index.php?page=vehicule-traitement" method="POST" class="needs-validation" novalidate>
                        <div class="row">
                            <!-- Marque -->
                            <div class="col-md-6 mb-3">
                                <label for="vehicule_marque" class="form-label fw-semibold">
                                    <i class="fas fa-car me-2 text-info"></i>Marque
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="vehicule_marque" 
                                       name="vehicule_marque" 
                                       value="<?= htmlspecialchars($profil_info['vehicule_marque']) ?>"
                                       placeholder="ex: Renault">
                            </div>

                            <!-- Modèle -->
                            <div class="col-md-6 mb-3">
                                <label for="vehicule_modele" class="form-label fw-semibold">
                                    <i class="fas fa-car me-2 text-info"></i>Modèle
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="vehicule_modele" 
                                       name="vehicule_modele" 
                                       value="<?= htmlspecialchars($profil_info['vehicule_modele']) ?>"
                                       placeholder="ex: Clio V">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Couleur -->
                            <div class="col-md-6 mb-3">
                                <label for="vehicule_couleur" class="form-label fw-semibold">
                                    <i class="fas fa-palette me-2 text-info"></i>Couleur
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="vehicule_couleur" 
                                       name="vehicule_couleur" 
                                       value="<?= htmlspecialchars($profil_info['vehicule_couleur']) ?>"
                                       placeholder="ex: Bleu">
                            </div>

                            <!-- Nombre de places -->
                            <div class="col-md-6 mb-3">
                                <label for="vehicule_places" class="form-label fw-semibold">
                                    <i class="fas fa-users me-2 text-info"></i>Nombre de places
                                </label>
                                <select class="form-select" id="vehicule_places" name="vehicule_places">
                                    <option value="">Sélectionner...</option>
                                    <?php for ($i = 2; $i <= 8; $i++): ?>
                                        <option value="<?= $i ?>" <?= $profil_info['vehicule_places'] == $i ? 'selected' : '' ?>>
                                            <?= $i ?> places
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-car me-2"></i>Mettre à jour le véhicule
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Préférences -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-cog me-2 text-primary"></i>
                        Préférences de voyage
                    </h5>
                </div>
                <div class="card-body">
                    <form action="/Ecoride/index.php?page=preferences-traitement" method="POST">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="preferences_musique" 
                                           name="preferences_musique" 
                                           <?= $profil_info['preferences_musique'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="preferences_musique">
                                        <i class="fas fa-music me-2 text-warning"></i>
                                        Musique autorisée
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="preferences_animaux" 
                                           name="preferences_animaux" 
                                           <?= $profil_info['preferences_animaux'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="preferences_animaux">
                                        <i class="fas fa-paw me-2 text-info"></i>
                                        Animaux acceptés
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="preferences_fumeur" 
                                           name="preferences_fumeur" 
                                           <?= $profil_info['preferences_fumeur'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="preferences_fumeur">
                                        <i class="fas fa-smoking me-2 text-danger"></i>
                                        Fumeur autorisé
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Sauvegarder les préférences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Colonne droite - Informations complémentaires -->
        <div class="col-lg-4">
            <!-- Carte profil -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="avatar bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <i class="fas fa-user fa-3x"></i>
                    </div>
                    <h4 class="fw-bold mb-1"><?= htmlspecialchars($user_prenom . ' ' . $user_nom) ?></h4>
                    <p class="text-muted mb-3"><?= htmlspecialchars($user_email) ?></p>
                    
                    <!-- Évaluations -->
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="fw-bold text-warning fs-4">
                                <i class="fas fa-star me-1"></i>
                                <?= $profil_info['note_moyenne'] ?>/5
                            </div>
                            <small class="text-muted">Note moyenne</small>
                        </div>
                        <div class="col-6">
                            <div class="fw-bold text-primary fs-4">
                                <?= $profil_info['nb_evaluations'] ?>
                            </div>
                            <small class="text-muted">Évaluations</small>
                        </div>
                    </div>

                    <!-- Membre depuis -->
                    <div class="bg-light p-3 rounded mb-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Membre depuis le <?= date('d/m/Y', strtotime($profil_info['date_inscription'])) ?>
                        </small>
                    </div>

                    <!-- Dernière connexion -->
                    <div class="text-muted small">
                        <i class="fas fa-clock me-2"></i>
                        Dernière connexion : <?= date('d/m/Y à H:i', strtotime($profil_info['derniere_connexion'])) ?>
                    </div>
                </div>
            </div>

            <!-- Sécurité -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-shield-alt me-2 text-danger"></i>
                        Sécurité
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/Ecoride/index.php?page=changer-mot-de-passe" class="btn btn-outline-warning">
                            <i class="fas fa-key me-2"></i>Changer le mot de passe
                        </a>
                        <a href="/Ecoride/index.php?page=supprimer-compte" class="btn btn-outline-danger">
                            <i class="fas fa-trash me-2"></i>Supprimer le compte
                        </a>
                    </div>
                </div>
            </div>

            <!-- Véhicule actuel -->
            <?php if (!empty($profil_info['vehicule_marque'])): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-car me-2 text-info"></i>
                            Mon véhicule
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div class="vehicle-icon bg-info text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-car fa-2x"></i>
                            </div>
                            <h6 class="fw-bold">
                                <?= htmlspecialchars($profil_info['vehicule_marque']) ?>
                                <?= htmlspecialchars($profil_info['vehicule_modele']) ?>
                            </h6>
                            <p class="text-muted mb-2">
                                <?= htmlspecialchars($profil_info['vehicule_couleur']) ?>
                            </p>
                            <div class="badge bg-info">
                                <?= $profil_info['vehicule_places'] ?> places
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Validation Bootstrap
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Compteur de caractères pour la bio
document.getElementById('bio').addEventListener('input', function() {
    const maxLength = 500;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;
    
    // Afficher le compteur (à implémenter si nécessaire)
    console.log(`Caractères restants: ${remaining}`);
});
</script>

<style>
.card {
    border-radius: 15px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.form-control, .form-select {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    transform: translateY(-1px);
}

.btn {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.avatar, .vehicle-icon {
    transition: all 0.3s ease;
}

.avatar:hover, .vehicle-icon:hover {
    transform: scale(1.05);
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.form-switch .form-check-input {
    border-radius: 20px;
}

.badge {
    border-radius: 20px;
    padding: 8px 15px;
}

@media (max-width: 768px) {
    .avatar {
        width: 80px !important;
        height: 80px !important;
    }
    
    .vehicle-icon {
        width: 60px !important;
        height: 60px !important;
    }
}
</style>