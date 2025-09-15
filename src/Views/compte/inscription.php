<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Inscription - Ecoride</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
</head>

<body>

    <?php include ROOT_PATH . '/includes/nav.php'; ?>

    <main class="container mt-5">
        <h1 class="text-center">Inscription</h1>
        <p class="text-center lead">Créez votre compte pour devenir un Ecorider.</p>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="index.php?page=inscription-traitement" method="post" class="mt-4 p-4 shadow-sm rounded">
                    <div class="mb-3">
                        <label for="pseudo" class="form-label">Pseudo</label>
                        <input type="text" class="form-control" id="pseudo" name="pseudo" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <?php include ROOT_PATH . '/includes/footer.php'; ?>

</body>

</html>