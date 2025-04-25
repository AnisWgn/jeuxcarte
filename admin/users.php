<?php
require_once '../includes/header.php';
require_once '../config/database.php';

// Vérification des droits d'administration
if (!isAdmin()) {
    header('Location: ../index.php');
    exit();
}

// Récupération des utilisateurs
$query = "SELECT u.*, 
          COUNT(uc.card_id) as card_count, 
          COALESCE(SUM(uc.quantity), 0) as total_cards 
          FROM users u 
          LEFT JOIN user_cards uc ON u.id = uc.user_id 
          GROUP BY u.id, u.username, u.is_admin 
          ORDER BY u.username ASC";

$result = $conn->query($query);

if (!$result) {
    die("Erreur de requête : " . $conn->error);
}
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
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .users-table {
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

    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 1.5rem;
    }

    .user-avatar i {
        margin: 0;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-name {
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 0.25rem;
    }

    .user-email {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .user-stats {
        display: flex;
        gap: 1rem;
    }

    .stat-item {
        text-align: center;
        padding: 0.5rem 1rem;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .stat-value {
        font-weight: bold;
        color: #2c3e50;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-edit {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, #6e8efb 0%, #a777e3 100%);
        color: white;
        border-radius: 20px 20px 0 0;
    }

    .modal-title {
        font-weight: bold;
    }

    .form-control {
        border-radius: 10px;
        padding: 0.8rem;
        border: 2px solid #e9ecef;
    }

    .form-control:focus {
        border-color: #6e8efb;
        box-shadow: 0 0 0 0.2rem rgba(110, 142, 251, 0.25);
    }

    .form-label {
        font-weight: bold;
        color: #2c3e50;
    }
</style>

<div class="admin-container">
    <div class="container">
        <div class="admin-header">
            <h1 class="admin-title">Gestion des Utilisateurs</h1>
            
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Rechercher un utilisateur..." 
                       onkeyup="searchUsers(this.value)">
            </div>

            <div class="users-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Statistiques</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <?php if (!empty($user['avatar_url'])): ?>
                                            <img src="<?php echo htmlspecialchars($user['avatar_url']); ?>" 
                                                 class="user-avatar" 
                                                 alt="<?php echo htmlspecialchars($user['username']); ?>">
                                        <?php else: ?>
                                            <div class="user-avatar">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="user-name"><?php echo htmlspecialchars($user['username']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="user-stats">
                                        <div class="stat-item">
                                            <div class="stat-value"><?php echo $user['card_count']; ?></div>
                                            <div class="stat-label">Cartes uniques</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value"><?php echo $user['total_cards']; ?></div>
                                            <div class="stat-label">Total cartes</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $user['is_admin'] ? 'danger' : 'primary'; ?>">
                                        <?php echo $user['is_admin'] ? 'Administrateur' : 'Utilisateur'; ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-action btn-edit me-2" 
                                            onclick="editUser(<?php echo $user['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-action btn-delete" 
                                            onclick="deleteUser(<?php echo $user['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Édition Utilisateur -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier l'utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <div class="mb-3">
                        <label class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rôle</label>
                        <select class="form-control" name="is_admin" required>
                            <option value="0">Utilisateur</option>
                            <option value="1">Administrateur</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="submitEditUser()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<script>
function searchUsers(query) {
    // Code pour rechercher des utilisateurs
    console.log('Recherche:', query);
}

function editUser(userId) {
    // Code pour éditer un utilisateur
    alert('Fonctionnalité d\'édition à venir !');
}

function deleteUser(userId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        // Code pour supprimer un utilisateur
        alert('Fonctionnalité de suppression à venir !');
    }
}

function submitEditUser() {
    // Code pour enregistrer les modifications
    alert('Fonctionnalité d\'enregistrement à venir !');
}
</script>

<?php
require_once '../includes/footer.php';
?> 