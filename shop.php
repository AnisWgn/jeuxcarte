<?php
require_once 'includes/header.php';
require_once 'config/database.php';

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Récupérer le solde de l'utilisateur depuis la session
// Si non présent, alors récupérer de la base de données
if (!isset($_SESSION['coins'])) {
    $stmt = $conn->prepare('SELECT coins FROM users WHERE id = ?');
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user) {
        // Vérifier si l'utilisateur existe
        $checkUser = $conn->prepare('SELECT id FROM users WHERE id = ?');
        $checkUser->bind_param('i', $_SESSION['user_id']);
        $checkUser->execute();
        $userExists = $checkUser->get_result()->fetch_assoc();
        
        if (!$userExists) {
            header('Location: logout.php');
            exit();
        }
        
        // Vérifier si la colonne coins existe
        $checkColumn = $conn->query("SHOW COLUMNS FROM users LIKE 'coins'");
        if ($checkColumn->num_rows === 0) {
            $conn->query("ALTER TABLE users ADD COLUMN coins INT DEFAULT 1000");
            $conn->query("UPDATE users SET coins = 1000 WHERE id = " . $_SESSION['user_id']);
        }
        
        // Récupérer à nouveau le solde
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    }
    
    // Mettre à jour le solde dans la session
    $_SESSION['coins'] = $user['coins'] ?? 0;
}

// Récupérer les cartes disponibles à l'achat avec leur prix
$query = "SELECT id, name, image_url, price, available_for_purchase FROM cards WHERE available_for_purchase = 1 ORDER BY price ASC";
$cardsResult = $conn->query($query);

// Récupérer les cartes que l'utilisateur possède déjà avec leur quantité en une seule requête
$userCards = [];
$userCardsQuery = "SELECT card_id, quantity FROM user_cards WHERE user_id = ?";
$stmt = $conn->prepare($userCardsQuery);
if ($stmt) {
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $userCardsResult = $stmt->get_result();
    while ($row = $userCardsResult->fetch_assoc()) {
        $userCards[$row['card_id']] = $row['quantity'];
    }
}
?>

<style>
    /* Style modernisé pour la boutique */
    .shop-container {
        padding: 3rem 0;
        background: linear-gradient(135deg, #f0f4f9 0%, #d8e3f3 100%);
        min-height: 100vh;
        font-family: 'Poppins', sans-serif;
    }

    .shop-header {
        background: white;
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(31, 45, 61, 0.08);
        margin-bottom: 3rem;
    }

    .shop-title {
        font-size: 2.6rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        letter-spacing: -0.5px;
        position: relative;
        display: inline-block;
    }
    
    .shop-title:after {
        content: '';
        position: absolute;
        bottom: -6px;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #6e8efb, #a777e3);
        border-radius: 3px;
    }

    .balance-card {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        padding: 1.8rem;
        border-radius: 14px;
        box-shadow: 0 10px 20px rgba(124, 58, 237, 0.15);
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .balance-card:before {
        content: '';
        position: absolute;
        top: -10px;
        right: -10px;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .balance-label {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        opacity: 0.85;
        font-weight: 500;
    }

    .balance-value {
        font-size: 2.7rem;
        font-weight: 700;
        margin-top: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .balance-value i {
        color: #ffd700;
        filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.2));
    }

    .pack-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2.2rem;
        margin-top: 2.5rem;
    }

    .pack-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(31, 45, 61, 0.07);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        z-index: 1;
    }

    .pack-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 30px rgba(31, 45, 61, 0.1);
    }
    
    .pack-card:hover .card-title {
        color: #4f46e5;
    }

    .pack-image {
        width: 100%;
        height: 220px;
        object-fit: contain;
        background: #f9fafc;
        padding: 0.8rem;
        transition: transform 0.5s ease;
    }
    
    .pack-card:hover .pack-image {
        transform: scale(1.05);
    }

    .pack-content {
        padding: 1.8rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .card-content {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .card-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.8rem;
        transition: color 0.3s ease;
    }

    .card-price {
        font-weight: 700;
        color: #4f46e5;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .card-price i {
        color: #ffd700;
        margin-right: 8px;
        font-size: 1.1rem;
    }

    .card-quantity {
        color: #4b5563;
        margin-bottom: 1.2rem;
        padding: 8px 12px;
        background-color: #f3f4f6;
        border-radius: 8px;
        display: inline-block;
        font-size: 0.95rem;
    }
    
    .card-quantity i {
        color: #10b981;
        margin-right: 6px;
    }

    .card-actions {
        margin-top: auto;
    }

    .btn-buy {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        padding: 0.9rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        transition: all 0.3s ease;
        width: 100%;
        font-size: 0.9rem;
        position: relative;
        overflow: hidden;
    }
    
    .btn-buy:before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }
    
    .btn-buy:hover:before {
        left: 100%;
    }

    .btn-buy:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 14px rgba(79, 70, 229, 0.3);
    }
    
    .btn-buy:active {
        transform: translateY(-1px);
    }
