// Fonction pour gérer le tirage de cartes
function drawCards() {
    const button = document.getElementById('drawButton');
    if (button) {
        button.disabled = true;
        button.textContent = 'Tirage en cours...';
        
        fetch('/api/draw_cards.php', {
            method: 'POST',
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Vous avez reçu 2 nouvelles cartes !');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                button.disabled = false;
                button.textContent = 'Obtenir des cartes';
            }
        })
    }
}

// Fonction pour afficher les alertes
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.container').firstChild);
    setTimeout(() => alertDiv.remove(), 5000);
}

// Fonction pour la recherche de cartes
function searchCards() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        const searchTerm = searchInput.value.toLowerCase();
        const cards = document.querySelectorAll('.card');
        
        cards.forEach(card => {
            const cardName = card.querySelector('.card-title').textContent.toLowerCase();
            if (cardName.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Gérer la recherche de carte en temp réem
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', searchCards);
    }
}); 