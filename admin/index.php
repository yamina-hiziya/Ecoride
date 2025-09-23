<?php
// Créer : /Applications/XAMPP/xamppfiles/htdocs/Ecoride/admin/index.php
session_start();

// Vérification admin
if (!isset($_SESSION["is_logged_in"]) || $_SESSION["user_role"] !== 'admin') {
    header("Location: ../index.php?page=connexion");
    exit;
}

require_once "../src/Classes/Database.php";
$db = Database::getConnection(); // retourne ton PDO

// Statistiques
$stats = [];
try {
    $stmt = $db->query("SELECT COUNT(*) as total FROM utilisateurs");
    $stats["users"] = $stmt->fetch()["total"];

    $stmt = $db->query("SELECT COUNT(*) as total FROM covoiturages");
    $stats["covoiturages"] = $stmt->fetch()["total"];

    $stmt = $db->query("SELECT COUNT(*) as total FROM reservations");
    $stats["reservations"] = $stmt->fetch()["total"];

    $stmt = $db->query("SELECT role, COUNT(*) as count FROM utilisateurs GROUP BY role");
    $role_stats = $stmt->fetchAll();
} catch (Exception $e) {
    $stats = ["users" => 0, "covoiturages" => 0, "reservations" => 0];
    $role_stats = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👨‍💼 Administration EcoRide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background: linear-gradient(135deg, #dc3545, #c82333);
            min-height: 100vh;
        }
        .stat-card {
            transition: transform 0.3s;
            cursor: pointer;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar text-white p-0">
                <div class="p-3">
                    <h4>👨‍💼 Admin</h4>
                    <p class="small">EcoRide</p>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link text-white active" href="index.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a class="nav-link text-white" href="users.php">
                        <i class="fas fa-users"></i> Utilisateurs
                    </a>
                    <a class="nav-link text-white" href="covoiturages.php">
                        <i class="fas fa-car"></i> Covoiturages
                    </a>
                    <a class="nav-link text-white" href="stats.php">
                        <i class="fas fa-chart-bar"></i> Statistiques
                    </a>
                    <a class="nav-link text-white" href="support.php">
                        <i class="fas fa-headset"></i> Support
                    </a>
                    <hr class="bg-white">
                    <a class="nav-link text-white" href="../index.php">
                        <i class="fas fa-home"></i> Site Web
                    </a>
                    <a class="nav-link text-white" href="../index.php?page=deconnexion">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                </nav>
            </div>

            <!-- Contenu principal -->
            <div class="col-md-10">
                <div class="p-4">
                    <!-- En-tête -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1>📊 Dashboard Administrateur</h1>
                        <div class="text-muted">
                            Connecté en tant que : <strong><?= $_SESSION["user_name"] ?? "Admin" ?></strong>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary stat-card" onclick="location.href='users.php'">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>👥 Utilisateurs</h5>
                                            <h2><?= $stats["users"] ?></h2>
                                        </div>
                                        <i class="fas fa-users fa-3x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success stat-card" onclick="location.href='covoiturages.php'">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>🚗 Trajets</h5>
                                            <h2><?= $stats["covoiturages"] ?></h2>
                                        </div>
                                        <i class="fas fa-car fa-3x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning stat-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>📋 Réservations</h5>
                                            <h2><?= $stats["reservations"] ?></h2>
                                        </div>
                                        <i class="fas fa-calendar-check fa-3x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-info stat-card" onclick="location.href='stats.php'">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>📊 Analytics</h5>
                                            <h2>✓</h2>
                                        </div>
                                        <i class="fas fa-chart-line fa-3x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Répartition des rôles -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>👥 Répartition des Rôles</h5>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($role_stats as $role): ?>
                                        <?php
                                        $color = match($role['role']) {
                                            'admin' => 'danger',
                                            'employe' => 'primary',
                                            default => 'secondary'
                                        };
                                        $icon = match($role['role']) {
                                            'admin' => '👨‍💼',
                                            'employe' => '👔',
                                            default => '👤'
                                        };
                                        ?>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span><?= $icon ?> <?= ucfirst($role['role']) ?></span>
                                            <span class="badge bg-<?= $color ?>"><?= $role['count'] ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>⚡ Actions Rapides</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="users.php" class="btn btn-outline-primary">
                                            👥 Gérer les utilisateurs
                                        </a>
                                        <a href="covoiturages.php" class="btn btn-outline-success">
                                            🚗 Modérer les trajets
                                        </a>
                                        <a href="stats.php" class="btn btn-outline-info">
                                            📊 Voir les statistiques
                                        </a>
                                        <a href="support.php" class="btn btn-outline-warning">
                                            💬 Support client
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>