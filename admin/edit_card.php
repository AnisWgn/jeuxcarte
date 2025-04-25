<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/portfolio/project/Jeux_carte/includes/header.php';

// Vérifier si l'utilisateur est admin
if (!isAdmin()) {
    header('Location: ../index.php');
    exit();
}

$card_id = $_GET['id'] ?? null;

if (!$card_id) {
    header('Location: cards.php');
    exit();
}

// Récupérer les informations de la carte
$stmt = $pdo->prepare('SELECT * FROM cards WHERE id = ?');
$stmt->execute([$card_id]);
$card = $stmt->fetch();

if (!$card) {
    header('Location: cards.php');
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
                UPDATE cards 
                SET name = ?, description = ?, available_quantity = ?, image_url = ?
                WHERE id = ?
            ');
            
            if ($stmt->execute([$name, $description, $available_quantity, $image_url, $card_id])) {
                $success = 'La carte a été modifiée avec succès !';
                // Mettre à jour les données affichées
                $card['name'] = $name;
                $card['description'] = $description;
                $card['available_quantity'] = $available_quantity;
                $card['image_url'] = $image_url;
            } else {
                $error = 'Une erreur est survenue lors de la modification de la carte.';
            }
        } catch (PDOException $e) {
            $error = 'Une erreur est survenue lors de la modification de la carte.';
        }
    }
}
?>

<h2 class="mb-4">Modifier la carte</h2>

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
            <input type="text" class="form-control" id="name" name="name" 
                   value="<?php echo htmlspecialchars($card['name']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Description *</label>
            <textarea class="form-control" id="description" name="description" 
                      rows="3" required><?php echo htmlspecialchars($card['description']); ?></textarea>
        </div>
        
        <div class="mb-3">
            <label for="available_quantity" class="form-label">Quantité disponible *</label>
            <input type="number" class="form-control" id="available_quantity" name="available_quantity" 
                   min="0" value="<?php echo $card['available_quantity']; ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="image_url" class="form-label">URL de l'image</label>
            <input type="url" class="form-control" id="image_url" name="image_url" 
                   value="<?php echo htmlspecialchars($card['image_url']); ?>"
                   placeholder="https://exemple.com/image.jpg">
        </div>
        
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="cards.php" class="btn btn-secondary">Retour à la liste</a>
        </div>
    </form>
</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/portfolio/project/Jeux_carte/includes/footer.php';
?> 