<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Ecoride/src/Views/compte/dashboard.php
// Dashboard utilisateur - Vue

// Vérifier l'authentification
if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header('Location: /Ecoride/index.php?page=connexion');
    exit;
}

// Récupérer les informations utilisateur
$user_prenom = $_SESSION['user_prenom'] ?? 'Utilisateur';
$user_nom = $_SESSION['user_nom'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';
$user_role = $_SESSION['user_role'] ?? 'user';
$user_credits = $_SESSION['user_credits'] ?? 50;

// Statistiques simulées (à remplacer par de vraies données)
$stats = [
    'trajets_proposes' => 12,
    'trajets_effectues' => 8,
    'km_parcourus' => 450,
    'co2_economise' => 68,
    'economies' => 125
];
?>

<div class="container-fluid py-4">
    <!-- En-tête du dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-success fw-bold">
                        <i class="fas fa-tachometer-alt me-3"></i>
                        Tableau de bord
                    </h1>
                    <p class="text-muted mb-0">
                        Bonjour <?= htmlspecialchars($user_prenom) ?>, voici un résumé de votre activité
                    </p>
                </div>
                <div class="text-end">
                    <div class="badge bg-success fs-6 px-3 py-2">
                        💚 <?= $user_credits ?> crédits
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages de session -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Statistiques principales -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="stat-icon text-primary mb-3">
                        <i class="fas fa-route fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-primary"><?= $stats['trajets_proposes'] ?></h4>
                    <p class="text-muted mb-0 small">Trajets proposés</p>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="stat-icon text-success mb-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-success"><?= $stats['trajets_effectues'] ?></h4>
                    <p class="text-muted mb-0 small">Trajets effectués</p>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="stat-icon text-info mb-3">
                        <i class="fas fa-road fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-info"><?= $stats['km_parcourus'] ?></h4>
                    <p class="text-muted mb-0 small">Kilomètres</p>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="stat-icon text-warning mb-3">
                        <i class="fas fa-leaf fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-warning"><?= $stats['co2_economise'] ?>kg</h4>
                    <p class="text-muted mb-0 small">CO₂ économisé</p>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="stat-icon text-danger mb-3">
                        <i class="fas fa-euro-sign fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-danger"><?= $stats['economies'] ?>€</h4>
                    <p class="text-muted mb-0 small">Économies</p>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="stat-icon text-secondary mb-3">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-secondary">4.8/5</h4>
                    <p class="text-muted mb-0 small">Note moyenne</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="/Ecoride/index.php?page=proposer-covoiturage" class="btn btn-success w-100 py-3">
                                <i class="fas fa-plus-circle mb-2 d-block fa-2x"></i>
                                <strong>Proposer un trajet</strong>
                                <small class="d-block text-white-50">Partagez votre voiture</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="/Ecoride/index.php?page=covoiturage" class="btn btn-primary w-100 py-3">
                                <i class="fas fa-search mb-2 d-block fa-2x"></i>
                                <strong>Chercher un trajet</strong>
                                <small class="d-block text-white-50">Trouvez un covoiturage</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="/Ecoride/index.php?page=mes-covoiturages" class="btn btn-info w-100 py-3">
                                <i class="fas fa-list mb-2 d-block fa-2x"></i>
                                <strong>Mes trajets</strong>
                                <small class="d-block text-white-50">Gérer mes covoiturages</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="/Ecoride/index.php?page=profil" class="btn btn-secondary w-100 py-3">
                                <i class="fas fa-user-edit mb-2 d-block fa-2x"></i>
                                <strong>Mon profil</strong>
                                <small class="d-block text-white-50">Modifier mes infos</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mes trajets récents et Notifications -->
    <div class="row">
        <!-- Trajets récents -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-clock me-2 text-primary"></i>
                        Mes trajets récents
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Trajet 1 -->
                    <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-car"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 fw-bold">Paris → Lyon</h6>
                            <p class="mb-1 text-muted small">
                                <i class="fas fa-calendar me-1"></i>Demain, 14h00
                                <span class="mx-2">•</span>
                                <i class="fas fa-users me-1"></i>2/4 places
                            </p>
                            <span class="badge bg-success">Conducteur</span>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="fw-bold text-success">25€</span>
                        </div>
                    </div>

                    <!-- Trajet 2 -->
                    <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 fw-bold">Marseille → Nice</h6>
                            <p class="mb-1 text-muted small">
                                <i class="fas fa-calendar me-1"></i>Vendredi, 9h30
                                <span class="mx-2">•</span>
                                <i class="fas fa-user me-1"></i>Passager
                            </p>
                            <span class="badge bg-primary">Réservé</span>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="fw-bold text-primary">15€</span>
                        </div>
                    </div>

                    <!-- Voir plus -->
                    <div class="text-center">
                        <a href="/Ecoride/index.php?page=mes-covoiturages" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>Voir tous mes trajets
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications et infos -->
        <div class="col-lg-4 mb-4">
            <!-- Notifications -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-bell me-2 text-warning"></i>
                        Notifications
                        <span class="badge bg-danger ms-2">3</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="notification-item d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            <div class="notification-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                <i class="fas fa-check fa-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-1 small">
                                <strong>Réservation confirmée</strong>
                            </p>
                            <p class="mb-0 text-muted small">
                                Marie a confirmé votre réservation pour Paris → Lyon
                            </p>
                        </div>
                    </div>

                    <div class="notification-item d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            <div class="notification-icon bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                <i class="fas fa-user fa-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-1 small">
                                <strong>Nouvelle demande</strong>
                            </p>
                            <p class="mb-0 text-muted small">
                                Pierre souhaite rejoindre votre trajet Lyon → Marseille
                            </p>
                        </div>
                    </div>

                    <div class="notification-item d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            <div class="notification-icon bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                <i class="fas fa-star fa-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-1 small">
                                <strong>Nouvelle évaluation</strong>
                            </p>
                            <p class="mb-0 text-muted small">
                                Sophie vous a donné 5 étoiles !
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profil -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user me-2 text-secondary"></i>
                        Mon profil
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="avatar bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($user_prenom . ' ' . $user_nom) ?></h5>
                    <p class="text-muted mb-3"><?= htmlspecialchars($user_email) ?></p>
                    
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="fw-bold text-success">4.8/5</div>
                            <small class="text-muted">Note</small>
                        </div>
                        <div class="col-6">
                            <div class="fw-bold text-primary"><?= $stats['trajets_effectues'] ?></div>
                            <small class="text-muted">Trajets</small>
                        </div>
                    </div>

                    <a href="/Ecoride/index.php?page=profil" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-edit me-2"></i>Modifier mon profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    transition: all 0.3s ease;
    border-radius: 15px;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.stat-icon {
    transition: transform 0.3s ease;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1);
}

.card {
    border-radius: 15px;
}

.btn {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.avatar {
    transition: all 0.3s ease;
}

.notification-item:hover {
    background-color: rgba(0,0,0,0.02);
    border-radius: 8px;
    padding: 8px;
    margin: -8px;
}

.notification-icon {
    transition: all 0.3s ease;
}

.notification-item:hover .notification-icon {
    transform: scale(1.1);
}

@media (max-width: 768px) {
    .col-lg-2 {
        margin-bottom: 1rem;
    }
    
    .stat-card .card-body {
        padding: 1.5rem 1rem;
    }
}
</style>