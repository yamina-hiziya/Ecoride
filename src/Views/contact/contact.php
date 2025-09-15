<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Contact - Ecoride</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
</head>

<body>

    <?php include ROOT_PATH . '/includes/nav.php'; ?>

    <main class="container mt-5">
        <h1 class="text-center">Nous contacter</h1>
        <p class="text-center">Envoyez-nous un message et nous vous répondrons dans les plus brefs délais.</p>

        <form action="#" method="post" class="mt-4">
            <div class="mb-3">
                <label for="name" class="form-label">Nom complet</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">Sujet</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </div>
        </form>
    </main>

    <?php include ROOT_PATH . '/includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/main.js"></script>

</body>

</html>