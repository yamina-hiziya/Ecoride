/**
 * Interface Employé EcoRide
 */
class EmployeManager {
    constructor() {
        this.currentSection = 'dashboard';
        this.init();
    }

    init() {
        this.setupNavigation();
        this.setupModerationActions();
        this.setupCharts();
        this.setupQuickActions();
        this.loadNotifications();
        
        console.log('💼 Interface employé initialisée');
    }

    /**
     * Navigation entre sections
     */
    setupNavigation() {
        const navLinks = document.querySelectorAll('[data-section]');
        const sections = document.querySelectorAll('.employe-section');

        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                
                const targetSection = link.getAttribute('data-section');
                
                // Mettre à jour navigation
                navLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                
                // Changer de section
                sections.forEach(section => section.classList.remove('active'));
                const target = document.getElementById(`section-${targetSection}`);
                if (target) {
                    target.classList.add('active');
                    this.currentSection = targetSection;
                    
                    // Charger les données de la section
                    this.loadSectionData(targetSection);
                }
            });
        });
    }

    /**
     * Actions de modération
     */
    setupModerationActions() {
        // Actions globales
        const approveAllBtn = document.getElementById('approveAllBtn');
        if (approveAllBtn) {
            approveAllBtn.addEventListener('click', () => this.approveAllVisible());
        }
        
        // Raccourcis clavier
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 'Enter':
                        e.preventDefault();
                        this.approveSelected();
                        break;
                    case 'Delete':
                        e.preventDefault();
                        this.rejectSelected();
                        break;
                }
            }
        });
    }

    /**
     * Graphiques de performance
     */
    setupCharts() {
        // Graphique de performance
        const performanceCtx = document.getElementById('performanceChart');
        if (performanceCtx) {
            new Chart(performanceCtx, {
                type: 'line',
                data: {
                    labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                    datasets: [{
                        label: 'Actions réalisées',
                        data: [12, 19, 15, 22, 18, 25, 8],
                        borderColor: 'rgb(45, 80, 22)',
                        backgroundColor: 'rgba(45, 80, 22, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    }

    /**
     * Actions rapides
     */
    setupQuickActions() {
        const quickActionBtn = document.getElementById('quickActionBtn');
        if (quickActionBtn) {
            quickActionBtn.addEventListener('click', () => {
                this.showQuickActionsMenu();
            });
        }
    }

    /**
     * Approuver un élément
     */
    async approuverItem(type, id) {
        try {
            const response = await fetch('/Ecoride/src/api/moderation.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': this.getCSRFToken()
                },
                body: JSON.stringify({
                    action: 'approve',
                    type: type,
                    id: id
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showNotification('✅ Élément approuvé !', 'success');
                this.removeItemFromList(type, id);
                this.updateStats();
            } else {
                this.showNotification('❌ ' + result.message, 'error');
            }
            
        } catch (error) {
            console.error('Erreur approbation:', error);
            this.showNotification('❌ Erreur lors de l\'approbation', 'error');
        }
    }

    /**
     * Rejeter un élément
     */
    async rejeterItem(type, id) {
        const raison = await this.demanderRaison();
        if (!raison) return;
        
        try {
            const response = await fetch('/Ecoride/src/api/moderation.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': this.getCSRFToken()
                },
                body: JSON.stringify({
                    action: 'reject',
                    type: type,
                    id: id,
                    raison: raison
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showNotification('❌ Élément rejeté', 'warning');
                this.removeItemFromList(type, id);
                this.updateStats();
            } else {
                this.showNotification('❌ ' + result.message, 'error');
            }
            
        } catch (error) {
            console.error('Erreur rejet:', error);
            this.showNotification('❌ Erreur lors du rejet', 'error');
        }
    }

    /**
     * Demander une raison de rejet
     */
    async demanderRaison() {
        return new Promise((resolve) => {
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Raison du rejet</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Veuillez préciser la raison du rejet :</label>
                            <textarea class="form-control" id="raisonRejet" rows="3" 
                                      placeholder="Ex: Contenu inapproprié, prix abusif, informations manquantes..."></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-danger" id="confirmerRejet">Rejeter</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
            document.getElementById('confirmerRejet').addEventListener('click', () => {
                const raison = document.getElementById('raisonRejet').value.trim();
                if (raison) {
                    resolve(raison);
                    bsModal.hide();
                } else {
                    this.showNotification('⚠️ Veuillez préciser une raison', 'warning');
                }
            });
            
            modal.addEventListener('hidden.bs.modal', () => {
                resolve(null);
                modal.remove();
            });
        });
    }

    /**
     * Supprimer un élément de la liste
     */
    removeItemFromList(type, id) {
        const item = document.querySelector(`[data-id="${id}"]`);
        if (item) {
            item.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                item.remove();
            }, 300);
        }
    }

    /**
     * Mettre à jour les statistiques
     */
    updateStats() {
        // Simulation de mise à jour des stats
        const statElements = document.querySelectorAll('.stat-details h3');
        statElements.forEach(stat => {
            const currentValue = parseInt(stat.textContent);
            if (currentValue > 0) {
                stat.textContent = currentValue - 1;
                stat.style.animation = 'pulse 0.5s ease-in-out';
            }
        });
    }

    /**
     * Menu d'actions rapides
     */
    showQuickActionsMenu() {
        const menu = document.createElement('div');
        menu.className = 'quick-actions-menu';
        menu.innerHTML = `
            <div class="quick-action-item" onclick="employeManager.approveAllVisible()">
                ✅ Tout approuver (visible)
            </div>
            <div class="quick-action-item" onclick="employeManager.markAllAsRead()">
                👁️ Marquer comme lu
            </div>
            <div class="quick-action-item" onclick="employeManager.exportModeration()">
                📊 Exporter rapport
            </div>
            <div class="quick-action-item" onclick="employeManager.showShortcuts()">
                ⌨️ Raccourcis clavier
            </div>
        `;
        
        document.body.appendChild(menu);
        
        // Positionner le menu
        const btn = document.getElementById('quickActionBtn');
        const rect = btn.getBoundingClientRect();
        menu.style.position = 'fixed';
        menu.style.top = (rect.bottom + 5) + 'px';
        menu.style.right = (window.innerWidth - rect.right) + 'px';
        
        // Fermer au clic extérieur
        setTimeout(() => {
            document.addEventListener('click', function closeMenu(e) {
                if (!menu.contains(e.target)) {
                    menu.remove();
                    document.removeEventListener('click', closeMenu);
                }
            });
        }, 100);
    }

    /**
     * Approuver tous les éléments visibles
     */
    async approveAllVisible() {
        const visibleItems = document.querySelectorAll('.moderation-card:not(.d-none)');
        
        if (visibleItems.length === 0) {
            this.showNotification('ℹ️ Aucun élément à approuver', 'info');
            return;
        }
        
        const confirmed = confirm(`Approuver ${visibleItems.length} éléments ?`);
        if (!confirmed) return;
        
        let approved = 0;
        for (const item of visibleItems) {
            const type = item.classList.contains('trajet') ? 'trajet' : 'avis';
            const id = item.getAttribute('data-id');
            
            try {
                await this.approuverItem(type, id);
                approved++;
            } catch (error) {
                console.error('Erreur approbation batch:', error);
            }
        }
        
        this.showNotification(`✅ ${approved} éléments approuvés !`, 'success');
    }

    /**
     * Charger les données d'une section
     */
    loadSectionData(section) {
        switch (section) {
            case 'moderation':
                this.refreshModerationData();
                break;
            case 'support':
                this.refreshSupportData();
                break;
            case 'reports':
                this.refreshReportsData();
                break;
        }
    }

    /**
     * Actualiser les données de modération
     */
    async refreshModerationData() {
        // Simulation - remplacez par vos appels API
        console.log('🔄 Actualisation des données de modération...');
    }

    /**
     * Notifications système
     */
    loadNotifications() {
        // Vérifier les nouvelles tâches toutes les 30 secondes
        setInterval(() => {
            this.checkNewTasks();
        }, 30000);
    }

    async checkNewTasks() {
        try {
            // Appel API pour vérifier les nouvelles tâches
            // const response = await fetch('/api/employe/check-new-tasks');
            // const data = await response.json();
            
            // Simulation
            const hasNewTasks = Math.random() < 0.1; // 10% de chance
            
            if (hasNewTasks) {
                this.showNotification('🔔 Nouvelles tâches disponibles !', 'info');
                this.updateNavigationBadges();
            }
            
        } catch (error) {
            console.error('Erreur vérification tâches:', error);
        }
    }

    /**
     * Mettre à jour les badges de navigation
     */
    updateNavigationBadges() {
        // Simulation de mise à jour des badges
        const moderationBadge = document.querySelector('[href="#moderation"] .badge');
        if (moderationBadge) {
            const current = parseInt(moderationBadge.textContent) || 0;
            moderationBadge.textContent = current + 1;
        }
    }

    /**
     * Utilitaires
     */
    getCSRFToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
}

// Fonctions globales pour les événements onclick
window.approuverItem = (type, id) => employeManager.approuverItem(type, id);
window.rejeterItem = (type, id) => employeManager.rejeterItem(type, id);
window.voirDetails = (type, id) => {
    window.open(`/Ecoride/src/Views/details.php?type=${type}&id=${id}`, '_blank');
};
window.traiterSignalement = (id) => {
    console.log(`Traitement signalement ${id}`);
};

// Initialisation
let employeManager;
document.addEventListener('DOMContentLoaded', () => {
    employeManager = new EmployeManager();
});