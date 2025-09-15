<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Tableau de bord - Ecoride</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
</head>

<body>

    <?php include ROOT_PATH . '/includes/nav.php'; ?>

    <main class="container mt-5">
        <h1 class="text-center">Bienvenue sur votre tableau de bord</h1>
        <p class="text-center lead">Ici, vous pourrez gérer vos informations et vos covoiturages.</p>

        <div class="row my-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mes informations</h5>
                        <p class="card-text">Gérez vos informations de profil, vos préférences et votre véhicule.</p>
                        <a href="#" class="btn btn-primary">Gérer mon profil</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mes covoiturages</h5>
                        <p class="card-text">Consultez vos covoiturages passés et à venir.</p>
                        <a href="index.php?page=mes-covoiturages" class="btn btn-primary">Voir mes trajets</a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <h2>Vous n'avez pas de covoiturage en cours</h2>
                <p class="lead">Proposez votre premier covoiturage pour commencer à rouler avec Ecoride.</p>
                <a href="index.php?page=proposer-covoiturage" class="btn btn-primary mt-3">Proposer un nouveau covoiturage</a>
            </div>
        </div>
    </main>

    <?php include ROOT_PATH . '/includes/footer.php'; ?>

</body>

</html>