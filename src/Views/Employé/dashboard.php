<?php
// Dashboard employé - Vue partielle

require_once ROOT_PATH . '/src/Classes/Auth.php';
require_once ROOT_PATH . '/src/Classes/Employe.php';

$auth = new Auth();
$auth->requireRole(2); // Employé seulement

$employe = new Employe();

// Statistiques employé
$stats = $employe->getEmployeStats();
$trajetsAModerer = $employe->getTrajetsAModerer(10);
$avisAModerer = $employe->getAvisAModerer(10);
$signalements = $employe->getSignalements(10);
?>

<!-- CSS spécifique employé -->
<link rel="stylesheet" href="/Ecoride/src/public/css/common-style.css">
<link rel="stylesheet" href="/Ecoride/src/public/css/employe.css">

<div class="employe-container">
    <!-- Header Employé -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h2 class="card-title">💼 Espace Employé - EcoRide</h2>
                    <p class="card-text mb-0">Interface de gestion et modération</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h3 class="text-warning">🔍 <?= $stats['pending_moderation'] ?? 0 ?></h3>
                    <p class="card-text">En attente de modération</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-info">
                <div class="card-body">
                    <h3 class="text-info">🎧 <?= $stats['pending_tickets'] ?? 0 ?></h3>
                    <p class="card-text">Tickets support</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h3 class="text-success">✅ <?= $stats['resolved_today'] ?? 0 ?></h3>
                    <p class="card-text">Résolus aujourd'hui</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-danger">
                <div class="card-body">
                    <h3 class="text-danger">🚨 <?= count($signalements) ?></h3>
                    <p class="card-text">Signalements</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row mb-4">
        <div class="col-12">
            <h3>⚡ Actions rapides</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">🔍 Modération</h5>
                    <p class="card-text">Modérer les trajets et avis utilisateurs</p>
                    <?php if (($stats['pending_moderation'] ?? 0) > 0): ?>
                        <span class="badge bg-warning mb-2"><?= $stats['pending_moderation'] ?> en attente</span><br>
                    <?php endif; ?>
                    <a href="index.php?page=employe-moderation" class="btn btn-warning">
                        Voir la modération
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">🎧 Support Client</h5>
                    <p class="card-text">Gérer les tickets et demandes d'aide</p>
                    <?php if (($stats['pending_tickets'] ?? 0) > 0): ?>
                        <span class="badge bg-info mb-2"><?= $stats['pending_tickets'] ?> tickets</span><br>
                    <?php endif; ?>
                    <a href="index.php?page=employe-support" class="btn btn-info">
                        Accéder au support
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Trajets à modérer -->
    <?php if (!empty($trajetsAModerer)): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">🚗 Trajets en attente de modération</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Trajet</th>
                                    <th>Conducteur</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($trajetsAModerer, 0, 5) as $trajet): ?>
                                <tr>
                                    <td><?= $trajet['id'] ?></td>
                                    <td><?= htmlspecialchars($trajet['depart']) ?> → <?= htmlspecialchars($trajet['arrivee']) ?></td>
                                    <td><?= htmlspecialchars($trajet['conducteur']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($trajet['date_creation'])) ?></td>
                                    <td>
                                        <a href="index.php?page=employe-moderation&trajet=<?= $trajet['id'] ?>" class="btn btn-sm btn-outline-primary">Voir</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (count($trajetsAModerer) > 5): ?>
                        <p class="text-muted">Et <?= count($trajetsAModerer) - 5 ?> autres trajets...</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Signalements récents -->
    <?php if (!empty($signalements)): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">🚨 Signalements récents</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Utilisateur</th>
                                    <th>Motif</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($signalements, 0, 5) as $signalement): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-danger"><?= htmlspecialchars($signalement['type']) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($signalement['utilisateur']) ?></td>
                                    <td><?= htmlspecialchars(substr($signalement['motif'], 0, 50)) ?>...</td>
                                    <td><?= date('d/m/Y H:i', strtotime($signalement['date_creation'])) ?></td>
                                    <td>
                                        <a href="index.php?page=employe-signalement&id=<?= $signalement['id'] ?>" class="btn btn-sm btn-outline-danger">Traiter</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Graphiques et statistiques -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">📊 Activité de la semaine</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>📈 Statistiques cette semaine :</strong><br>
                        • <?= $stats['trajets_week'] ?? 0 ?> nouveaux trajets<br>
                        • <?= $stats['users_week'] ?? 0 ?> nouveaux utilisateurs<br>
                        • <?= $stats['reservations_week'] ?? 0 ?> réservations
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">🔧 Actions système</h5>
                </div>
                <div class="card-body">
                    <button class="btn btn-outline-secondary btn-sm mb-2" onclick="refreshStats()">🔄 Actualiser statistiques</button><br>
                    <button class="btn btn-outline-info btn-sm mb-2" onclick="exportData()">📊 Exporter données</button><br>
                    <button class="btn btn-outline-warning btn-sm" onclick="clearCache()">🗑️ Vider cache</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshStats() {
    location.reload();
}


    alert('Fonctionnalité en développement');


function clearCache() {
    if (confirm('Vider le cache système ?')) {
        alert('Cache vidé (simulation)');
    }
}
</script>