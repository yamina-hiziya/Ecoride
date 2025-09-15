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
        <?php
        // Affichage des messages d'erreur/succès
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success text-center" role="alert">';
            echo $_SESSION['success_message'];
            echo '</div>';
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger text-center" role="alert">';
            echo $_SESSION['error_message'];
            echo '</div>';
            unset($_SESSION['error_message']);
        }
        ?>
        
        <h1 class="text-center">Inscription</h1>
        <p class="text-center lead">Créez votre compte pour devenir un Ecorider.</p>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="index.php?page=inscription-traitement" method="post" class="mt-4 p-4 shadow-sm rounded">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom *</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom *</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pseudo" class="form-label">Pseudo *</label>
                                <input type="text" class="form-control" id="pseudo" name="pseudo" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="Optionnel">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe *</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="form-text">Minimum 6 caractères</div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </div>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">* Champs obligatoires</small>
                    </div>
                    
                    <div class="text-center mt-2">
                        <small>Déjà un compte ? <a href="index.php?page=connexion">Se connecter</a></small>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <?php include ROOT_PATH . '/includes/footer.php'; ?>

</body>

</html>