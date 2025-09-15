// src/public/js/main.js

document.addEventListener('DOMContentLoaded', () => {

    const form = document.querySelector('form');

    if (form) {
        form.addEventListener('submit', (event) => {
            // Empêche le formulaire d'être soumis
            event.preventDefault();

            // Affiche une alerte de confirmation
            alert('Votre message a été envoyé ! Un développeur interviendra plus tard pour intégrer une véritable logique d\'envoi.');

            // Pour une vraie application, on enverrait les données au serveur ici
            // form.submit();
        });
    }

});