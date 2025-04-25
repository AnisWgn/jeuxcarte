<?php
// Désactiver l'affichage des erreurs HTML
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Définir le type de contenu JSON avant tout autre output
header('Content-Type: application/json');

// Capturer les erreurs PHP
function handleError($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler('handleError');

try {
    // Démarrer la session si elle n'est pas déjà démarrée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Inclure uniquement la configuration de la base de données
    require_once '../config/database.php';

    // Fonction pour vérifier si l'utilisateur est connecté
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    if (!isLoggedIn()) {
        throw new Exception('Vous devez être connecté pour acheter des cartes');
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $cardId = $data['card_id'] ?? null;

    if (!$cardId) {
        throw new Exception('ID de carte manquant');
    }

    // Récupérer les informations de la carte en une seule requête
    $query = "SELECT id, name, price, available_for_purchase FROM cards WHERE id = ? AND available_for_purchase = 1";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Erreur préparation requête carte: ' . $conn->error);
    }

    $stmt->bind_param("i", $cardId);
    if (!$stmt->execute()) {
        throw new Exception('Erreur exécution requête carte: ' . $stmt->error);
    }

    $result = $stmt->get_result();
    $card = $result->fetch_assoc();

    if (!$card) {
        throw new Exception('Carte non disponible');
    }

    // Vérifier si l'utilisateur a assez de pièces
    if ($_SESSION['coins'] < $card['price']) {
        throw new Exception('Solde insuffisant');
    }

    // Démarrer la transaction
    $conn->begin_transaction();

    try {
        // Vérifier si la table user_cards existe (une seule fois par session)
        if (!isset($_SESSION['user_cards_checked'])) {
            $checkTable = $conn->query("SHOW TABLES LIKE 'user_cards'");
            if ($checkTable->num_rows === 0) {
                // Créer la table si elle n'existe pas
                $createTable = "CREATE TABLE user_cards (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    card_id INT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    quantity INT DEFAULT 1,
                    INDEX idx_user_card (user_id, card_id)
                )";
                if (!$conn->query($createTable)) {
                    throw new Exception('Erreur création table: ' . $conn->error);
                }
            } else {
                // Vérifier si la colonne quantity existe
                $checkColumn = $conn->query("SHOW COLUMNS FROM user_cards LIKE 'quantity'");
                if ($checkColumn->num_rows === 0) {
                    // Ajouter la colonne quantity si elle n'existe pas
                    $conn->query("ALTER TABLE user_cards ADD COLUMN quantity INT DEFAULT 1");
                }
            }
            $_SESSION['user_cards_checked'] = true;
        }

        // Vérifier si l'utilisateur possède déjà la carte et mettre à jour la base en une seule requête
        $upsertCard = "INSERT INTO user_cards (user_id, card_id, quantity) 
                       VALUES (?, ?, 1) 
                       ON DUPLICATE KEY UPDATE quantity = quantity + 1";
        $stmt = $conn->prepare($upsertCard);
        if (!$stmt) {
            throw new Exception('Erreur préparation ajout/mise à jour carte: ' . $conn->error);
        }
        
        $stmt->bind_param("ii", $_SESSION['user_id'], $cardId);
        if (!$stmt->execute()) {
            throw new Exception('Erreur exécution ajout/mise à jour carte: ' . $stmt->error);
        }

        // Déduire le prix de la carte du solde de l'utilisateur
        $updateCoins = "UPDATE users SET coins = coins - ? WHERE id = ?";
        $stmt = $conn->prepare($updateCoins);
        if (!$stmt) {
            throw new Exception('Erreur préparation mise à jour pièces: ' . $conn->error);
        }
        
        $stmt->bind_param("ii", $card['price'], $_SESSION['user_id']);
        if (!$stmt->execute()) {
            throw new Exception('Erreur exécution mise à jour pièces: ' . $stmt->error);
        }

        // Mettre à jour le solde dans la session
        $_SESSION['coins'] -= $card['price'];

        $conn->commit();
        echo json_encode([
            'success' => true, 
            'message' => 'Achat réussi !',
            'price' => $card['price'],
            'newBalance' => $_SESSION['coins']
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 