<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/portfolio/project/Jeux_carte/includes/header.php';

// Vérifier si l'utilisateur est admin
if (!isAdmin()) {
    header('Location: ../index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $available_quantity = $_POST['available_quantity'] ?? 0;
    $image_url = $_POST['image_url'] ?? '';

    if (empty($name) || empty($description) || $available_quantity < 0) {
        $error = 'Veuillez remplir tous les champs obligatoires avec des valeurs valides.';
    } else {
        try {
            $stmt = $pdo->prepare('
                INSERT INTO cards (name, description, available_quantity, image_url) 
                VALUES (?, ?, ?, ?)
            ');
            
            if ($stmt->execute([$name, $description, $available_quantity, $image_url])) {
                $success = 'La carte a été ajoutée avec succès !';
            } else {
                $error = 'Une erreur est survenue lors de l\'ajout de la carte.';
            }
        } catch (PDOException $e) {
            $error = 'Une erreur est survenue lors de l\'ajout de la carte.';
        }
    }
}
?>

<h2 class="mb-4">Ajouter une nouvelle carte</h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="">
        <div class="mb-3">
            <label for="name" class="form-label">Nom de la carte *</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Description *</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        
        <div class="mb-3">
            <label for="available_quantity" class="form-label">Quantité disponible *</label>
            <input type="number" class="form-control" id="available_quantity" name="available_quantity" 
                   min="0" value="0" required>
        </div>
        
        <div class="mb-3">
            <label for="image_url" class="form-label">URL de l'image</label>
            <input type="url" class="form-control" id="image_url" name="image_url" 
                   placeholder="https://exemple.com/image.jpg">
        </div>
        
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Ajouter la carte</button>
            <a href="cards.php" class="btn btn-secondary">Retour à la liste</a>
        </div>
    </form>
</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/portfolio/project/Jeux_carte/includes/footer.php';
?> 