<?php

// Vérification d'authentification
if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header('Location: /Ecoride/index.php?page=connexion');
    exit;
}

// Inclusion de la classe avec le bon chemin
if (file_exists(ROOT_PATH . '/src/Classes/Covoiturage.php')) {
    require_once ROOT_PATH . '/src/Classes/Covoiturage.php';
    
    try {
        $covoiturageManager = new Covoiturage();
        // Récupérer les covoiturages de l'utilisateur connecté
        $mesCovoiturages = $covoiturageManager->getMesCovoiturages($_SESSION['user_id']);
        $mesReservations = $covoiturageManager->getMesReservations($_SESSION['user_id']);
    } catch (Exception $e) {
        // Données de test si méthodes non disponibles
        $mesCovoiturages = [
            [
                'id' => 1,
                'ville_depart' => 'Paris',
                'ville_arrivee' => 'Lyon',
                'date_depart' => date('Y-m-d', strtotime('+3 days')),
                'heure_depart' => '14:30:00',
                'prix' => 25.50,
                'places_disponibles' => 3,
                'places_reservees' => 1,
                'statut' => 'actif'
            ],
            [
                'id' => 2,
                'ville_depart' => 'Lyon',
                'ville_arrivee' => 'Marseille',
                'date_depart' => date('Y-m-d', strtotime('+7 days')),
                'heure_depart' => '09:15:00',
                'prix' => 18.00,
                'places_disponibles' => 2,
                'places_reservees' => 0,
                'statut' => 'actif'
            ]
        ];
        
        $mesReservations = [
            [
                'id' => 3,
                'ville_depart' => 'Bordeaux',
                'ville_arrivee' => 'Toulouse',
                'date_depart' => date('Y-m-d', strtotime('+5 days')),
                'heure_depart' => '16:45:00',
                'prix' => 12.00,
                'conducteur_nom' => 'Martin',
                'conducteur_prenom' => 'Jean',
                'statut' => 'confirme'
            ]
        ];
    }
} else {
    // Données par défaut si classe inexistante
    $mesCovoiturages = [];
    $mesReservations = [];
}
?>


    <main class="container mt-5">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="bg-success text-white p-4 rounded">
                    <h1 class="mb-2">🚗 Mes covoiturages</h1>
                    <p class="lead mb-0">Gérez vos trajets proposés et vos réservations</p>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <a href="index.php?page=proposer-covoiturage" class="btn btn-success btn-lg me-3">
                    ➕ Proposer un nouveau trajet
                </a>
                <a href="index.php?page=rechercher-covoiturage" class="btn btn-primary btn-lg">
                    🔍 Rechercher un covoiturage
                </a>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h3><?= count($mesCovoiturages) ?></h3>
                        <p class="mb-0">Trajets proposés</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <h3><?= count($mesReservations) ?></h3>
                        <p class="mb-0">Réservations</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h3><?= $_SESSION['user_credits'] ?? 0 ?></h3>
                        <p class="mb-0">Crédits éco</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center bg-warning text-dark">
                    <div class="card-body">
                        <h3><?= ($_SESSION['user_credits'] ?? 0) * 2 ?> kg</h3>
                        <p class="mb-0">CO₂ économisé</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mes trajets proposés -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">🚗 Mes trajets proposés</h4>
            </div>
            <div class="card-body">
                <?php if (empty($mesCovoiturages)): ?>
                    <div class="text-center py-4">
                        <h5 class="text-muted">🚫 Aucun trajet proposé</h5>
                        <p class="text-muted">Commencez par proposer votre premier covoiturage !</p>
                        <a href="index.php?page=proposer-covoiturage" class="btn btn-success">
                            ➕ Proposer un trajet
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($mesCovoiturages as $trajet): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card border-primary">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            🏁 <?= htmlspecialchars($trajet['ville_depart']) ?> 
                                            → 
                                            🎯 <?= htmlspecialchars($trajet['ville_arrivee']) ?>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-1"><strong>📅 Date :</strong> <?= date('d/m/Y', strtotime($trajet['date_depart'])) ?></p>
                                        <p class="mb-1"><strong>🕐 Heure :</strong> <?= date('H:i', strtotime($trajet['heure_depart'])) ?></p>
                                        <p class="mb-1"><strong>👥 Places :</strong> <?= $trajet['places_restantes'] ?> / <?= $trajet['nombre_places_disponibles'] ?></p>
                                        <p class="mb-1"><strong>💰 Prix :</strong> <?= $trajet['prix_par_place'] ?> € / personne</p>
                                        <p class="mb-1"><strong>📊 Statut :</strong> 
                                            <span class="badge bg-<?= $trajet['statut'] == 'actif' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($trajet['statut']) ?>
                                            </span>
                                        </p>
                                        <?php if (!empty($trajet['description'])): ?>
                                            <p class="mb-1"><small class="text-muted">"<?= htmlspecialchars(substr($trajet['description'], 0, 50)) ?>..."</small></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer">
                                        <div class="btn-group w-100">
                                            <a href="index.php?page=covoiturage-detail&id=<?= $trajet['id'] ?>" class="btn btn-primary btn-sm">
                                                👁️ Voir
                                            </a>
                                            <a href="index.php?page=modifier-covoiturage&id=<?= $trajet['id'] ?>" class="btn btn-warning btn-sm">
                                                ✏️ Modifier
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mes réservations -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">🎫 Mes réservations</h4>
            </div>
            <div class="card-body">
                <?php if (empty($mesReservations)): ?>
                    <div class="text-center py-4">
                        <h5 class="text-muted">🚫 Aucune réservation</h5>
                        <p class="text-muted">Recherchez un covoiturage pour faire votre première réservation !</p>
                        <a href="index.php?page=rechercher-covoiturage" class="btn btn-primary">
                            🔍 Rechercher un trajet
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($mesReservations as $reservation): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card border-info">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            🏁 <?= htmlspecialchars($reservation['ville_depart']) ?> 
                                            → 
                                            🎯 <?= htmlspecialchars($reservation['ville_arrivee']) ?>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-1"><strong>📅 Date :</strong> <?= date('d/m/Y', strtotime($reservation['date_depart'])) ?></p>
                                        <p class="mb-1"><strong>🕐 Heure :</strong> <?= date('H:i', strtotime($reservation['heure_depart'])) ?></p>
                                        <p class="mb-1"><strong>👥 Places réservées :</strong> <?= $reservation['nombre_places_reservees'] ?></p>
                                        <p class="mb-1"><strong>💰 Prix total :</strong> <?= $reservation['prix_total'] ?> €</p>
                                        <p class="mb-1"><strong>👤 Conducteur :</strong> <?= htmlspecialchars($reservation['conducteur_prenom']) ?></p>
                                        <p class="mb-1"><strong>📊 Statut :</strong> 
                                            <span class="badge bg-<?= $reservation['statut'] == 'confirmee' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($reservation['statut']) ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <a href="index.php?page=covoiturage-detail&id=<?= $reservation['covoiturage_id'] ?>" class="btn btn-primary btn-sm">
                                            👁️ Voir trajet
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Conseils -->
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="text-primary">💡 Conseils</h5>
                <ul class="mb-0">
                    <li>✅ Proposez vos trajets récurrents pour maximiser vos gains</li>
                    <li>✅ Soyez ponctuel et respectueux avec vos passagers</li>
                    <li>✅ Communiquez clairement le point de rendez-vous</li>
                    <li>✅ Partagez vos trajets pour réduire votre empreinte carbone !</li>
                </ul>
            </div>
        </div>

    </main>

    <!-- Styles CSS -->
