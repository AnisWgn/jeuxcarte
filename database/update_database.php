<?php
require_once '../config/database.php';

try {
    // Lire le fichier SQL
    $sql = file_get_contents(__DIR__ . '/update_shop.sql');
    
    // Exécuter les requêtes SQL
    if ($conn->multi_query($sql)) {
        do {
            // Vide les résultats
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->next_result());
        
        echo "La base de données a été mise à jour avec succès !\n";
        echo "Les modifications incluent :\n";
        echo "- Ajout de la colonne 'coins' à la table users\n";
        echo "- Ajout des colonnes 'available_for_purchase' et 'price' à la table cards\n";
        echo "- Création de la table 'user_cards'\n";
        echo "- Mise à jour des cartes pour la boutique\n";
        echo "- Ajout des index pour les performances\n";
    } else {
        throw new Exception("Erreur lors de l'exécution des requêtes SQL : " . $conn->error);
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}

$conn->close();
?> 