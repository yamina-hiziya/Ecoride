<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Proposer un covoiturage - Ecoride</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
</head>

<body>

    <?php include ROOT_PATH . '/includes/nav.php'; ?>

    <main class="container mt-5">
        <a href="index.php?page=mes-covoiturages" class="btn btn-outline-secondary mb-4">← Retour à mes covoiturages</a>
        <h1 class="text-center">Proposer un covoiturage</h1>
        <p class="text-center lead">Renseignez les détails de votre trajet et publiez-le sur Ecoride.</p>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="index.php?page=proposer-covoiturage-traitement" method="post" class="p-4 shadow-sm rounded">
                    <div class="mb-3">
                        <label for="depart" class="form-label">Ville de départ</label>
                        <input type="text" class="form-control" id="depart" name="depart" required>
                    </div>
                    <div class="mb-3">
                        <label for="arrivee" class="form-label">Ville d'arrivée</label>
                        <input type="text" class="form-control" id="arrivee" name="arrivee" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date du trajet</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="heure" class="form-label">Heure de départ</label>
                        <input type="time" class="form-control" id="heure" name="heure" required>
                    </div>
                    <div class="mb-3">
                        <label for="places" class="form-label">Nombre de places disponibles</label>
                        <input type="number" class="form-control" id="places" name="places" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="prix" class="form-label">Prix par place (€)</label>
                        <input type="number" class="form-control" id="prix" name="prix" step="0.50" min="0" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Publier le covoiturage</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include ROOT_PATH . '/includes/footer.php'; ?>

</body>

</html>