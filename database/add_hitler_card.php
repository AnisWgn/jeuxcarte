<?php
require_once '../config/database.php';

try {
    // Lire le fichier SQL
    $sql = file_get_contents(__DIR__ . '/add_hitler_card.sql');
    
    // Exécuter la requête SQL
    if ($conn->query($sql)) {
        echo "La carte Hitler a été ajoutée avec succès !\n";
    } else {
        throw new Exception("Erreur lors de l'ajout de la carte : " . $conn->error);
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}

$conn->close();
?> 