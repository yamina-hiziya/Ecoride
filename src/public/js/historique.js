/**
 * Gestionnaire de l'historique des trajets
 */
class HistoriqueManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupFiltres();
        this.setupExport();
        this.setupAnimations();
        this.setupSearch();
        
        console.log('📊 Historique manager initialisé');
    }

    /**
     * Gestion des filtres
     */
    setupFiltres() {
        const form = document.getElementById('filtresForm');
        const inputs = form.querySelectorAll('select, input');
        
        // Auto-soumission lors du changement
        inputs.forEach(input => {
            input.addEventListener('change', () => {
                if (input.name !== 'recherche') {
                    this.submitFiltres();
                }
            });
        });
        
        // Sauvegarde des filtres dans localStorage
        this.saveFilters();
        this.loadFilters();
    }

    /**
     * Recherche avec délai
     */
    setupSearch() {
        const searchInput = document.querySelector('input[name="recherche"]');
        let searchTimeout;
        
        if (searchInput) {
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.submitFiltres();
                }, 500); // Délai de 500ms
            });
        }
    }

    /**
     * Soumettre les filtres
     */
    submitFiltres() {
        const form = document.getElementById('filtresForm');
        
        // Animation de chargement
        this.showLoading();
        
        // Sauvegarder les filtres
        this.saveFilters();
        
        // Soumettre le formulaire
        form.submit();
    }

    /**
     * Sauvegarder les filtres
     */
    saveFilters() {
        const form = document.getElementById('filtresForm');
        const formData = new FormData(form);
        const filters = {};
        
        for (let [key, value] of formData.entries()) {
            if (key !== 'page') {
                filters[key] = value;
            }
        }
        
        localStorage.setItem('ecoride_historique_filters', JSON.stringify(filters));
    }

    /**
     * Charger les filtres sauvegardés
     */
    loadFilters() {
        const savedFilters = localStorage.getItem('ecoride_historique_filters');
        
        if (savedFilters) {
            const filters = JSON.parse(savedFilters);
            const form = document.getElementById('filtresForm');
            
            Object.keys(filters).forEach(key => {
                const input = form.querySelector(`[name="${key}"]`);
                if (input && !input.value) {
                    input.value = filters[key];
                }
            });
        }
    }

    /**
     * Export des données
     */
    setupExport() {
        const exportBtn = document.getElementById('exportBtn');
        
        if (exportBtn) {
            exportBtn.addEventListener('click', () => {
                this.exportData();
            });
        }
    }

    /**
     * Exporter les données en CSV
     */
    exportData() {
        const trajets = document.querySelectorAll('.trajet-card');
        let csvContent = "Date,Départ,Arrivée,Type,Statut,Prix,Places\n";
        
        trajets.forEach(trajet => {
            const route = trajet.querySelector('.trajet-route h5').textContent.replace(' → ', ',');
            const date = trajet.querySelector('.detail-item .detail-icon:contains("📅")').nextSibling.textContent;
            const type = trajet.querySelector('.badge-propose, .badge-reserve').textContent;
            const statut = trajet.querySelector('[class*="badge-statut"]').textContent;
            const prix = trajet.querySelector('.prix').textContent;
            const places = trajet.querySelector('.detail-item:contains("places")').textContent;
            
            csvContent += `"${date}","${route}","${type}","${statut}","${prix}","${places}"\n`;
        });
        
        // Télécharger le fichier
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', `ecoride_historique_${new Date().toISOString().slice(0,10)}.csv`);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Notification
        this.showNotification('📊 Export réussi !', 'success');
    }

    /**
     * Animations
     */
    setupAnimations() {
        // Observer pour l'animation des cards
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationDelay = 
                        Array.from(entry.target.parentNode.children).indexOf(entry.target) * 100 + 'ms';
                    entry.target.classList.add('animate');
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.trajet-card').forEach(card => {
            observer.observe(card);
        });
    }

    /**
     * Affichage du loading
     */
    showLoading() {
        const resultsSection = document.querySelector('.resultats-section');
        
        if (resultsSection) {
            resultsSection.style.opacity = '0.5';
            resultsSection.style.pointerEvents = 'none';
            
            // Ajouter un spinner
            if (!document.querySelector('.loading-spinner')) {
                const spinner = document.createElement('div');
                spinner.className = 'loading-spinner';
                spinner.innerHTML = `
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <span class="ms-2">Filtrage en cours...</span>
                    </div>
                `;
                resultsSection.appendChild(spinner);
            }
        }
    }

    /**
     * Notifications toast
     */
    showNotification(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        // Conteneur toast
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }
        
        container.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }
}

/**
 * Utilitaires
 */
class HistoriqueUtils {
    static formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    static calculateDaysAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 1) return 'hier';
        if (diffDays < 7) return `il y a ${diffDays} jours`;
        if (diffDays < 30) return `il y a ${Math.floor(diffDays / 7)} semaines`;
        return `il y a ${Math.floor(diffDays / 30)} mois`;
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    new HistoriqueManager();
    
    // Ajout des infos "il y a X jours" sur les dates
    document.querySelectorAll('.detail-item:contains("📅")').forEach(item => {
        const dateText = item.textContent;
        const dateMatch = dateText.match(/\d{2}\/\d{2}\/\d{4}/);
        
        if (dateMatch) {
            const daysAgo = HistoriqueUtils.calculateDaysAgo(dateMatch[0]);
            item.innerHTML += ` <small class="text-muted">(${daysAgo})</small>`;
        }
    });
});