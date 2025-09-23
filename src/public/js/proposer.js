// <!-- JavaScript pour validation -->

// Validation du formulaire
(function() {
    'use strict';
    
    const form = document.querySelector('.needs-validation');
    
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    });
    
    // Validation en temps réel de la date
    const dateInput = document.getElementById('date');
    const heureInput = document.getElementById('heure');
    
    function validateDateTime() {
        const selectedDate = dateInput.value;
        const selectedTime = heureInput.value;
        
        if (selectedDate && selectedTime) {
            const selectedDateTime = new Date(selectedDate + 'T' + selectedTime);
            const now = new Date();
            
            if (selectedDateTime <= now) {
                dateInput.setCustomValidity('La date et l\'heure doivent être dans le futur');
                heureInput.setCustomValidity('La date et l\'heure doivent être dans le futur');
            } else {
                dateInput.setCustomValidity('');
                heureInput.setCustomValidity('');
            }
        }
    }
    
    dateInput.addEventListener('change', validateDateTime);
    heureInput.addEventListener('change', validateDateTime);
})();
