<?php
require_once '../includes/header.php';
require_once '../config/database.php';

// Vérification des droits d'administration
if (!isAdmin()) {
    header('Location: ../index.php');
    exit();
}

// Récupération des statistiques
$stats = [
    'total_users' => $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'],
    'total_cards' => $conn->query("SELECT COUNT(*) as count FROM cards")->fetch_assoc()['count'],
    'total_transactions' => $conn->query("SELECT COUNT(*) as count FROM transactions")->fetch_assoc()['count'],
    'total_coins' => $conn->query("SELECT SUM(coins) as total FROM users")->fetch_assoc()['total']
];

// Récupération des données pour les graphiques
$daily_transactions = $conn->query("
    SELECT DATE(date) as date, COUNT(*) as count, SUM(amount) as total 
    FROM transactions 
    GROUP BY DATE(date) 
    ORDER BY date DESC 
    LIMIT 7
")->fetch_all(MYSQLI_ASSOC);

$card_rarity = $conn->query("
    SELECT rarity, COUNT(*) as count 
    FROM cards 
    GROUP BY rarity
")->fetch_all(MYSQLI_ASSOC);

$user_activity = $conn->query("
    SELECT DATE(created_at) as date, COUNT(*) as count 
    FROM users 
    GROUP BY DATE(created_at) 
    ORDER BY date DESC 
    LIMIT 30
")->fetch_all(MYSQLI_ASSOC);
?>

<style>
    .admin-container {
        padding: 3rem 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .admin-header {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 3rem;
    }

    .admin-title {
        font-size: 2.5rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: #6e8efb;
    }

    .stat-title {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: #2c3e50;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .chart-card {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .chart-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 1.5rem;
    }

    .chart-container {
        height: 300px;
        position: relative;
    }

    .recent-activity {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .activity-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 1.5rem;
    }

    .activity-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: #6e8efb;
    }

    .activity-details {
        flex: 1;
    }

    .activity-text {
        color: #2c3e50;
        margin-bottom: 0.25rem;
    }

    .activity-time {
        color: #6c757d;
        font-size: 0.9rem;
    }
</style>

<div class="admin-container">
    <div class="container">
        <div class="admin-header">
            <h1 class="admin-title">Statistiques</h1>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-title">Utilisateurs</div>
                    <div class="stat-value"><?php echo number_format($stats['total_users']); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-cards"></i>
                    </div>
                    <div class="stat-title">Cartes</div>
                    <div class="stat-value"><?php echo number_format($stats['total_cards']); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="stat-title">Transactions</div>
                    <div class="stat-value"><?php echo number_format($stats['total_transactions']); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stat-title">Pièces en circulation</div>
                    <div class="stat-value"><?php echo number_format($stats['total_coins']); ?></div>
                </div>
            </div>

            <div class="charts-grid">
                <div class="chart-card">
                    <h3 class="chart-title">Transactions quotidiennes</h3>
                    <div class="chart-container">
                        <canvas id="transactionsChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <h3 class="chart-title">Répartition des raretés</h3>
                    <div class="chart-container">
                        <canvas id="rarityChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="recent-activity">
                <h3 class="activity-title">Activité récente</h3>
                <ul class="activity-list">
                    <?php foreach ($daily_transactions as $transaction): ?>
                        <li class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div class="activity-details">
                                <div class="activity-text">
                                    <?php echo number_format($transaction['count']); ?> transactions pour 
                                    <?php echo number_format($transaction['total']); ?> pièces
                                </div>
                                <div class="activity-time">
                                    <?php echo date('d/m/Y', strtotime($transaction['date'])); ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des transactions
const transactionsCtx = document.getElementById('transactionsChart').getContext('2d');
new Chart(transactionsCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($daily_transactions, 'date')); ?>,
        datasets: [{
            label: 'Nombre de transactions',
            data: <?php echo json_encode(array_column($daily_transactions, 'count')); ?>,
            borderColor: '#6e8efb',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Graphique des raretés
const rarityCtx = document.getElementById('rarityChart').getContext('2d');
new Chart(rarityCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode(array_column($card_rarity, 'rarity')); ?>,
        datasets: [{
            data: <?php echo json_encode(array_column($card_rarity, 'count')); ?>,
            backgroundColor: [
                '#95a5a6',
                '#2ecc71',
                '#3498db',
                '#f1c40f',
                '#e74c3c'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>

<?php
require_once '../includes/footer.php';
?> 