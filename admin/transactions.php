<?php
require_once '../includes/header.php';
require_once '../config/database.php';

// Vérification des droits d'administration
if (!isAdmin()) {
    header('Location: ../index.php');
    exit();
}

// Récupération des transactions
$query = "SELECT t.*, u.username, c.name as card_name 
          FROM transactions t 
          JOIN users u ON t.user_id = u.id 
          LEFT JOIN cards c ON t.card_id = c.id 
          ORDER BY t.date DESC";
$result = $conn->query($query);
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

    .filters {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .filter-item {
        flex: 1;
    }

    .filter-label {
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .filter-select {
        width: 100%;
        padding: 0.8rem;
        border-radius: 10px;
        border: 2px solid #e9ecef;
        background: white;
    }

    .transactions-table {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.9rem;
        color: #6c757d;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background: #f8f9fa;
    }

    .transaction-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .transaction-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .transaction-details {
        flex: 1;
    }

    .transaction-user {
        font-weight: bold;
        color: #2c3e50;
    }

    .transaction-card {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .transaction-amount {
        font-weight: bold;
    }

    .transaction-amount.positive {
        color: #2ecc71;
    }

    .transaction-amount.negative {
        color: #e74c3c;
    }

    .transaction-date {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .transaction-type {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        text-transform: uppercase;
    }

    .type-purchase {
        background: rgba(46, 204, 113, 0.1);
        color: #2ecc71;
    }

    .type-sale {
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    .type-gift {
        background: rgba(52, 152, 219, 0.1);
        color: #3498db;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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

    .stat-value.positive {
        color: #2ecc71;
    }

    .stat-value.negative {
        color: #e74c3c;
    }
</style>

<div class="admin-container">
    <div class="container">
        <div class="admin-header">
            <h1 class="admin-title">Gestion des Transactions</h1>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-title">Transactions totales</div>
                    <div class="stat-value">1,234</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Revenus totaux</div>
                    <div class="stat-value positive">+50,000 pièces</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Dépenses totales</div>
                    <div class="stat-value negative">-25,000 pièces</div>
                </div>
            </div>

            <div class="filters">
                <div class="filter-item">
                    <div class="filter-label">Type de transaction</div>
                    <select class="filter-select" onchange="filterTransactions()">
                        <option value="">Tous les types</option>
                        <option value="purchase">Achats</option>
                        <option value="sale">Ventes</option>
                        <option value="gift">Cadeaux</option>
                    </select>
                </div>
                <div class="filter-item">
                    <div class="filter-label">Période</div>
                    <select class="filter-select" onchange="filterTransactions()">
                        <option value="">Toute la période</option>
                        <option value="today">Aujourd'hui</option>
                        <option value="week">Cette semaine</option>
                        <option value="month">Ce mois</option>
                    </select>
                </div>
                <div class="filter-item">
                    <div class="filter-label">Utilisateur</div>
                    <select class="filter-select" onchange="filterTransactions()">
                        <option value="">Tous les utilisateurs</option>
                        <!-- Options des utilisateurs seront ajoutées dynamiquement -->
                    </select>
                </div>
            </div>

            <div class="transactions-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Transaction</th>
                            <th>Montant</th>
                            <th>Type</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($transaction = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div class="transaction-info">
                                        <img src="<?php echo htmlspecialchars($transaction['avatar_url'] ?? '../assets/default-avatar.png'); ?>" 
                                             class="transaction-avatar" 
                                             alt="<?php echo htmlspecialchars($transaction['username']); ?>">
                                        <div class="transaction-details">
                                            <div class="transaction-user"><?php echo htmlspecialchars($transaction['username']); ?></div>
                                            <div class="transaction-card"><?php echo htmlspecialchars($transaction['card_name'] ?? 'N/A'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="transaction-amount <?php echo $transaction['amount'] > 0 ? 'positive' : 'negative'; ?>">
                                        <?php echo $transaction['amount'] > 0 ? '+' : ''; ?>
                                        <?php echo number_format($transaction['amount']); ?> pièces
                                    </div>
                                </td>
                                <td>
                                    <span class="transaction-type type-<?php echo $transaction['type']; ?>">
                                        <?php echo ucfirst($transaction['type']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="transaction-date">
                                        <?php echo date('d/m/Y H:i', strtotime($transaction['date'])); ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function filterTransactions() {
    // Code pour filtrer les transactions
    console.log('Filtrage des transactions...');
}
</script>

<?php
require_once '../includes/footer.php';
?> 