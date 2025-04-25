<?php
require_once 'includes/header.php';
require_once 'config/database.php';
?>

<style>
    .card-detail-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        margin: 2rem 0;
    }

    .card-image {
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }

    .card-image:hover {
        transform: scale(1.02);
    }

    .card-info {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .card-title {
        color: #2c3e50;
        font-size: 2.5rem;
        margin-bottom: 1rem;
        font-weight: bold;
    }

    .card-description {
        color: #34495e;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin: 2rem 0;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: #3498db;
    }

    .stat-label {
        color: #7f8c8d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .user-list {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        margin-top: 2rem;
    }

    .user-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #eee;
    }

    .user-item:last-child {
        border-bottom: none;
    }

    .admin-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-custom {
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
</style>

<?php
$card_id = $_GET['id'] ?? null;

if (!$card_id) {
    header('Location: cards.php');
    exit();
}

// Récupérer les informations de la carte
$stmt = $conn->prepare('SELECT * FROM cards WHERE id = ?');
$stmt->bind_param('i', $card_id);
$stmt->execute();
$result = $stmt->get_result();
$card = $result->fetch_assoc();

if (!$card) {
    header('Location: cards.php');
    exit();
}

// Récupérer le nombre de cartes possédées par l'utilisateur connecté
$user_card_quantity = 0;
if (isLoggedIn()) {
    $stmt = $conn->prepare('SELECT quantity FROM user_cards WHERE user_id = ? AND card_id = ?');
    $stmt->bind_param('ii', $_SESSION['user_id'], $card_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $user_card_quantity = $row['quantity'];
    }
}

// Récupérer la liste des utilisateurs possédant cette carte (admin uniquement)
$users_with_card = [];
if (isAdmin()) {
    $stmt = $conn->prepare('
        SELECT u.username, uc.quantity 
        FROM user_cards uc 
        JOIN users u ON uc.user_id = u.id 
        WHERE uc.card_id = ?
    ');
    $stmt->bind_param('i', $card_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $users_with_card = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<div class="container">
    <div class="card-detail-container">
        <div class="row">
            <div class="col-md-6">
                <?php if ($card['image_url']): ?>
                    <img src="<?php echo htmlspecialchars($card['image_url']); ?>" 
                         class="img-fluid card-image" 
                         alt="<?php echo htmlspecialchars($card['name']); ?>">
                <?php endif; ?>
            </div>
            
            <div class="col-md-6">
                <div class="card-info">
                    <h1 class="card-title"><?php echo htmlspecialchars($card['name']); ?></h1>
                    <p class="card-description"><?php echo htmlspecialchars($card['description']); ?></p>
                    
                    <div class="stats-container">
                        <div class="stat-card">
                            <div class="stat-value"><?php echo $card['available_quantity']; ?></div>
                            <div class="stat-label">Disponibles</div>
                        </div>
                        <?php if (isLoggedIn()): ?>
                            <div class="stat-card">
                                <div class="stat-value"><?php echo $user_card_quantity; ?></div>
                                <div class="stat-label">Dans votre collection</div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (isAdmin()): ?>
                        <div class="user-list">
                            <h3>Utilisateurs possédant cette carte</h3>
                            <?php if (empty($users_with_card)): ?>
                                <p class="text-muted">Aucun utilisateur ne possède cette carte.</p>
                            <?php else: ?>
                                <?php foreach ($users_with_card as $user): ?>
                                    <div class="user-item">
                                        <span><?php echo htmlspecialchars($user['username']); ?></span>
                                        <span class="badge bg-primary"><?php echo $user['quantity']; ?> exemplaires</span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="admin-actions">
                            <a href="admin/edit_card.php?id=<?php echo $card_id; ?>" 
                               class="btn btn-warning btn-custom">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <a href="admin/delete_card.php?id=<?php echo $card_id; ?>" 
                               class="btn btn-danger btn-custom"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette carte ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?> 