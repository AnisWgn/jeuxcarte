<?php
require_once '../config/database.php';

try {
    // Lire le fichier SQL
    $sql = file_get_contents(__DIR__ . '/update_coins.sql');
    
    // Exécuter les requêtes SQL
    if ($conn->multi_query($sql)) {
        do {
            // Vide les résultats
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->next_result());
        
        echo "La colonne coins a été ajoutée et initialisée avec succès !\n";
        echo "Tous les utilisateurs ont maintenant 1000 pièces.\n";
    } else {
        throw new Exception("Erreur lors de l'exécution des requêtes SQL : " . $conn->error);
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}

$conn->close();
?> 