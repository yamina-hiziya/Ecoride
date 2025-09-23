<?php

// Vérification de session (évite les erreurs)
if (!isset($_SESSION)) {
    session_start();
}
?>

<!-- ATTENTION: Assurez-vous qu'il n'y a RIEN entre ?> et cette ligne -->
<!-- Footer écologique -->
<footer class="footer-eco">
    <div class="container">
        <div class="row">
            <!-- Logo et description -->
            <div class="col-lg-4 mb-4">
                <div class="footer-brand">
                    <img src="/Ecoride/src/public/images/logo-transparent-png.png" 
                         alt="EcoRide" 
                         class="footer-logo mb-3"
                         onerror="this.src='/Ecoride/src/public/images/logo.png'">
                    <h5 class="footer-title">EcoRide</h5>
                    <p class="footer-description">
                        Votre plateforme de covoiturage écologique. Ensemble, réduisons notre empreinte carbone tout en créant des liens.
                    </p>
                </div>
            </div>
            
            <!-- Liens rapides -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="footer-heading">Navigation</h6>
                <ul class="footer-links">
                    <li><a href="/Ecoride/index.php?page=accueil">Accueil</a></li>
                    <li><a href="/Ecoride/index.php?page=covoiturage">Covoiturages</a></li>
                    <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']): ?>
                        <li><a href="/Ecoride/index.php?page=dashboard">Tableau de bord</a></li>
                        <li><a href="/Ecoride/index.php?page=mes-covoiturages">Mes trajets</a></li>
                    <?php else: ?>
                        <li><a href="/Ecoride/index.php?page=connexion">Connexion</a></li>
                        <li><a href="/Ecoride/index.php?page=inscription">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <!-- Services -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="footer-heading">Services</h6>
                <ul class="footer-links">
                    <li><a href="/Ecoride/index.php?page=covoiturage">Rechercher</a></li>
                    <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']): ?>
                        <li><a href="/Ecoride/index.php?page=proposer-covoiturage">Proposer un trajet</a></li>
                    <?php endif; ?>
                    <li><a href="/Ecoride/index.php?page=contact">Contact</a></li>
                    <li><a href="#">Aide</a></li>
                </ul>
            </div>
            
            <!-- Impact écologique -->
            <div class="col-lg-4 mb-4">
                <h6 class="footer-heading">Impact Écologique</h6>
                <div class="eco-stats">
                    <div class="eco-stat-item">
                        <span class="eco-number">1,247</span>
                        <span class="eco-label">kg CO² économisés</span>
                    </div>
                    <div class="eco-stat-item">
                        <span class="eco-number">3,456</span>
                        <span class="eco-label">trajets partagés</span>
                    </div>
                    <div class="eco-stat-item">
                        <span class="eco-number">12,890</span>
                        <span class="eco-label">membres actifs</span>
                    </div>
                </div>
            </div>
        </div>
        
        <hr class="footer-divider">
        
        <!-- Bas du footer -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="footer-copyright">
                    &copy; <?= date('Y') ?> EcoRide. Tous droits réservés.
                </p>
            </div>
            <div class="col-md-6 text-end">
                <div class="footer-legal">
                    <a href="#">Mentions légales</a>
                    <a href="#">CGU</a>
                    <a href="#">Confidentialité</a>
                </div>
            </div>
        </div>
    </div>
</footer>
