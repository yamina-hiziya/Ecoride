<?php
// src/Views/compte/mes-covoiturages.php
$covoiturageManager = new Covoiturage();
$trajets = $covoiturageManager->getMyTrajets($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Mes covoiturages - Ecoride</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
</head>

<body>

    <?php include ROOT_PATH . '/includes/nav.php'; ?>

    <main class="container mt-5">
        <h1 class="text-center">Mes covoiturages</h1>
        <p class="text-center lead">Ici, vous pouvez voir et gérer les covoiturages que vous avez créés ou réservés.</p>

        <div class="d-flex justify-content-end mb-4">
            <a href="index.php?page=proposer-covoiturage" class="btn btn-primary">Proposer un nouveau covoiturage</a>
        </div>

        <div class="list-group">
            <?php if (empty($trajets)): ?>
                <p class="text-center lead">Vous n'avez pas encore proposé de covoiturage.</p>
            <?php else: ?>

        <div class="text-center my-4">
            <a href="index.php?page=proposer-covoiturage" class="btn btn-success">Proposer un nouveau covoiturage</a>
        </div>

        <div class="list-group">
            <?php foreach ($trajets as $trajet): ?>
                <a href="/Ecoride/src/index.php?page=modifier-covoiturage&id=<?= htmlspecialchars($trajet->id) ?>" class="list-group-item list-group-item-action flex-column align-items-start mb-3">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">Départ : <?= htmlspecialchars($trajet->depart) ?> -> Arrivée : <?= htmlspecialchars($trajet->arrivee) ?></h5>
                        <small><strong>Statut :</strong> Confirmé</small>
                    </div>
                    <p class="mb-1">Départ le <?= htmlspecialchars($trajet->date) ?> à <?= htmlspecialchars($trajet->heure) ?> - Places restantes : <?= htmlspecialchars($trajet->places) ?></p>
                </a>
                <div class="d-flex justify-content-end mb-3">
                    <a href="/Ecoride/src/index.php?page=modifier-covoiturage&id=<?= htmlspecialchars($trajet->id) ?>" class="btn btn-sm btn-info me-2">Modifier</a>
                    <a href="/Ecoride/src/index.php?page=supprimer-covoiturage-traitement&id=<?= htmlspecialchars($trajet->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce covoiturage ?');">Supprimer</a>
                </div>
            <?php endforeach; ?>
        </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include ROOT_PATH . '/includes/footer.php'; ?>

</body>

</html>