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
        throw new Exception('Vous devez être connecté pour consulter vos cartes');
    }

    // Vérifier si l'utilisateur a tiré des cartes récemment
    if (!isset($_SESSION['last_drawn_cards']) || empty($_SESSION['last_drawn_cards'])) {
        // Si pas de cartes en session, récupérer les 2 dernières cartes ajoutées à sa collection
        $userId = $conn->real_escape_string($_SESSION['user_id']);
        $query = "
            SELECT c.id, c.name, c.image_url, c.description 
            FROM cards c
            JOIN user_cards uc ON c.id = uc.card_id
            WHERE uc.user_id = $userId
            ORDER BY uc.id DESC
            LIMIT 2
        ";
        
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception('Erreur lors de la récupération des cartes: ' . $conn->error);
        }
        
        $cards = [];
        while ($card = $result->fetch_assoc()) {
            $cards[] = [
                'id' => $card['id'],
                'name' => $card['name'],
                'image_url' => $card['image_url'],
                'rarity' => 'Commune', // Valeur par défaut puisque la colonne n'existe pas
                'description' => $card['description'] ?? ''
            ];
        }
        
        if (empty($cards)) {
            throw new Exception('Aucune carte récemment tirée trouvée');
        }
        
        // Stocker les cartes en session pour une utilisation ultérieure
        $_SESSION['last_drawn_cards'] = $cards;
    } else {
        $cards = $_SESSION['last_drawn_cards'];
    }
    
    echo json_encode([
        'success' => true,
        'cards' => $cards
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_details' => 'Emplacement: ' . $e->getFile() . ' ligne ' . $e->getLine()
    ]);
} 