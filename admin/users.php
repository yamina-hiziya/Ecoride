<?php
// Fichier : /Applications/XAMPP/xamppfiles/htdocs/Ecoride/admin/users.php

session_start();

if (!isset($_SESSION["is_logged_in"]) || !$_SESSION["is_logged_in"] || !in_array($_SESSION["user_role"] ?? "", ["admin", "employe"])) {
    header("Location: ../index.php?page=connexion");
    exit;
}

require_once "../src/Classes/Database.php";

try {
    $db = Database::getInstance();
    $stmt = $db->query("SELECT id, nom, prenom, email, role, credits_ecologiques, date_creation FROM utilisateurs ORDER BY role DESC, id ASC");
    $users = $stmt->fetchAll();
} catch (Exception $e) {
    $users = [];
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👥 Gestion Utilisateurs - EcoRide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand" href="index.php">👨‍💼 Administration</a>
            <div>
                <a href="../index.php" class="btn btn-outline-light">🏠 Site</a>
                <a href="../index.php?page=deconnexion" class="btn btn-light">🚪 Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>👥 Gestion des Utilisateurs</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">❌ Erreur : <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Crédits</th>
                            <th>Actions</th>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <?php
                            $role_badge = match($user['role']) {
                                'admin' => '<span class="badge bg-danger">👨‍💼 Admin</span>',
                                'employe' => '<span class="badge bg-primary">👔 Employé</span>',
                                default => '<span class="badge bg-secondary">👤 User</span>'
                            };
                            ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['nom'] . ' ' . $user['prenom']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= $role_badge ?></td>
                                <td><?= $user['credits_ecologiques'] ?? 0 ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info">👁️ Voir</button>
                                    <?php if ($_SESSION["user_role"] === 'admin'): ?>
                                        <button class="btn btn-sm btn-warning">✏️ Éditer</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>