<style>
.hero-section {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 15px;
}

.stats-rapides {
    display: flex;
    gap: 1rem;
}

.stat-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.75rem 1rem;
    border-radius: 10px;
    text-align: center;
    min-width: 80px;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: bold;
}

.stat-label {
    font-size: 0.8rem;
    opacity: 0.9;
}

.stat-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: block;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0;
}

.trajet-card,
.reservation-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.trajet-card:hover,
.reservation-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.route-cities {
    text-align: center;
    font-weight: 600;
    color: #495057;
}

.city-from,
.city-to {
    font-size: 1.1rem;
}

.route-arrow {
    color: #28a745;
    margin: 0 0.75rem;
    font-size: 1.2rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.detail-icon {
    font-size: 1.1rem;
    width: 20px;
}

.detail-text {
    font-size: 0.9rem;
    color: #6c757d;
}

.empty-state {
    background: white;
    border-radius: 15px;
    border: 2px dashed #dee2e6;
}

.empty-icon {
    font-size: 4rem;
    opacity: 0.6;
    margin-bottom: 1rem;
}

.conseil-item {
    padding: 1rem;
}

.conseil-icon {
    font-size: 2rem;
    margin-bottom: 0.75rem;
}

@media (max-width: 768px) {
    .stats-rapides {
        justify-content: center;
        margin-top: 1rem;
    }
    
    .btn-group .btn {
        font-size: 0.8rem;
    }
    
    .hero-section .text-end {
        text-align: center !important;
    }
}
</style>

<!-- JavaScript pour les actions -->
<script>
function modifierTrajet(trajetId) {
    // Redirection vers page de modification
    window.location.href = `/Ecoride/index.php?page=modifier-trajet&id=${trajetId}`;
}

function annulerTrajet(trajetId) {
    if (confirm('Êtes-vous sûr de vouloir annuler ce trajet ?')) {
        // Envoi de la requête d'annulation
        fetch(`/Ecoride/api/annuler-trajet.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ trajet_id: trajetId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de l\'annulation: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur de connexion');
        });
    }
}

function annulerReservation(reservationId) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) {
        // Envoi de la requête d'annulation
        fetch(`/Ecoride/api/annuler-reservation.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ reservation_id: reservationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de l\'annulation: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur de connexion');
        });
    }
}

function contacterConducteur(reservationId) {
    // Redirection vers page de contact
    window.location.href = `/Ecoride/index.php?page=contact-conducteur&reservation=${reservationId}`;
}
</script>