<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Ecoride/src/Views/compte/inscription.php
// Page d'inscription utilisateur

// Vérification de sécurité
if (!defined('ROOT_PATH')) {
    die('Accès non autorisé');
}

// Rediriger si déjà connecté
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']) {
    header('Location: /Ecoride/index.php?page=dashboard');
    exit;
}
?>

<div class="container-fluid vh-100">
    <div class="row h-100">
        <!-- Colonne gauche - Image/Infos -->
        <div class="col-lg-6 bg-eco-gradient d-none d-lg-flex align-items-center justify-content-center text-white">
            <div class="text-center">
                <i class="fas fa-leaf display-1 mb-4"></i>
                <h3 class="fw-bold mb-4">Bienvenue sur EcoRide !</h3>
                <p class="lead mb-4">
                    Créez votre compte gratuitement et commencez à partager vos trajets dès aujourd'hui.
                </p>
                
                <!-- Avantages -->
                <div class="text-start">
                    <div class="mb-3">
                        <i class="fas fa-check-circle me-3"></i>
                        <span>Inscription 100% gratuite</span>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-check-circle me-3"></i>
                        <span>Profil sécurisé et vérifié</span>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-check-circle me-3"></i>
                        <span>Économisez jusqu'à 70% sur vos trajets</span>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-check-circle me-3"></i>
                        <span>Contribuez à la protection de l'environnement</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite - Formulaire -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="w-100" style="max-width: 500px;">
                <div class="text-center mb-4">
                    <h1 class="h2 text-eco-primary fw-bold">
                        <i class="fas fa-leaf me-2"></i>EcoRide
                    </h1>
                    <h2 class="h4 text-muted">Créer votre compte</h2>
                    <p class="text-muted">Rejoignez la communauté du covoiturage écologique</p>
                </div>

                <!-- Formulaire d'inscription -->
                <form action="/Ecoride/index.php?page=inscription-traitement" method="POST" class="needs-validation" novalidate>
                    <!-- Token CSRF -->
                    <?php if (function_exists('csrf_token')): ?>
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <?php endif; ?>

                    <!-- Prénom et Nom -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="prenom" class="form-label fw-bold">
                                <i class="fas fa-user me-2 text-eco-primary"></i>Prénom
                            </label>
                            <input type="text" class="form-control" id="prenom" name="prenom" 
                                   placeholder="Votre prénom" required minlength="2" maxlength="50"
                                   value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
                            <div class="invalid-feedback">
                                Le prénom doit contenir entre 2 et 50 caractères.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="nom" class="form-label fw-bold">
                                <i class="fas fa-user me-2 text-eco-primary"></i>Nom
                            </label>
                            <input type="text" class="form-control" id="nom" name="nom" 
                                   placeholder="Votre nom" required minlength="2" maxlength="50"
                                   value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                            <div class="invalid-feedback">
                                Le nom doit contenir entre 2 et 50 caractères.
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">
                            <i class="fas fa-envelope me-2 text-eco-primary"></i>Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="votre@email.com" required
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        <div class="invalid-feedback">
                            Veuillez saisir une adresse email valide.
                        </div>
                        <div class="form-text">
                            Nous ne partagerons jamais votre email avec des tiers.
                        </div>
                    </div>

                    <!-- Téléphone -->
                    <div class="mb-3">
                        <label for="telephone" class="form-label fw-bold">
                            <i class="fas fa-phone me-2 text-eco-primary"></i>Téléphone
                            <small class="text-muted">(optionnel)</small>
                        </label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" 
                               placeholder="06 12 34 56 78" pattern="[0-9\s\.\-\+\(\)]+"
                               value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
                        <div class="form-text">
                            Utile pour être contacté par les autres utilisateurs.
                        </div>
                    </div>

                    <!-- Mot de passe -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-bold">
                                <i class="fas fa-lock me-2 text-eco-primary"></i>Mot de passe
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" 
                                       name="password" placeholder="••••••••" required minlength="6">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="toggleIcon1"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                Le mot de passe doit contenir au moins 6 caractères.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirm" class="form-label fw-bold">
                                <i class="fas fa-lock me-2 text-eco-primary"></i>Confirmation
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirm" 
                                       name="password_confirm" placeholder="••••••••" required minlength="6">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirm')">
                                    <i class="fas fa-eye" id="toggleIcon2"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                Les mots de passe ne correspondent pas.
                            </div>
                        </div>
                    </div>

                    <!-- Indicateur de force du mot de passe -->
                    <div class="mb-3">
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar" id="passwordStrength" role="progressbar" style="width: 0%"></div>
                        </div>
                        <small id="passwordHelp" class="form-text text-muted">
                            Utilisez au moins 6 caractères avec des lettres et des chiffres.
                        </small>
                    </div>

                    <!-- Conditions d'utilisation -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            J'accepte les 
                            <a href="/Ecoride/index.php?page=conditions" target="_blank" class="text-eco-primary">
                                conditions d'utilisation
                            </a> 
                            et la 
                            <a href="/Ecoride/index.php?page=confidentialite" target="_blank" class="text-eco-primary">
                                politique de confidentialité
                            </a>
                        </label>
                        <div class="invalid-feedback">
                            Vous devez accepter les conditions d'utilisation.
                        </div>
                    </div>

                    <!-- Newsletter -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter" checked>
                        <label class="form-check-label" for="newsletter">
                            Je souhaite recevoir les actualités et offres spéciales d'EcoRide
                        </label>
                    </div>

                    <!-- Bouton d'inscription -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-success btn-lg fw-bold">
                            <i class="fas fa-user-plus me-2"></i>Créer mon compte
                        </button>
                    </div>
                </form>

                <!-- Divider -->
                <div class="text-center my-4">
                    <span class="text-muted">ou</span>
                </div>

                <!-- Lien vers connexion -->
                <div class="text-center">
                    <p class="mb-0">Déjà un compte ?</p>
                    <a href="/Ecoride/index.php?page=connexion" class="btn btn-outline-success">
                        <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                    </a>
                </div>

                <!-- Retour à l'accueil -->
                <div class="text-center mt-4">
                    <a href="/Ecoride/index.php" class="text-muted text-decoration-none">
                        <i class="fas fa-arrow-left me-2"></i>Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Validation du formulaire
