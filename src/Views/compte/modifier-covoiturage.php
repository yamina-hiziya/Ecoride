<?php
// src/Views/compte/modifier-covoiturage.php
require_once ROOT_PATH . '/Classes/Covoiturage.php';

// On vérifie si un ID de trajet est présent dans l'URL
if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = "Aucun trajet sélectionné.";
    header('Location: /Ecoride/src/index.php?page=mes-covoiturages');
    exit;
}

$covoiturageManager = new Covoiturage();
$trajet = $covoiturageManager->getTrajetById($_GET['id']);

// On vérifie si le trajet existe
if (!$trajet) {
    $_SESSION['error_message'] = "Ce trajet n'existe pas.";
    header('Location: /Ecoride/src/index.php?page=mes-covoiturages');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Modifier le covoiturage - Ecoride</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Ecoride/src/public/css/style.css">
</head>
<body>

    <?php include ROOT_PATH . '/includes/nav.php'; ?>

    <main class="container mt-5">
        <a href="index.php?page=mes-covoiturages" class="btn btn-outline-secondary mb-4">← Retour à mes covoiturages</a>
        <h1 class="text-center">Modifier un covoiturage</h1>
        <p class="text-center lead">Modifiez les informations de votre trajet.</p>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="/Ecoride/src/index.php?page=modifier-covoiturage-traitement" method="post" class="p-4 shadow-sm rounded">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($trajet->id) ?>">
                    <div class="mb-3">
                        <label for="depart" class="form-label">Ville de départ</label>
                        <input type="text" class="form-control" id="depart" name="depart" value="<?= htmlspecialchars($trajet->depart) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="arrivee" class="form-label">Ville d'arrivée</label>
                        <input type="text" class="form-control" id="arrivee" name="arrivee" value="<?= htmlspecialchars($trajet->arrivee) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date du trajet</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($trajet->date) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="heure" class="form-label">Heure de départ</label>
                        <input type="time" class="form-control" id="heure" name="heure" value="<?= htmlspecialchars($trajet->heure) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="places" class="form-label">Nombre de places disponibles</label>
                        <input type="number" class="form-control" id="places" name="places" min="1" value="<?= htmlspecialchars($trajet->places) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="prix" class="form-label">Prix par place (€)</label>
                        <input type="number" class="form-control" id="prix" name="prix" step="0.50" min="0" value="<?= htmlspecialchars($trajet->prix) ?>" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include ROOT_PATH . '/includes/footer.php'; ?>
    
</body>
</html>