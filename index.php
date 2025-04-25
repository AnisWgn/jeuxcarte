<?php
require_once 'includes/header.php';
?>

<style>
    .hero-section {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        padding: 3.5rem 0;
        margin-bottom: 3rem;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(79, 70, 229, 0.25);
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -70px;
        left: -70px;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        z-index: 0;
    }

    .hero-title {
        font-size: 3.2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        letter-spacing: -0.5px;
        position: relative;
        z-index: 1;
    }
    
    .hero-subtitle {
        font-size: 1.3rem;
        font-weight: 400;
        opacity: 0.9;
        margin-bottom: 2rem;
        position: relative;
        z-index: 1;
    }
    
    .hero-btn {
        background-color: white;
        color: #4f46e5;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        position: relative;
        z-index: 1;
    }
    
    .hero-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 15px rgba(0, 0, 0, 0.2);
        color: #4338ca;
    }

    .feature-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        height: 100%;
        margin-bottom: 1.5rem;
        box-shadow: 0 10px 25px rgba(31, 41, 55, 0.07);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .feature-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 15px 30px rgba(31, 41, 55, 0.1);
    }
    
    .feature-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #4f46e5, #7c3aed);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }
    
    .feature-card:hover::after {
        transform: scaleX(1);
    }

    .feature-icon {
        font-size: 2.2rem;
        margin-bottom: 1.5rem;
        color: #4f46e5;
        background: rgba(79, 70, 229, 0.08);
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .feature-card:hover .feature-icon {
        transform: scale(1.1);
        background: rgba(79, 70, 229, 0.15);
    }
    
    .feature-title {
        font-weight: 600;
        font-size: 1.4rem;
        margin-bottom: 1rem;
        color: #2d3748;
    }
    
    .feature-text {
        color: #6b7280;
        font-size: 1.05rem;
        line-height: 1.6;
    }

    .action-section {
        background: #f3f4f6;
        padding: 3rem 0;
        margin-top: 3rem;
        border-radius: 16px;
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .action-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        height: 100%;
        box-shadow: 0 10px 25px rgba(31, 41, 55, 0.07);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }
    
    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(31, 41, 55, 0.1);
    }
    
    .action-title {
        font-weight: 600;
        font-size: 1.5rem;
        margin-bottom: 0.7rem;
        color: #2d3748;
    }
    
    .action-text {
        color: #6b7280;
        font-size: 1.05rem;
        margin-bottom: 1.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        font-weight: 500;
        padding: 12px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
    }
    
    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 15px rgba(79, 70, 229, 0.35);
        background-image: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
    }
    
    .btn-primary:active {
        transform: translateY(-1px);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        font-weight: 500;
        padding: 12px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.25);
    }
    
    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 15px rgba(16, 185, 129, 0.35);
        background-image: linear-gradient(135deg, #059669 0%, #047857 100%);
    }
    
    .btn-success:active {
        transform: translateY(-1px);
    }
    
    /* Modal styles */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem 2rem;
    }
    
    .modal-title {
        font-weight: 600;
        color: #2d3748;
    }
    
    .modal-body {
        padding: 2rem;
    }
    
    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem 2rem;
    }
    
    .btn-close {
        box-shadow: none;
        opacity: 0.5;
    }
    
    .btn-close:hover {
        opacity: 0.8;
    }
    
    .lead {
        font-size: 1.2rem;
        color: #4b5563;
    }
    
    /* Card animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card-animated {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }
    
    /* Timer display */
    #countdownTimer {
        padding: 10px;
        background: rgba(79, 70, 229, 0.08);
        border-radius: 8px;
        margin-top: 10px;
        color: #4f46e5;
        font-weight: 500;
    }
</style>

