<?php
// Vue contact - Vue partielle
?>

<!-- CSS spécifique contact -->
<link rel="stylesheet" href="/Ecoride/src/public/css/contact.css">
<!-- AJOUTEZ CE STYLE DIRECTEMENT : -->
<style>
.contact-hero {
    background: linear-gradient(135deg, #1a5928 0%, #17a2b8 100%) !important;
    min-height: 400px;
    color: white !important;
}

.contact-hero h1,
.contact-hero .display-4 {
    color: #ffffff !important;
    font-size: 3.5rem !important;
    font-weight: 800 !important;
    text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.5) !important;
}

.contact-hero p,
.contact-hero .lead {
    color: #f8f9fa !important;
    font-size: 1.3rem !important;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4) !important;
}
</style>

<!-- Hero Section Contact -->
<div class="contact-hero py-5" style="background: linear-gradient(135deg, #1a5928 0%, #17a2b8 100%); color: white;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">📞 Nous contacter</h1>
                <p class="lead">
                    Une question ? Un problème ? Notre équipe EcoRide est là pour vous accompagner dans votre voyage écologique.
                </p>
            </div>
            <div class="col-lg-6 text-center">
                <div class="contact-illustration">
                    <h1 style="font-size: 5rem;">🌱📞💬</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<main class="container my-5">
    <!-- Messages de session -->
    <?php if (isset($_SESSION['contact_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Message envoyé !</strong> Nous vous répondrons dans les plus brefs délais.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['contact_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['contact_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erreur !</strong> <?= htmlspecialchars($_SESSION['contact_error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['contact_error']); ?>
    <?php endif; ?>

    <!-- Formulaire de contact -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white">
                    <h2 class="card-title mb-0">✉️ Envoyez-nous un message</h2>
                </div>
                <div class="card-body p-4">
                    <form action="index.php?page=contact-traitement" method="POST" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">
                                    <strong>Nom *</strong>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nom" 
                                       name="nom" 
                                       required 
                                       placeholder="Votre nom"
                                       value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="form-label">
                                    <strong>Prénom *</strong>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="prenom" 
                                       name="prenom" 
                                       required 
                                       placeholder="Votre prénom"
                                       value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <strong>Email *</strong>
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       required 
                                       placeholder="votre@email.com"
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telephone" class="form-label">
                                    <strong>Téléphone</strong>
                                </label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="telephone" 
                                       name="telephone" 
                                       placeholder="0123456789"
                                       value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="sujet" class="form-label">
                                <strong>Sujet *</strong>
                            </label>
                            <select class="form-select" id="sujet" name="sujet" required>
                                <option value="">Choisissez un sujet...</option>
                                <option value="question_generale" <?= (($_POST['sujet'] ?? '') == 'question_generale') ? 'selected' : '' ?>>
                                    ❓ Question générale
                                </option>
                                <option value="probleme_technique" <?= (($_POST['sujet'] ?? '') == 'probleme_technique') ? 'selected' : '' ?>>
                                    🔧 Problème technique
                                </option>
                                <option value="probleme_covoiturage" <?= (($_POST['sujet'] ?? '') == 'probleme_covoiturage') ? 'selected' : '' ?>>
                                    🚗 Problème avec un covoiturage
                                </option>
                                <option value="suggestion" <?= (($_POST['sujet'] ?? '') == 'suggestion') ? 'selected' : '' ?>>
                                    💡 Suggestion d'amélioration
                                </option>
                                <option value="signalement" <?= (($_POST['sujet'] ?? '') == 'signalement') ? 'selected' : '' ?>>
                                    🚨 Signalement
                                </option>
                                <option value="partenariat" <?= (($_POST['sujet'] ?? '') == 'partenariat') ? 'selected' : '' ?>>
                                    🤝 Partenariat
                                </option>
                                <option value="autre" <?= (($_POST['sujet'] ?? '') == 'autre') ? 'selected' : '' ?>>
                                    📋 Autre
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">
                                <strong>Message *</strong>
                            </label>
                            <textarea class="form-control" 
                                      id="message" 
                                      name="message" 
                                      rows="6" 
                                      required 
                                      placeholder="Décrivez votre demande en détail..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                            <div class="form-text">
                                Minimum 10 caractères
                            </div>
                        </div>

                        <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']): ?>
                            <div class="alert alert-info">
                                <strong>📧 Connecté en tant que :</strong> 
                                <?= htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']) ?>
                                (<?= htmlspecialchars($_SESSION['user_email']) ?>)
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="accord_donnees" 
                                       name="accord_donnees" 
                                       required>
                                <label class="form-check-label" for="accord_donnees">
                                    J'accepte que mes données soient utilisées pour traiter ma demande *
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                📨 Envoyer le message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations de contact -->
    <div class="row mt-5">
        <div class="col-lg-4 mb-4">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <div class="contact-icon mb-3">
                        <h2 class="text-success">📧</h2>
                    </div>
                    <h5 class="card-title">Email</h5>
                    <p class="card-text">
                        <a href="mailto:contact@ecoride.com" class="text-success">
                            contact@ecoride.com
                        </a>
                    </p>
                    <small class="text-muted">Réponse sous 24h</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <div class="contact-icon mb-3">
                        <h2 class="text-primary">📞</h2>
                    </div>
                    <h5 class="card-title">Téléphone</h5>
                    <p class="card-text">
                        <a href="tel:+33123456789" class="text-primary">
                            01 23 45 67 89
                        </a>
                    </p>
                    <small class="text-muted">Lun-Ven 9h-18h</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <div class="contact-icon mb-3">
                        <h2 class="text-warning">💬</h2>
                    </div>
                    <h5 class="card-title">Chat en direct</h5>
                    <p class="card-text">
                        <button class="btn btn-warning btn-sm">
                            Ouvrir le chat
                        </button>
                    </p>
                    <small class="text-muted">Lun-Ven 9h-18h</small>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ rapide -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">❓ Questions fréquentes</h3>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Comment proposer un covoiturage ?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Connectez-vous à votre compte, puis cliquez sur "Proposer un trajet" dans votre dashboard. Remplissez les informations demandées et publiez votre annonce !
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Comment fonctionnent les crédits écologiques ?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Vous gagnez des crédits en proposant des trajets et en étant un conducteur responsable. Ces crédits peuvent être utilisés pour réserver des places ou obtenir des avantages.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Que faire en cas de problème pendant un trajet ?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Contactez-nous immédiatement via le chat en direct ou le téléphone. Notre équipe est disponible pour vous aider à résoudre tout conflit.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Script de validation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const message = document.getElementById('message');
    
    // Validation du message (minimum 10 caractères)
    message.addEventListener('input', function() {
        const length = this.value.length;
        if (length < 10) {
            this.setCustomValidity('Le message doit contenir au moins 10 caractères');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Validation du formulaire
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>