<?php

require_once ROOT_PATH . '/Classes/Auth.php';
require_once ROOT_PATH . '/Classes/Admin.php';

$auth = new Auth();
$auth->requireRole(1); // Admin seulement

$admin = new Admin();

// Statistiques générales
$stats = $admin->getGeneralStats();
$recentUsers = $admin->getRecentUsers(10);
$recentTrips = $admin->getRecentTrips(10);
$systemAlerts = $admin->getSystemAlerts();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - EcoRide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Ecoride/src/public/css/common-style.css">
    <link rel="stylesheet" href="/Ecoride/src/public/css/admin.css">
</head>

<body class="admin-body">
    <?php include ROOT_PATH . '/includes/admin-nav.php'; ?>

    <div class="admin-container">
        <!-- Sidebar Admin -->
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <h3>🛠️ EcoRide Admin</h3>
                <p>Panneau d'administration</p>
            </div>
            
            <nav class="admin-nav">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#dashboard" data-section="dashboard">
                            📊 Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#users" data-section="users">
                            👥 Utilisateurs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#trips" data-section="trips">
                            🚗 Trajets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#reviews" data-section="reviews">
                            ⭐ Avis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#analytics" data-section="analytics">
                            📈 Analytics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#security" data-section="security">
                            🔒 Sécurité
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#system" data-section="system">
                            ⚙️ Système
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Contenu principal -->
        <main class="admin-content">
            <!-- Header -->
            <div class="admin-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1>Bonjour <?= $_SESSION['user_prenom'] ?> 👋</h1>
                        <p class="text-muted">Tableau de bord administrateur</p>
                    </div>
                    
                    <div class="admin-actions">
                        <?php if (!empty($systemAlerts)): ?>
                            <button class="btn btn-warning position-relative" data-bs-toggle="modal" data-bs-target="#alertsModal">
                                🚨 Alertes
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= count($systemAlerts) ?>
                                </span>
                            </button>
                        <?php endif; ?>
                        
                        <button class="btn btn-outline-primary" onclick="location.reload()">
                            🔄 Actualiser
                        </button>
                    </div>
                </div>
            </div>

            <!-- Section: Dashboard -->
            <div id="section-dashboard" class="admin-section active">
                <!-- Statistiques générales -->
                <div class="row stats-cards-admin">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card-admin users">
                            <div class="stat-icon">👥</div>
                            <div class="stat-details">
                                <h3><?= number_format($stats['total_users']) ?></h3>
                                <p>Utilisateurs</p>
                                <small class="stat-change positive">+<?= $stats['new_users_today'] ?> aujourd'hui</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card-admin trips">
                            <div class="stat-icon">🚗</div>
                            <div class="stat-details">
                                <h3><?= number_format($stats['total_trips']) ?></h3>
                                <p>Trajets</p>
                                <small class="stat-change positive">+<?= $stats['new_trips_today'] ?> aujourd'hui</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card-admin revenue">
                            <div class="stat-icon">💰</div>
                            <div class="stat-details">
                                <h3><?= number_format($stats['total_revenue']) ?>€</h3>
                                <p>Revenus</p>
                                <small class="stat-change positive">+<?= $stats['revenue_today'] ?>€ aujourd'hui</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card-admin eco">
                            <div class="stat-icon">🌿</div>
                            <div class="stat-details">
                                <h3><?= number_format($stats['total_co2_saved']) ?>kg</h3>
                                <p>CO² économisé</p>
                                <small class="stat-change positive">+<?= $stats['co2_saved_today'] ?>kg aujourd'hui</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <div class="admin-card">
                            <div class="card-header">
                                <h5>📈 Évolution des inscriptions (30 derniers jours)</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="userGrowthChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-4">
                        <div class="admin-card">
                            <div class="card-header">
                                <h5>🥧 Répartition des trajets</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="tripsDistributionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activité récente -->
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="admin-card">
                            <div class="card-header">
                                <h5>👥 Nouveaux utilisateurs</h5>
                                <a href="#users" class="btn btn-sm btn-outline-primary">Voir tous</a>
                            </div>
                            <div class="card-body">
                                <div class="recent-users">
                                    <?php foreach ($recentUsers as $user): ?>
                                        <div class="user-item">
                                            <div class="user-avatar">
                                                <?= strtoupper(substr($user['prenom'], 0, 1)) ?>
                                            </div>
                                            <div class="user-info">
                                                <h6><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h6>
                                                <small class="text-muted"><?= $user['email'] ?></small>
                                                <small class="registration-date">Inscrit <?= date('d/m/Y', strtotime($user['date_inscription'])) ?></small>
                                            </div>
                                            <div class="user-status">
                                                <?php if ($user['actif']): ?>
                                                    <span class="badge bg-success">Actif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactif</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 mb-4">
                        <div class="admin-card">
                            <div class="card-header">
                                <h5>🚗 Trajets récents</h5>
                                <a href="#trips" class="btn btn-sm btn-outline-primary">Voir tous</a>
                            </div>
                            <div class="card-body">
                                <div class="recent-trips">
                                    <?php foreach ($recentTrips as $trip): ?>
                                        <div class="trip-item">
                                            <div class="trip-route">
                                                <h6><?= htmlspecialchars($trip['ville_depart']) ?> → <?= htmlspecialchars($trip['ville_arrivee']) ?></h6>
                                                <small class="text-muted">Par <?= htmlspecialchars($trip['conducteur_nom']) ?></small>
                                            </div>
                                            <div class="trip-details">
                                                <span class="trip-date"><?= date('d/m H:i', strtotime($trip['date_depart'])) ?></span>
                                                <span class="trip-price"><?= $trip['prix'] ?>€</span>
                                                <span class="badge badge-status badge-<?= $trip['statut'] ?>"><?= ucfirst($trip['statut']) ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Autres sections à développer -->
            <div id="section-users" class="admin-section">
                <h3>👥 Gestion des utilisateurs</h3>
                <p class="text-muted">Interface de gestion des utilisateurs en développement...</p>
            </div>

            <div id="section-trips" class="admin-section">
                <h3>🚗 Gestion des trajets</h3>
                <p class="text-muted">Interface de gestion des trajets en développement...</p>
            </div>

            <div id="section-security" class="admin-section">
                <h3>🔒 Sécurité</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="admin-card">
                            <div class="card-header">
                                <h5>🚨 Tentatives de connexion échouées</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="failedLoginsChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="admin-card">
                            <div class="card-header">
                                <h5>🌍 Connexions par pays</h5>
                            </div>
                            <div class="card-body">
                                <div id="securityLog">
                                    <!-- Logs de sécurité -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal des alertes -->
    <div class="modal fade" id="alertsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">🚨 Alertes système</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php foreach ($systemAlerts as $alert): ?>
                        <div class="alert alert-<?= $alert['type'] ?> d-flex align-items-center">
                            <div class="alert-icon me-3">
                                <?php
                                switch($alert['type']) {
                                    case 'danger': echo '🚨'; break;
                                    case 'warning': echo '⚠️'; break;
                                    case 'info': echo 'ℹ️'; break;
                                    default: echo '📢';
                                }
                                ?>
                            </div>
                            <div>
                                <h6><?= $alert['title'] ?></h6>
                                <p class="mb-0"><?= $alert['message'] ?></p>
                                <small class="text-muted"><?= $alert['time'] ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/Ecoride/src/public/js/admin.js"></script>
</body>
</html>