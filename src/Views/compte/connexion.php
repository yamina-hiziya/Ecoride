<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Ecoride/src/Views/compte/connexion.php
// Page de connexion - Vue

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']) {
    header('Location: /Ecoride/index.php?page=dashboard');
    exit;
}
?>

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="row w-100 shadow-lg rounded-4 overflow-hidden bg-white" style="max-width: 1000px;">
        
        <!-- Colonne gauche - Formulaire -->
        <div class="col-lg-6 p-5">
            <div class="text-center mb-5">
                <h1 class="h2 fw-bold text-success mb-3">
                    <i class="fas fa-sign-in-alt me-3"></i>
                    Connexion
                </h1>
                <p class="text-muted">
                    Connectez-vous à votre compte EcoRide
                </p>
            </div>

            <!-- Messages d'erreur/succès -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- Formulaire de connexion -->
            <form action="/Ecoride/index.php?page=connexion-traitement" method="POST" class="needs-validation" novalidate>
                
                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">
                        <i class="fas fa-envelope me-2 text-success"></i>Adresse email
                    </label>
                    <input type="email" 
                           class="form-control form-control-lg" 
                           id="email" 
                           name="email" 
                           placeholder="votre@email.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           required>
                    <div class="invalid-feedback">
                        Veuillez saisir une adresse email valide.
                    </div>
                </div>

                <!-- Mot de passe -->
                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">
                        <i class="fas fa-lock me-2 text-success"></i>Mot de passe
                    </label>
                    <div class="input-group">
                        <input type="password" 
                               class="form-control form-control-lg" 
                               id="password" 
                               name="password" 
                               placeholder="Votre mot de passe"
                               required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    <div class="invalid-feedback">
                        Veuillez saisir votre mot de passe.
                    </div>
                </div>

                <!-- Se souvenir de moi -->
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Se souvenir de moi
                        </label>
                    </div>
                </div>

                <!-- Bouton de connexion -->
                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-success btn-lg fw-bold py-3">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Se connecter
                    </button>
                </div>

                <!-- Mot de passe oublié -->
                <div class="text-center mb-4">
                    <a href="/Ecoride/index.php?page=mot-de-passe-oublie" class="text-success text-decoration-none">
                        <i class="fas fa-key me-2"></i>Mot de passe oublié ?
                    </a>
                </div>

                <!-- Divider -->
                <div class="text-center mb-4">
                    <hr class="my-4">
                    <span class="bg-white px-3 text-muted">ou</span>
                </div>

                <!-- Inscription -->
                <div class="text-center">
                    <p class="mb-0">Pas encore de compte ?</p>
                    <a href="/Ecoride/index.php?page=inscription" class="btn btn-outline-success">
                        <i class="fas fa-user-plus me-2"></i>Créer un compte
                    </a>
                </div>

                <!-- Retour à l'accueil -->
                <div class="text-center mt-4">
                    <a href="/Ecoride/index.php" class="text-muted text-decoration-none">
                        <i class="fas fa-arrow-left me-2"></i>Retour à l'accueil
                    </a>
                </div>
            </form>
        </div>

        <!-- Colonne droite - Image/Infos -->
        <div class="col-lg-6 bg-eco-gradient d-none d-lg-flex align-items-center justify-content-center text-white">
            <div class="text-center">
                <i class="fas fa-users display-1 mb-4"></i>
                <h3 class="fw-bold mb-4">Rejoignez notre communauté !</h3>
                <p class="lead mb-4">
                    Plus de 1,250 utilisateurs font déjà confiance à EcoRide pour leurs trajets quotidiens.
                </p>
                
                <!-- Statistiques -->
                <div class="row text-center">
                    <div class="col-4">
                        <div class="h4 fw-bold">1,250+</div>
                        <small>Utilisateurs</small>
                    </div>
                    <div class="col-4">
                        <div class="h4 fw-bold">3,480</div>
                        <small>Trajets</small>
                    </div>
                    <div class="col-4">
                        <div class="h4 fw-bold">15.6T</div>
                        <small>CO₂ économisé</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Validation Bootstrap
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Toggle password visibility
function togglePassword() {
    const passwordField = document.getElementById('password');
    const passwordIcon = document.getElementById('password-icon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
    }
}

// Auto-focus sur le champ email
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('email').focus();
});
</script>

<style>
.bg-eco-gradient {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.form-control-lg {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control-lg:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    transform: translateY(-2px);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
}

.btn-outline-success {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-outline-success:hover {
    transform: translateY(-2px);
}

.rounded-4 {
    border-radius: 20px !important;
}

@media (max-width: 991px) {
    .container-fluid {
        padding: 1rem;
    }
}
</style>