</style>

<div class="shop-container">
    <div class="container">
        <div class="shop-header">
            <h1 class="shop-title">Boutique</h1>
            
            <div class="balance-card">
                <div class="balance-label">Votre solde</div>
                <div class="balance-value">
                    <i class="fas fa-coins me-2"></i>
                    <?php echo number_format($_SESSION['coins']); ?> pièces
                </div>
            </div>

            <div class="pack-grid">
                <?php while ($card = $cardsResult->fetch_assoc()): ?>
                    <div class="pack-card" data-card-id="<?php echo $card['id']; ?>">
                        <img src="<?php echo htmlspecialchars($card['image_url']); ?>" 
                             class="pack-image" 
                             alt="<?php echo htmlspecialchars($card['name']); ?>"
                             loading="lazy"> <!-- Chargement différé des images -->
                        <div class="pack-content">
                            <div class="card-content">
                                <h3 class="card-title"><?php echo htmlspecialchars($card['name']); ?></h3>
                                <div class="card-price mb-2">
                                    <i class="fas fa-coins"></i><?php echo number_format($card['price']); ?> pièces
                                </div>
                                <?php if (isset($userCards[$card['id']])): ?>
                                    <div class="card-quantity">
                                        <i class="fas fa-check-circle"></i>Vous en possédez <?php echo $userCards[$card['id']]; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="card-actions">
                                    <button class="btn btn-buy" 
                                            onclick="buyCard(<?php echo $card['id']; ?>)">
                                        <i class="fas fa-shopping-cart me-2"></i>
                                        <?php echo isset($userCards[$card['id']]) ? 'Acheter (encore)' : 'Acheter'; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Optimiser la fonction d'achat pour éviter de recharger la page
function buyCard(cardId) {
    if (confirm('Voulez-vous vraiment acheter cette carte ?')) {
        // Désactiver tous les boutons d'achat pour éviter les clics multiples
        const buyButtons = document.querySelectorAll('.btn-buy');
        buyButtons.forEach(btn => btn.disabled = true);
        
        fetch('api/buy_card.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ card_id: cardId })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error('Erreur serveur: ' + text);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Mise à jour du solde sans recharger la page
                const balanceElement = document.querySelector('.balance-value');
                const currentCoins = parseInt(balanceElement.textContent.replace(/\D/g, ''));
                const newCoins = currentCoins - data.price;
                balanceElement.innerHTML = '<i class="fas fa-coins me-2"></i>' + newCoins.toLocaleString() + ' pièces';
                
                // Mise à jour de la quantité possédée
                const cardElement = document.querySelector(`[data-card-id="${cardId}"]`);
                if (cardElement) {
                    const quantityElement = cardElement.querySelector('.card-quantity');
                    if (quantityElement) {
                        const currentQuantity = parseInt(quantityElement.textContent.match(/\d+/)[0]);
                        quantityElement.innerHTML = '<i class="fas fa-check-circle"></i>Vous en possédez ' + (currentQuantity + 1);
                    } else {
                        const cardContent = cardElement.querySelector('.card-content');
                        const cardPrice = cardElement.querySelector('.card-price');
                        const newQuantityElement = document.createElement('div');
                        newQuantityElement.className = 'card-quantity';
                        newQuantityElement.innerHTML = '<i class="fas fa-check-circle"></i>Vous en possédez 1';
                        cardContent.insertBefore(newQuantityElement, cardPrice.nextSibling);
                    }
                }
                
                alert('Achat réussi !');
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur détaillée:', error);
            alert('Erreur: ' + error.message);
        })
        .finally(() => {
            // Réactiver les boutons d'achat
            buyButtons.forEach(btn => btn.disabled = false);
        });
    }
}
</script>

<?php
require_once 'includes/footer.php';
?> 