(function() {
    'use strict';
    
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            // Vérification des mots de passe
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;
            const passwordConfirmInput = document.getElementById('password_confirm');
            
            if (password !== passwordConfirm) {
                passwordConfirmInput.setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                passwordConfirmInput.setCustomValidity('');
            }
            
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
})();

// Toggle password visibility
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleIcon = document.getElementById(fieldId === 'password' ? 'toggleIcon1' : 'toggleIcon2');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.className = 'fas fa-eye-slash';
    } else {
        passwordInput.type = 'password';
        toggleIcon.className = 'fas fa-eye';
    }
}

// Vérification de la force du mot de passe
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('passwordStrength');
    const helpText = document.getElementById('passwordHelp');
    
    let strength = 0;
    let feedback = '';
    
    // Calcul de la force
    if (password.length >= 6) strength += 20;
    if (password.length >= 8) strength += 20;
    if (/[a-z]/.test(password)) strength += 20;
    if (/[A-Z]/.test(password)) strength += 20;
    if (/[0-9]/.test(password)) strength += 10;
    if (/[^A-Za-z0-9]/.test(password)) strength += 10;
    
    // Couleur et message selon la force
    if (strength < 30) {
        strengthBar.className = 'progress-bar bg-danger';
        feedback = 'Mot de passe faible';
    } else if (strength < 60) {
        strengthBar.className = 'progress-bar bg-warning';
        feedback = 'Mot de passe moyen';
    } else if (strength < 80) {
        strengthBar.className = 'progress-bar bg-info';
        feedback = 'Mot de passe bon';
    } else {
        strengthBar.className = 'progress-bar bg-success';
        feedback = 'Mot de passe excellent';
    }
    
    strengthBar.style.width = strength + '%';
    helpText.textContent = feedback;
});

// Vérification en temps réel de la correspondance des mots de passe
document.getElementById('password_confirm').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const passwordConfirm = this.value;
    
    if (passwordConfirm && password !== passwordConfirm) {
        this.setCustomValidity('Les mots de passe ne correspondent pas');
        this.classList.add('is-invalid');
    } else {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
    }
});

// Auto-focus sur le premier champ
document.addEventListener('DOMContentLoaded', function() {
    const prenomInput = document.getElementById('prenom');
    if (prenomInput) {
        prenomInput.focus();
    }
});

// Formatage automatique du numéro de téléphone
document.getElementById('telephone').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    
    if (value.length > 0) {
        if (value.length <= 10) {
            value = value.replace(/(\d{2})(?=\d)/g, '$1 ');
        }
        this.value = value;
    }
});
</script>

<style>
/* Styles spécifiques à la page d'inscription */
.form-control:focus {
    border-color: var(--eco-primary);
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn-success {
    background: var(--eco-gradient);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #1e7e34 0%, #17a2b8 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
}

.btn-outline-success:hover {
    background: var(--eco-primary);
    border-color: var(--eco-primary);
}

/* Animation d'entrée */
.container-fluid {
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Progress bar animations */
.progress-bar {
    transition: width 0.3s ease, background-color 0.3s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 2rem 1rem;
    }
    
    .row.mb-3 .col-md-6 {
        margin-bottom: 1rem;
    }
}
</style>