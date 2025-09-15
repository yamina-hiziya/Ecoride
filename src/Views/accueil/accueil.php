<?php
require_once ROOT_PATH . '/Classes/Covoiturage.php';
$covoiturageManager = new Covoiturage();
$trajets = $covoiturageManager->getPublicTrajets();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecoride - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/Ecoride/src/public/css/style.css">
</head>

<body>

    <?php include ROOT_PATH . '/includes/nav.php'; ?>

    <div class="video-banner">
        <video autoplay muted loop class="video-bg">
            <source src="/Ecoride/src/public/videos/covoiturages-videos.mp4" type="video/mp4">
            Votre navigateur ne supporte pas la balise vidéo.
        </video>
        <div class="video-overlay">
            <h1>Bienvenue sur Ecoride</h1>
            <p>La startup "EcoRide" fraichement crée en France, a pour objectif de réduire l'impact environnemental des déplacements en encourageant le covoiturage. EcoRide prône une approche écologique et souhaite se faire connaître au travers d’un projet porté par José, le directeur technique, d’une application web.</p>
        </div>
    </div>

    <main class="container mt-5">
        <div class="row justify-content-center my-4">
            <div class="col-md-8">
                <form>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Entrez une ville de départ">
                        <input type="text" class="form-control" placeholder="Entrez une ville d'arrivée">
                        <button class="btn btn-success" type="submit">Rechercher</button>
                    </div>
                </form>
            </div>
        </div>
        <h2>Derniers covoiturages publiés</h2>
        <div class="row">
    <?php if (empty($trajets)): ?>
        <p class="text-center">Aucun covoiturage n'est encore disponible.</p>
    <?php else: ?>
        <?php foreach ($trajets as $trajet): ?>
            <div class="col-md-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($trajet->lieu_depart) ?> → <?= htmlspecialchars($trajet->lieu_arrivee) ?></h5>
            <p class="card-text">
                <strong>Prix par personne :</strong> <?= htmlspecialchars($trajet->prix_par_personne) ?> €
            </p>
            <a href="index.php?page=covoiturage-detail&id=<?= $trajet->id ?>" class="btn btn-primary">Voir les détails</a>
        </div>
    </div>
</div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

        <section class="row my-5">
            <div class="col-md-6">
                <h2>Notre mission</h2>
                <p>Réduire l'impact environnemental des déplacements en encourageant le covoiturage.</p>
            </div>
            <div class="col-md-6">
                <img src="/Ecoride/src/public/images/pexels-tima-miroshnichenko-5439477.jpg" alt="Covoiturage écologique" class="img-fluid rounded">
            </div>
        </section>
        <section class="row my-5">
            <div class="col-md-6">
                <h2>Notre mission</h2>
                <p>L’ambition "EcoRide" est de devenir la principale plateforme de covoiturage pour les voyageurs soucieux de l'environnement et ceux qui recherchent une solution économique pour leurs déplacements. Il est important à souligner que la plateforme de covoiturage doit gérer uniquement les déplacements en voitures.</p>
            </div>

            <div class="col-md-6">
                <img src="/Ecoride/src/public/images/pexels-tima-miroshnichenko-5439477.jpg" alt="Covoiturage écologique" class="img-fluid rounded">
            </div>
        </section>

    </main>

    <?php include ROOT_PATH . '/includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>