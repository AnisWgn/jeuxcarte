<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'trading_card_game');

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        throw new Exception("Erreur de connexion : " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
} catch(Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?> 