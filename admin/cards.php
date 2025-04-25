<?php
require_once '../includes/header.php';
require_once '../config/database.php';

// Vérification des droits d'administration
if (!isAdmin()) {
    header('Location: ../index.php');
    exit();
}

// Traitement de l'ajout de carte
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_card') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $image_url = $_POST['image_url'] ?? '';
    $price = $_POST['price'] ?? 0;
    $available_quantity = $_POST['available_quantity'] ?? 0;

    if (!empty($name) && !empty($description) && !empty($image_url)) {
        $stmt = $conn->prepare("INSERT INTO cards (name, description, image_url, price, available_quantity) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $name, $description, $image_url, $price, $available_quantity);
        
        if ($stmt->execute()) {
            header('Location: cards.php?success=1');
            exit();
        } else {
            $error = "Erreur lors de l'ajout de la carte : " . $conn->error;
        }
    } else {
        $error = "Tous les champs sont obligatoires";
    }
}

// Récupération des cartes
$query = "SELECT * FROM cards ORDER BY name ASC";
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

    .btn-add-card {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        color: white;
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .btn-add-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
    }

    .cards-table {
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

    .card-image {
        width: 150px;
        height: 200px;
        object-fit: contain;
        border-radius: 10px;
        background: #f8f9fa;
        padding: 10px;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: bold;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 100px;
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

    .table td {
        vertical-align: middle;
    }
</style>

<div class="admin-container">
    <div class="container">
        <div class="admin-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="admin-title">Gestion des Cartes</h1>
                <button class="btn btn-add-card" data-bs-toggle="modal" data-bs-target="#addCardModal">
                    <i class="fas fa-plus me-2"></i>Ajouter une carte
                </button>
            </div>
        </div>

        <div class="cards-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Quantité</th>
                        <th>Prix</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($card = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <img src="<?php echo htmlspecialchars($card['image_url']); ?>" 
                                     class="card-image" 
                                     alt="<?php echo htmlspecialchars($card['name']); ?>">
                            </td>
                            <td><?php echo htmlspecialchars($card['name']); ?></td>
                            <td><?php echo htmlspecialchars($card['available_quantity']); ?></td>
                            <td><?php echo number_format($card['price'] ?? 0); ?> pièces</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-action btn-edit" 
                                            onclick="editCard(<?php echo $card['id']; ?>)"
                                            title="Modifier la carte">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </button>
                                    <button class="btn btn-action btn-delete" 
                                            onclick="deleteCard(<?php echo $card['id']; ?>)"
                                            title="Supprimer la carte">
                                        <i class="fas fa-trash me-1"></i>Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ajout de Carte -->
<div class="modal fade" id="addCardModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une nouvelle carte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="POST" action="cards.php">
                    <input type="hidden" name="action" value="add_card">
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL de l'image</label>
                        <input type="url" class="form-control" name="image_url" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prix</label>
                        <input type="number" class="form-control" name="price" value="100" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantité disponible</label>
                        <input type="number" class="form-control" name="available_quantity" value="1" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function getRarityColor(rarity) {
    const colors = {
        'common': 'secondary',
        'uncommon': 'success',
        'rare': 'primary',
        'epic': 'warning',
        'legendary': 'danger'
    };
    return colors[rarity] || 'secondary';
}

function editCard(cardId) {
    // Code pour éditer une carte
    alert('Fonctionnalité d\'édition à venir !');
}

function deleteCard(cardId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette carte ?')) {
        // Code pour supprimer une carte
        alert('Fonctionnalité de suppression à venir !');
    }
}
</script>

<?php
require_once '../includes/footer.php';
?> 