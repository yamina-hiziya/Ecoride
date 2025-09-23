<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Ecoride/src/includes/nav.php
// CORRECTION: Suppression de l'espace en ligne 2 qui causait l'erreur "headers already sent"
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <!-- VOTRE LOGO CONSERVÉ -->
        <a class="navbar-brand" href="/Ecoride/index.php?page=accueil">
            <img src="/Ecoride/src/public/images/logo.p.png" alt="Logo Ecoride" height="70">
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menu principal -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/Ecoride/index.php?page=accueil">
                        <i class="fas fa-home me-1"></i>Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Ecoride/index.php?page=covoiturage">
                        <i class="fas fa-car me-1"></i>Covoiturage
                    </a>
                </li>
                
                <!-- Menu pour utilisateurs connectés -->
                <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/Ecoride/index.php?page=proposer-covoiturage">
                            <i class="fas fa-plus-circle me-1"></i>Proposer un trajet
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Ecoride/index.php?page=mes-covoiturages">
                            <i class="fas fa-list me-1"></i>Mes trajets
                        </a>
                    </li>
                <?php endif; ?>
                
                <li class="nav-item">
                    <a class="nav-link" href="/Ecoride/index.php?page=contact">
                        <i class="fas fa-envelope me-1"></i>Contact
                    </a>
                </li>
            </ul>
            
            <!-- Menu utilisateur -->
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']): ?>
                    <!-- Affichage des crédits (conservé) -->
                    <li class="nav-item">
                        <span class="navbar-text me-3">
                            💚 <strong><?php echo $_SESSION['user_credits'] ?? '0'; ?></strong> crédits
                        </span>
                    </li>
                    
                    <!-- Menu déroulant utilisateur -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i>
                            <?= htmlspecialchars($_SESSION['user_prenom'] ?? 'Utilisateur') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="/Ecoride/index.php?page=dashboard">
                                    <i class="fas fa-tachometer-alt me-2"></i>Mon compte
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/Ecoride/index.php?page=profil">
                                    <i class="fas fa-user-edit me-2"></i>Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/Ecoride/index.php?page=historique">
                                    <i class="fas fa-history me-2"></i>Historique
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="/Ecoride/index.php?page=deconnexion">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                <?php else: ?>
                    <!-- Menu pour visiteurs -->
                    <li class="nav-item">
                        <a class="nav-link" href="/Ecoride/index.php?page=connexion">
                            <i class="fas fa-sign-in-alt me-1"></i>Connexion
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-success ms-2" href="/Ecoride/index.php?page=inscription">
                            <i class="fas fa-user-plus me-1"></i>S'inscrire
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>