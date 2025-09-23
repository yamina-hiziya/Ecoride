/**
 * Dashboard EcoRide - Interactions JavaScript
 * Gestion de la navigation, préférences et interactions utilisateur
 */

class DashboardManager {
    constructor() {
        this.currentSection = 'dashboard';
        this.init();
    }

    init() {
        this.setupNavigation();
        this.setupPreferences();
        this.setupRangeSlider();
        this.setupEcoStats();
        
        console.log('🚀 Dashboard EcoRide initialisé');
    }

    /**
     * Navigation entre les sections
     */
    setupNavigation() {
        const navLinks = document.querySelectorAll('[data-section]');
        const sections = document.querySelectorAll('.dashboard-section');

        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                
                const targetSection = link.getAttribute('data-section');
                
                // Mettre à jour les liens actifs
                navLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                
                // Masquer toutes les sections
                sections.forEach(section => {
                    section.classList.remove('active');
                });
                
                // Afficher la section cible
                const target = document.getElementById(`section-${targetSection}`);
                if (target) {
                    target.classList.add('active');
                    this.currentSection = targetSection;
                    
                    // Animation d'entrée
                    target.style.opacity = '0';
                    target.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        target.style.transition = 'all 0.3s ease';
                        target.style.opacity = '1';
                        target.style.transform = 'translateY(0)';
                    }, 50);
                }
                
                console.log(`📱 Navigation vers: ${targetSection}`);
            });
        });
    }

    /**
     * Gestion des préférences de véhicule
     */
    setupPreferences() {
        const vehicleOptions = document.querySelectorAll('.vehicle-option');
        
        vehicleOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Retirer la classe active de toutes les options
                vehicleOptions.forEach(opt => opt.classList.remove('active'));
                
                // Ajouter la classe active à l'option sélectionnée
                option.classList.add('active');
                
                const vehicleType = option.getAttribute('data-type');
                
                // Effet visuel de confirmation
                option.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    option.style.transform = 'scale(1)';
                }, 200);
                
                // Sauvegarder la préférence (localStorage pour demo)
                localStorage.setItem('eco_vehicle_preference', vehicleType);
                
                console.log(`🚗 Préférence véhicule: ${vehicleType}`);
                
                // Notification toast
                this.showToast(`Préférence sauvegardée: ${this.getVehicleLabel(vehicleType)}`, 'success');
            });
        });
        
        // Charger la préférence sauvegardée
        const savedPreference = localStorage.getItem('eco_vehicle_preference');
        if (savedPreference) {
            const savedOption = document.querySelector(`[data-type="${savedPreference}"]`);
            if (savedOption) {
                vehicleOptions.forEach(opt => opt.classList.remove('active'));
                savedOption.classList.add('active');
            }
        }
    }

    /**
     * Gestion du slider de note minimale
     */
    setupRangeSlider() {
        const noteSlider = document.getElementById('noteMin');
        const noteValue = document.getElementById('noteValue');
        
        if (noteSlider && noteValue) {
            noteSlider.addEventListener('input', () => {
                const value = parseFloat(noteSlider.value);
                noteValue.textContent = `${value.toFixed(1)}⭐`;
                
                // Couleur dynamique basée sur la valeur
                if (value >= 4.5) {
                    noteValue.style.color = '#28a745'; // Vert
                } else if (value >= 4.0) {
                    noteValue.style.color = '#52734d'; // Vert foncé
                } else if (value >= 3.5) {
                    noteValue.style.color = '#ffc107'; // Jaune
                } else {
                    noteValue.style.color = '#dc3545'; // Rouge
                }
                
                // Sauvegarder la préférence
                localStorage.setItem('eco_min_rating', value);
                
                console.log(`⭐ Note minimale: ${value}`);
            });
            
            // Charger la préférence sauvegardée
            const savedRating = localStorage.getItem('eco_min_rating');
            if (savedRating) {
                noteSlider.value = savedRating;
                noteValue.textContent = `${parseFloat(savedRating).toFixed(1)}⭐`;
            }
        }
    }

    /**
     * Animations des statistiques écologiques
     */
    setupEcoStats() {
        const statCards = document.querySelectorAll('.stat-card-dashboard');
        
        // Animation au chargement
        statCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Effet hover amélioré
        statCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-8px) scale(1.02)';
                card.style.boxShadow = '0 15px 40px rgba(45, 80, 22, 0.2)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
                card.style.boxShadow = '0 4px 15px rgba(45, 80, 22, 0.1)';
            });
        });
        
        // Animation des chiffres (compte progressif)
        this.animateNumbers();
    }

    /**
     * Animation progressive des chiffres
     */
    animateNumbers() {
        const numbers = document.querySelectorAll('.stat-details h3');
        
        numbers.forEach(numberElement => {
            const text = numberElement.textContent;
            const match = text.match(/(\d+)/);
            
            if (match) {
                const finalNumber = parseInt(match[1]);
                const suffix = text.replace(match[1], '');
                
                let currentNumber = 0;
                const increment = Math.ceil(finalNumber / 30);
                
                const timer = setInterval(() => {
                    currentNumber += increment;
                    
                    if (currentNumber >= finalNumber) {
                        currentNumber = finalNumber;
                        clearInterval(timer);
                    }
                    
                    numberElement.textContent = currentNumber + suffix;
                }, 50);
            }
        });
    }

    /**
     * Système de notifications toast
     */
    showToast(message, type = 'info') {
        // Créer le conteneur toast s'il n'existe pas
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        // Créer le toast
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'primary'} border-0`;
        toast.setAttribute('role', 'alert');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${type === 'success' ? '✅' : 'ℹ️'} ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        // Afficher le toast
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Supprimer après fermeture
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    /**
     * Utilitaires
     */
    getVehicleLabel(type) {
        const labels = {
            'electrique': '⚡ Électrique',
            'hybride': '🔋 Hybride',
            'essence': '⛽ Essence'
        };
        return labels[type] || type;
    }

    /**
     * Mise à jour des données en temps réel (simulation)
     */
    updateLiveData() {
        // Simulation d'une mise à jour des crédits écologiques
        const creditsElement = document.querySelector('.eco-stat h3');
        if (creditsElement) {
            const currentValue = parseInt(creditsElement.textContent);
            const newValue = currentValue + Math.floor(Math.random() * 5);
            creditsElement.textContent = `${newValue}kg`;
            
            console.log(`🌿 Mise à jour crédits: ${newValue}kg`);
        }
    }
}

/**
 * Gestion responsive
 */
class ResponsiveManager {
    constructor() {
        this.setupResponsive();
        window.addEventListener('resize', () => this.handleResize());
    }

    setupResponsive() {
        this.handleResize();
    }

    handleResize() {
        const width = window.innerWidth;
        const sidebar = document.querySelector('.sidebar');
        
        if (width < 768) {
            // Mode mobile
            if (sidebar) {
                sidebar.classList.add('mobile');
            }
            console.log('📱 Mode mobile activé');
        } else {
            // Mode desktop
            if (sidebar) {
                sidebar.classList.remove('mobile');
            }
            console.log('🖥️ Mode desktop activé');
        }
    }
}

/**
 * Initialisation
 */
document.addEventListener('DOMContentLoaded', () => {
    console.log('🚀 Initialisation du dashboard EcoRide...');
    
    const dashboard = new DashboardManager();
    const responsive = new ResponsiveManager();
    
    // Mise à jour des données toutes les 30 secondes (demo)
    setInterval(() => {
        dashboard.updateLiveData();
    }, 30000);
    
    console.log('✅ Dashboard EcoRide prêt !');
});

/**
 * Gestionnaire d'erreurs global
 */
window.addEventListener('error', (e) => {
    console.error('❌ Erreur dashboard:', e.error);
});