<div class="hero-section text-center">
    <div class="container">
        <h1 class="hero-title">Bienvenue sur PokeGeo</h1>
        <p class="hero-subtitle">Collectionnez des cartes virtuelles uniques de vos pays préférés !</p>
        <?php if (!isLoggedIn()): ?>
            <a class="hero-btn" href="register.php">
                <i class="fas fa-user-plus me-2"></i>S'inscrire
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="feature-card text-center">
                <div class="feature-icon mx-auto">
                    <i class="fas fa-gem"></i>
                </div>
                <h3 class="feature-title">Collectionnez</h3>
                <p class="feature-text">Obtenez des cartes uniques toutes les heures et créez votre collection personnelle.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card text-center">
                <div class="feature-icon mx-auto">
                    <i class="fas fa-globe-americas"></i>
                </div>
                <h3 class="feature-title">Explorez</h3>
                <p class="feature-text">Découvrez de nouvelles cartes représentant différents pays et leur histoire fascinante.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card text-center">
                <div class="feature-icon mx-auto">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <h3 class="feature-title">Échangez</h3>
                <p class="feature-text">Partagez vos cartes avec d'autres joueurs et complétez votre collection.</p>
            </div>
        </div>
    </div>

    <?php if (isLoggedIn()): ?>
        <div class="action-section">
            <div class="row">
                <div class="col-md-6">
                    <div class="action-card">
                        <h3 class="action-title">Votre Collection</h3>
                        <p class="action-text">Consultez votre collection de cartes et organisez vos trésors.</p>
                        <a href="collection.php" class="btn btn-primary">
                            <i class="fas fa-book-open me-2"></i>Voir ma collection
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="action-card">
                        <h3 class="action-title">Obtenir des Cartes</h3>
                        <p class="action-text">Cliquez pour obtenir 2 cartes aléatoires et agrandir votre collection.</p>
                        <button id="drawButton" class="btn btn-success">
                            <i class="fas fa-random me-2"></i>Obtenir des cartes
                        </button>
                        <div id="countdownTimer" class="mt-2" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal pour afficher les cartes tirées -->
        <div class="modal fade" id="cardsModal" tabindex="-1" aria-labelledby="cardsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cardsModalLabel">Vos nouvelles cartes !</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <p class="lead">Félicitations ! Vous avez obtenu ces cartes :</p>
                        </div>
                        <div class="row" id="drawnCards">
                            <!-- Les cartes tirées seront ajoutées ici par JavaScript -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Super !</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const drawButton = document.getElementById('drawButton');
    const countdownTimer = document.getElementById('countdownTimer');
    
    if (drawButton) {
        // Vérifier s'il y a un délai enregistré en localStorage
        const nextDrawTime = localStorage.getItem('nextDrawTime');
        
        if (nextDrawTime && parseInt(nextDrawTime) > Date.now()) {
            // Désactiver le bouton et afficher le compte à rebours
            drawButton.disabled = true;
            countdownTimer.style.display = 'block';
            updateCountdown();
            const countdown = setInterval(updateCountdown, 1000);
            
            function updateCountdown() {
                const timeLeft = parseInt(nextDrawTime) - Date.now();
                
                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    drawButton.disabled = false;
                    countdownTimer.style.display = 'none';
                    return;
                }
                
                const minutes = Math.floor(timeLeft / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                countdownTimer.textContent = `Prochain tirage dans ${minutes}m ${seconds}s`;
            }
        }
        
        drawButton.addEventListener('click', function() {
            drawButton.disabled = true;
            drawButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Tirage...';
            
            fetch('api/draw_cards.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur réseau: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        fetch('api/get_drawn_cards.php')
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Erreur réseau: ' + response.status);
                                }
                                return response.json();
                            })
                            .then(cardData => {
                                if (cardData.success) {
                                    showDrawnCards(cardData.cards);
                                    
                                    const nextDrawTime = Date.now() + (60 * 60 * 1000); // 1 heure
                                    localStorage.setItem('nextDrawTime', nextDrawTime);
                                    
                                    drawButton.innerHTML = '<i class="fas fa-random me-2"></i>Obtenir des cartes';
                                    
                                    countdownTimer.style.display = 'block';
                                    updateCountdown();
                                    const countdown = setInterval(updateCountdown, 1000);
                                    
                                    function updateCountdown() {
                                        const timeLeft = parseInt(nextDrawTime) - Date.now();
                                        
                                        if (timeLeft <= 0) {
                                            clearInterval(countdown);
                                            drawButton.disabled = false;
                                            countdownTimer.style.display = 'none';
                                            return;
                                        }
                                        
                                        const minutes = Math.floor(timeLeft / (1000 * 60));
                                        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                                        countdownTimer.textContent = `Prochain tirage dans ${minutes}m ${seconds}s`;
                                    }
                                } else {
                                    alert(cardData.message || 'Erreur lors de la récupération des cartes');
                                    drawButton.disabled = false;
                                    drawButton.innerHTML = '<i class="fas fa-random me-2"></i>Obtenir des cartes';
                                }
                            })
                            .catch(error => {
                                alert('Erreur: ' + error.message);
                                drawButton.disabled = false;
                                drawButton.innerHTML = '<i class="fas fa-random me-2"></i>Obtenir des cartes';
                            });
                    } else {
                        alert(data.message || 'Erreur lors du tirage des cartes');
                        
                        if (data.message && data.message.includes('attendre encore')) {
                            const minutesMatch = data.message.match(/(\d+) minutes/);
                            if (minutesMatch && minutesMatch[1]) {
                                const minutes = parseInt(minutesMatch[1]);
                                const nextDrawTime = Date.now() + (minutes * 60 * 1000);
                                localStorage.setItem('nextDrawTime', nextDrawTime);
                                
                                countdownTimer.style.display = 'block';
                                updateCountdown();
                                const countdown = setInterval(updateCountdown, 1000);
                                
                                function updateCountdown() {
                                    const timeLeft = parseInt(nextDrawTime) - Date.now();
                                    
                                    if (timeLeft <= 0) {
                                        clearInterval(countdown);
                                        drawButton.disabled = false;
                                        countdownTimer.style.display = 'none';
                                        return;
                                    }
                                    
                                    const minutes = Math.floor(timeLeft / (1000 * 60));
                                    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                                    countdownTimer.textContent = `Prochain tirage dans ${minutes}m ${seconds}s`;
                                }
                            } else {
                                drawButton.disabled = false;
                            }
                        } else {
                            drawButton.disabled = false;
                        }
                        
                        drawButton.innerHTML = '<i class="fas fa-random me-2"></i>Obtenir des cartes';
                    }
                })
                .catch(error => {
                    alert('Erreur: ' + error.message);
                    drawButton.disabled = false;
                    drawButton.innerHTML = '<i class="fas fa-random me-2"></i>Obtenir des cartes';
                });
        });
    }
});

