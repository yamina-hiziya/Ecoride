<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Connexion - Ecoride</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
</head>

<body>

    <?php include ROOT_PATH . '/includes/nav.php'; ?>

    <main class="container mt-5">
        <?php
        // On vérifie si un message de succès est stocké dans la session
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success text-center" role="alert">';
            echo $_SESSION['success_message'];
            echo '</div>';
            // On supprime le message de la session pour qu'il ne s'affiche qu'une fois
            unset($_SESSION['success_message']);
        }
        // On vérifie si un message d'erreur est stocké dans la session
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger text-center" role="alert">';
            echo $_SESSION['error_message'];
            echo '</div>';
            // On supprime le message de la session pour qu'il ne s'affiche qu'une fois
            unset($_SESSION['error_message']);
        }
        ?>
        <h1 class="text-center">Connexion</h1>
        <p class="text-center lead">Connectez-vous pour accéder à votre tableau de bord.</p>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="index.php?page=connexion-traitement" method="post" class="mt-4 p-4 shadow-sm rounded">
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <?php include ROOT_PATH . '/includes/footer.php'; ?>

</body>

</html>