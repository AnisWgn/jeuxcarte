<?php
require_once 'includes/header.php';
require_once 'config/database.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Récupérer la collection de l'utilisateur
$stmt = $conn->prepare('
    SELECT c.*, uc.quantity 
    FROM cards c 
    JOIN user_cards uc ON c.id = uc.card_id 
    WHERE uc.user_id = ?
    ORDER BY c.name ASC
');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$collection = $result->fetch_all(MYSQLI_ASSOC);

// Calculer le total des cartes
$total_cards = array_sum(array_column($collection, 'quantity'));

// Calculer le nombre de cartes uniques
$unique_cards = count(array_unique(array_column($collection, 'name')));

// Calculer le nombre de cartes en double
$duplicates = count($collection) - $unique_cards;
?>

<style>
    .collection-container {
        padding: 3rem 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .collection-header {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 3rem;
    }

    .collection-title {
        font-size: 2.5rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6c757d;
        font-size: 1rem;
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
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
    }

    .card-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    }

    .card-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-bottom: 1px solid #eee;
    }

    .card-content {
        padding: 1.5rem;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .card-description {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .card-quantity {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: bold;
    }

    .card-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .btn-trade {
        flex: 1;
        background: linear-gradient(135deg, #6e8efb 0%, #a777e3 100%);
        color: white;
        border: none;
        padding: 0.8rem;
        border-radius: 10px;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-trade:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(110, 142, 251, 0.3);
    }

    .empty-collection {
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

    .btn-get-cards {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        color: white;
        padding: 1rem 2rem;
        border-radius: 10px;
        font-weight: bold;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-get-cards:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
    }
</style>

<div class="collection-container">
    <div class="container">
        <div class="collection-header">
            <h1 class="collection-title">Ma Collection</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $total_cards; ?></div>
                    <div class="stat-label">Total des cartes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $unique_cards; ?></div>
                    <div class="stat-label">Cartes uniques</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $duplicates; ?></div>
                    <div class="stat-label">Doublons</div>
                </div>
            </div>

            <?php if (empty($collection)): ?>
                <div class="empty-collection">
                    <h2 class="empty-title">Votre collection est vide</h2>
                    <p class="empty-text">Commencez à collectionner des cartes dès maintenant !</p>
                    <a href="shop.php" class="btn btn-get-cards">
                        <i class="fas fa-shopping-cart me-2"></i>Obtenir des cartes
                    </a>
                </div>
            <?php else: ?>
                <div class="cards-grid">
                    <?php foreach ($collection as $card): ?>
                        <div class="card-item">
                            <div class="card-quantity">x<?php echo $card['quantity']; ?></div>
                            <img src="<?php echo htmlspecialchars($card['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($card['name']); ?>" 
                                 class="card-image">
                            <div class="card-content">
                                <h3 class="card-title"><?php echo htmlspecialchars($card['name']); ?></h3>
                                <div class="card-actions">
                                    <button class="btn btn-trade" 
                                            onclick="initiateTrade(<?php echo $card['id']; ?>)">
                                        <i class="fas fa-exchange-alt me-2"></i>Échanger
                                    </button>
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
function initiateTrade(cardId) {
    // Code pour initier un échange
    alert('Fonctionnalité d\'échange à venir !');
}
</script>

<?php
require_once 'includes/footer.php';
?> 