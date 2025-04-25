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
        throw new Exception('Vous devez être connecté pour tirer des cartes.');
    }
    
    // Vérifier l'existence des tables nécessaires
    $requiredTables = ['cards', 'user_cards', 'users'];
    foreach ($requiredTables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows == 0) {
            throw new Exception("La table '$table' n'existe pas dans la base de données.");
        }
    }
    
    // Vérifier les colonnes nécessaires
    $requiredColumns = [
        'cards' => ['id', 'name', 'image_url', 'description'],
        'user_cards' => ['user_id', 'card_id', 'quantity'],
        'users' => ['id', 'last_card_draw']
    ];
    
    foreach ($requiredColumns as $table => $columns) {
        foreach ($columns as $column) {
            $result = $conn->query("SHOW COLUMNS FROM $table LIKE '$column'");
            if ($result->num_rows == 0) {
                throw new Exception("La colonne '$column' n'existe pas dans la table '$table'.");
            }
        }
    }

    // Vérifier si l'utilisateur peut tirer des cartes (1 heure d'attente)
    $userId = $conn->real_escape_string($_SESSION['user_id']);
    $result = $conn->query("SELECT last_card_draw FROM users WHERE id = $userId");
    
    if (!$result) {
        throw new Exception('Erreur lors de l\'exécution de la requête: ' . $conn->error);
    }
    
    $user = $result->fetch_assoc();
    if (!$user) {
        throw new Exception('Utilisateur non trouvé dans la base de données.');
    }

    if (!$user['last_card_draw']) {
        // Si l'utilisateur n'a jamais tiré de carte, on peut l'autoriser à tirer immédiatement
        $last_draw = 0;
    } else {
        $last_draw = strtotime($user['last_card_draw']);
    }

    $now = time();
    $time_diff = $now - $last_draw;

    if ($time_diff < 3600) { // 3600 secondes = 1 heure
        $remaining_time = 3600 - $time_diff;
        throw new Exception('Vous devez attendre encore ' . ceil($remaining_time / 60) . ' minutes avant de pouvoir tirer des cartes.');
    }

    // Démarrer la transaction
    if (!$conn->begin_transaction()) {
        throw new Exception('Impossible de démarrer une transaction: ' . $conn->error);
    }

    try {
        // Sélectionner 2 cartes aléatoires disponibles - Utiliser une requête directe
        $result = $conn->query('SELECT id, name, image_url, description FROM cards ORDER BY RAND() LIMIT 2');
        
        if (!$result) {
            throw new Exception('Erreur lors de la sélection des cartes: ' . $conn->error);
        }
        
        $drawn_cards = [];
        $card_ids = [];
        
        while ($card = $result->fetch_assoc()) {
            $drawn_cards[] = [
                'id' => $card['id'],
                'name' => $card['name'],
                'image_url' => $card['image_url'],
                'rarity' => 'Commune', // Valeur par défaut puisque la colonne n'existe pas
                'description' => $card['description'] ?? ''
            ];
            $card_ids[] = $card['id'];
        }

        if (count($drawn_cards) < 2) {
            throw new Exception('Il n\'y a pas assez de cartes disponibles. Cartes trouvées: ' . count($drawn_cards));
        }

        // Ajouter les cartes à la collection de l'utilisateur
        foreach ($card_ids as $card_id) {
            // Échapper les valeurs pour éviter les injections SQL
            $userId = $conn->real_escape_string($_SESSION['user_id']);
            $cardId = $conn->real_escape_string($card_id);
            
            // Utiliser une requête directe avec ON DUPLICATE KEY UPDATE
            $query = "INSERT INTO user_cards (user_id, card_id, quantity) 
                      VALUES ($userId, $cardId, 1) 
                      ON DUPLICATE KEY UPDATE quantity = quantity + 1";
            
            if (!$conn->query($query)) {
                throw new Exception('Erreur lors de l\'ajout de la carte à la collection: ' . $conn->error);
            }
        }

        // Mettre à jour le timestamp du dernier tirage
        $userId = $conn->real_escape_string($_SESSION['user_id']);
        if (!$conn->query("UPDATE users SET last_card_draw = NOW() WHERE id = $userId")) {
            throw new Exception('Erreur lors de la mise à jour du dernier tirage: ' . $conn->error);
        }

        // Stocker les cartes tirées en session pour l'affichage
        $_SESSION['last_drawn_cards'] = $drawn_cards;
        
        if (!$conn->commit()) {
            throw new Exception('Erreur lors de la validation de la transaction: ' . $conn->error);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Vous avez reçu 2 nouvelles cartes !',
            'cards' => $drawn_cards
        ]);

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_details' => 'Emplacement: ' . $e->getFile() . ' ligne ' . $e->getLine()
    ]);
}
?>
