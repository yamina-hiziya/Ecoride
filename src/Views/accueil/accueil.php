<?php
// AJOUTEZ CETTE LIGNE AU TOUT DÉBUT (ligne 1 ou 2) :
require_once ROOT_PATH . '/src/Classes/Covoiturage.php';

$covoiturageManager = new Covoiturage();
$trajets = $covoiturageManager->getPublicTrajets();
?>


    <!-- Vidéo Banner SANS filtre vert -->
<div class="video-banner">
    <video autoplay muted loop class="video-bg">
        <source src="/Ecoride/src/public/videos/covoiturages-videos.mp4" type="video/mp4">
        Votre navigateur ne supporte pas la balise vidéo.
    </video>
    <div class="video-overlay-natural">
        <div class="container text-center text-white">
            <h1 class="display-3 fw-bold mb-4">Bienvenue sur EcoRide</h1>
            <p class="lead mb-4">La startup "EcoRide" fraîchement créée en France, a pour objectif de réduire l'impact environnemental des déplacements en encourageant le covoiturage.</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="index.php?page=covoiturage" class="btn btn-success btn-lg px-4">Trouver un trajet</a>
                <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']): ?>
                    <a href="index.php?page=proposer-covoiturage" class="btn btn-outline-light btn-lg px-4">Proposer un trajet</a>
                <?php else: ?>
                    <a href="index.php?page=inscription" class="btn btn-outline-light btn-lg px-4">S'inscrire</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<main class="container mt-5">
    <!-- Barre de recherche avec palette naturelle -->
    <div class="row justify-content-center my-5">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 search-card">
                <div class="card-body p-4">
                    <h3 class="text-center text-success mb-4">Recherchez votre trajet idéal</h3>
                    <form action="index.php" method="GET">
                        <input type="hidden" name="page" value="rechercher-covoiturage">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-lg eco-input" name="ville_depart" placeholder="Ville de départ" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-lg eco-input" name="ville_arrivee" placeholder="Ville d'arrivée" required>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-eco btn-lg w-100" type="submit">
                                    🔍 Rechercher
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Section des derniers covoiturages - Palette harmonieuse -->
    <div class="container my-5">
        <div class="section-eco">
            <h2 class="section-title-eco">
                Derniers covoiturages publiés
            </h2>
            
            <div class="row">
                <?php if (empty($trajets)): ?>
                    <div class="col-12">
                        <div class="empty-state-eco">
                            <h4>Aucun covoiturage disponible</h4>
                            <p>Soyez le premier à proposer un trajet écologique !</p>
                            <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']): ?>
                                <a href="index.php?page=proposer-covoiturage" class="btn-eco">Proposer un trajet</a>
                            <?php else: ?>
                                <a href="index.php?page=inscription" class="btn-eco">S'inscrire pour proposer</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($trajets as $trajet): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 trajet-card-eco">
                                <div class="card-body">
                                    
                                    <!-- TITRE DU TRAJET -->
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="trajet-title">
                                            <?= htmlspecialchars($trajet['ville_depart']) ?> → 
                                            <?= htmlspecialchars($trajet['ville_arrivee']) ?>
                                        </h5>
                                        <span class="price-badge-eco">
                                            <?= htmlspecialchars($trajet['prix_par_place']) ?> €
                                        </span>
                                    </div>
                                    
                                    <!-- CONDUCTEUR -->
                                    <p class="conducteur-info">
                                        <strong>Conducteur :</strong> <?= htmlspecialchars($trajet['prenom'] . ' ' . $trajet['nom']) ?>
                                    </p>
                                    
                                    <!-- DÉTAILS DU TRAJET -->
                                    <div class="trajet-details">
                                        <?php if (isset($trajet['date_depart'])): ?>
                                            <p class="detail-item">📅 <?= date('d/m/Y', strtotime($trajet['date_depart'])) ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($trajet['heure_depart'])): ?>
                                            <p class="detail-item">🕐 <?= date('H:i', strtotime($trajet['heure_depart'])) ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($trajet['places_restantes'])): ?>
                                            <p class="detail-item">👥 <?= $trajet['places_restantes'] ?> places disponibles</p>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($trajet['description']) && !empty($trajet['description'])): ?>
                                            <p class="description-text">
                                                "<?= htmlspecialchars(substr($trajet['description'], 0, 80)) ?><?= strlen($trajet['description']) > 80 ? '...' : '' ?>"
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <a href="index.php?page=covoiturage-detail&id=<?= $trajet['id'] ?>" 
                                       class="btn btn-outline-eco w-100">
                                        Voir les détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($trajets)): ?>
                <div class="text-center mt-4">
                    <a href="index.php?page=covoiturage" class="btn-eco-large">
                        Voir tous les trajets disponibles
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <section class="row my-5">
            <div class="col-md-6">
                <h2>Notre mission</h2>
                <p>Réduire l'impact environnemental des déplacements en encourageant le covoiturage.</p>
            </div>
            <div class="col-md-6">
                <img src="/Ecoride/src/public/images/pexels-tima-miroshnichenko-5439477.jpg" alt="Covoiturage écologique" class="img-fluid rounded">
            </div>
        </section>
        <section class="row my-5">
            <div class="col-md-6">
                <h2>Notre mission</h2>
                <p>L’ambition "EcoRide" est de devenir la principale plateforme de covoiturage pour les voyageurs soucieux de l'environnement et ceux qui recherchent une solution économique pour leurs déplacements. Il est important à souligner que la plateforme de covoiturage doit gérer uniquement les déplacements en voitures.</p>
            </div>

            <div class="col-md-6">
                <img src="/Ecoride/src/public/images/pexels-tima-miroshnichenko-5439477.jpg" alt="Covoiturage écologique" class="img-fluid rounded">
            </div>
        </section>
</main>
