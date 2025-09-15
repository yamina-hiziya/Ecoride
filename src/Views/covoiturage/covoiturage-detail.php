<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Détail du covoiturage - Ecoride</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
</head>

<body>
    <?php include ROOT_PATH . '/includes/nav.php'; ?>
    <main class="container mt-5">
        <a href="index.php?page=covoiturage" class="btn btn-outline-secondary mb-4">← Retour</a>
        <h1 class="text-center">Détail du covoiturage</h1>

        <div class="card my-4">
            <div class="card-body">
                <h5 class="card-title">Trajet : Paris → Lyon</h5>
                <p class="card-text">
                    <strong>Date et heure :</strong> 15/08/2025 à 10:00<br>
                    <strong>Prix :</strong> 25€<br>
                    <strong>Places disponibles :</strong> 2<br>
                    <strong>Aspect écologique :</strong> OUI (véhicule électrique)<br>
                    <strong>Véhicule :</strong> Renault Zoé (Marque et modèle)
                </p>
                <hr>
                <h4>Informations sur le conducteur</h4>
                <p>
                    <strong>Nom :</strong> Jean Dupont<br>
                    <strong>Pseudo :</strong> JeanD<br>
                    <strong>Préférences :</strong> Non-fumeur, accepte les animaux.
                </p>
                <a href="#" class="btn btn-primary mt-3">Participer à ce covoiturage</a>
            </div>
        </div>
    </main>
    <?php include ROOT_PATH . '/includes/footer.php'; ?>
</body>

</html>