// Fonction pour afficher les cartes tirées
function showDrawnCards(cards) {
    const cardsContainer = document.getElementById('drawnCards');
    cardsContainer.innerHTML = '';
    
    cards.forEach((card, index) => {
        const cardCol = document.createElement('div');
        cardCol.className = 'col-md-6 mb-4';
        
        const cardElement = document.createElement('div');
        cardElement.className = 'card card-animated';
        cardElement.style.borderRadius = '12px';
        cardElement.style.overflow = 'hidden';
        cardElement.style.boxShadow = '0 10px 25px rgba(31, 41, 55, 0.07)';
        cardElement.style.animationDelay = `${index * 0.2}s`;
        
        // Contenu de la carte
        const cardImage = document.createElement('img');
        cardImage.src = card.image_url;
        cardImage.className = 'card-img-top';
        cardImage.style.height = '200px';
        cardImage.style.objectFit = 'contain';
        cardImage.style.padding = '1rem';
        cardImage.style.background = '#f9fafc';
        cardImage.alt = card.name;
        
        const cardBody = document.createElement('div');
        cardBody.className = 'card-body text-center';
        cardBody.style.padding = '1.5rem';
        
        const cardTitle = document.createElement('h5');
        cardTitle.className = 'card-title';
        cardTitle.style.fontWeight = '600';
        cardTitle.style.marginBottom = '0.7rem';
        cardTitle.style.color = '#2d3748';
        cardTitle.textContent = card.name;
        
        const cardRarity = document.createElement('p');
        cardRarity.className = 'card-text';
        
        const rarityColors = {
            'Commune': 'secondary',
            'Peu commune': 'primary',
            'Rare': 'info',
            'Très rare': 'warning',
            'Légendaire': 'danger'
        };
        
        const badgeColor = rarityColors[card.rarity] || 'secondary';
        
        cardRarity.innerHTML = `<small>Rareté: <span class="badge bg-${badgeColor}" style="padding: 5px 10px; font-weight: 500;">${card.rarity}</span></small>`;
        
        cardBody.appendChild(cardTitle);
        cardBody.appendChild(cardRarity);
        
        cardElement.appendChild(cardImage);
        cardElement.appendChild(cardBody);
        
        cardCol.appendChild(cardElement);
        cardsContainer.appendChild(cardCol);
    });
    
    const cardsModal = new bootstrap.Modal(document.getElementById('cardsModal'));
    cardsModal.show();
}

// Fonction pour obtenir la couleur en fonction de la rareté
function getRarityColor(rarity) {
    switch(rarity) {
        case 'Commune': return 'secondary';
        case 'Peu commune': return 'primary';
        case 'Rare': return 'info';
        case 'Très rare': return 'warning';
        case 'Légendaire': return 'danger';
        default: return 'secondary';
    }
}
</script>

<?php
require_once 'includes/footer.php';
?> 