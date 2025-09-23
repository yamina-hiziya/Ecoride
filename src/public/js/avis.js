/**
 * Gestion des avis EcoRide
 */
class AvisManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupStarRating();
        this.setupFormValidation();
        console.log('⭐ Système d\'avis initialisé');
    }

    /**
     * Gestion des étoiles cliquables
     */
    setupStarRating() {
        const starRatings = document.querySelectorAll('.star-rating');
        
        starRatings.forEach(rating => {
            const stars = rating.querySelectorAll('.star');
            const inputs = rating.querySelectorAll('input[type="radio"]');
            
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    // Mettre à jour l'input correspondant
                    inputs[index].checked = true;
                    
                    // Animation des étoiles
                    this.animateStars(rating, index + 1);
                    
                    console.log(`⭐ Note sélectionnée: ${index + 1}/5`);
                });
                
                // Effet hover
                star.addEventListener('mouseenter', () => {
                    this.highlightStars(rating, index + 1);
                });
            });
            
            // Reset hover
            rating.addEventListener('mouseleave', () => {
                const checkedInput = rating.querySelector('input:checked');
                const checkedIndex = checkedInput ? Array.from(inputs).indexOf(checkedInput) + 1 : 0;
                this.highlightStars(rating, checkedIndex);
            });
        });
    }

    /**
     * Surligner les étoiles
     */
    highlightStars(rating, count) {
        const stars = rating.querySelectorAll('.star');
        
        stars.forEach((star, index) => {
            if (index < count) {
                star.style.opacity = '1';
                star.style.transform = 'scale(1.1)';
            } else {
                star.style.opacity = '0.3';
                star.style.transform = 'scale(1)';
            }
        });
    }

    /**
     * Animation des étoiles sélectionnées
     */
    animateStars(rating, count) {
        const stars = rating.querySelectorAll('.star');
        
        stars.forEach((star, index) => {
            if (index < count) {
                star.style.opacity = '1';
                star.style.transform = 'scale(1.2)';
                
                setTimeout(() => {
                    star.style.transform = 'scale(1.1)';
                }, 150);
            } else {
                star.style.opacity = '0.3';
                star.style.transform = 'scale(1)';
            }
        });
    }

    /**
     * Validation des formulaires
     */
    setupFormValidation() {
        const forms = document.querySelectorAll('.avis-form');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                    return false;
                }
                
                // Animation de soumission
                const submitBtn = form.querySelector('button[type="submit"]');
                this.animateSubmit(submitBtn);
            });
        });
    }

    /**
     * Valider un formulaire d'avis
     */
    validateForm(form) {
        const noteInput = form.querySelector('input[name="note"]:checked');
        
        if (!noteInput) {
            this.showError('Veuillez sélectionner une note');
            return false;
        }
        
        const note = parseInt(noteInput.value);
        if (note < 1 || note > 5) {
            this.showError('La note doit être entre 1 et 5');
            return false;
        }
        
        return true;
    }

    /**
     * Animation du bouton de soumission
     */
    animateSubmit(button) {
        const originalText = button.textContent;
        button.textContent = '⏳ Envoi en cours...';
        button.disabled = true;
        
        // Simulation (le formulaire PHP prendra le relais)
        setTimeout(() => {
            button.textContent = originalText;
            button.disabled = false;
        }, 2000);
    }

    /**
     * Afficher une erreur
     */
    showError(message) {
        // Créer une alerte Bootstrap
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show';
        alert.innerHTML = `
            ❌ ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insérer en haut de la page
        const container = document.querySelector('.container');
        container.insertBefore(alert, container.firstChild);
        
        // Auto-suppression après 5 secondes
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    new AvisManager();
});