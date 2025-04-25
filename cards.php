<?php
require_once 'includes/header.php';
require_once 'config/database.php';

// Récupération des cartes
$query = "SELECT * FROM cards ORDER BY name ASC";
$result = $conn->query($query);

if (!$result) {
    die("Erreur de requête : " . $conn->error);
}

$cards = $result->fetch_all(MYSQLI_ASSOC);
?>

<style>
    .cards-container {
        padding: 3rem 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .cards-header {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 3rem;
    }

    .cards-title {
        font-size: 2.5rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .search-box {
        position: relative;
        margin-bottom: 2rem;
    }

    .search-input {
        padding: 1rem 1rem 1rem 3rem;
        border-radius: 10px;
        border: 2px solid #e9ecef;
        width: 100%;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: #6e8efb;
        box-shadow: 0 0 0 0.2rem rgba(110, 142, 251, 0.25);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        padding: 1rem;
    }

    .card-item {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .card-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    }

    .card-image {
        width: 100%;
        height: 300px;
        object-fit: contain;
        border-bottom: 1px solid #eee;
        padding: 1rem;
        background: #f8f9fa;
    }

    .card-content {
        padding: 1.5rem;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .card-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-card {
        flex: 1;
        padding: 0.8rem;
        border-radius: 10px;
        font-weight: bold;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-view {
        background: linear-gradient(135deg, #6e8efb 0%, #a777e3 100%);
        color: white;
    }

    .btn-trade {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        color: white;
    }

    .btn-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .empty-cards {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .empty-title {
        font-size: 2rem;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .empty-text {
        color: #6c757d;
        margin-bottom: 2rem;
    }
</style>

<div class="cards-container">
    <div class="container">
        <div class="cards-header">
            <h1 class="cards-title">Toutes les Cartes</h1>
            
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" 
                       class="search-input" 
                       placeholder="Rechercher une carte..." 
                       onkeyup="searchCards(this.value)">
            </div>

            <?php if (empty($cards)): ?>
                <div class="empty-cards">
                    <h2 class="empty-title">Aucune carte disponible</h2>
                    <p class="empty-text">Revenez plus tard pour découvrir de nouvelles cartes !</p>
                </div>
            <?php else: ?>
                <div class="cards-grid">
                    <?php foreach ($cards as $card): ?>
                        <div class="card-item">
                            <img src="<?php echo htmlspecialchars($card['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($card['name']); ?>" 
                                 class="card-image">
                            <div class="card-content">
                                <h3 class="card-title"><?php echo htmlspecialchars($card['name']); ?></h3>
                                <div class="card-actions">
                                    <a href="card_detail.php?id=<?php echo $card['id']; ?>" 
                                       class="btn btn-card btn-view">
                                        <i class="fas fa-eye me-2"></i>Voir
                                    </a>
                                    <?php if (isLoggedIn()): ?>
                                        <button class="btn btn-card btn-trade" 
                                                onclick="initiateTrade(<?php echo $card['id']; ?>)">
                                            <i class="fas fa-exchange-alt me-2"></i>Échanger
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function searchCards(query) {
    const cards = document.querySelectorAll('.card-item');
    query = query.toLowerCase();
    
    cards.forEach(card => {
        const title = card.querySelector('.card-title').textContent.toLowerCase();
        if (title.includes(query)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

function initiateTrade(cardId) {
    // Code pour initier un échange
    alert('Fonctionnalité d\'échange à venir !');
}
</script>

<?php
require_once 'includes/footer.php';
?> 