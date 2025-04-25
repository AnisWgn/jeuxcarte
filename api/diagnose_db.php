<?php
// Désactiver l'affichage des erreurs HTML
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inclure la configuration de la base de données
require_once '../config/database.php';

echo "<h1>Diagnostic de la base de données</h1>";

try {
    // Obtenir la structure de la table cards
    $result = $conn->query("SHOW COLUMNS FROM cards");
    
    if (!$result) {
        throw new Exception("Erreur lors de la récupération de la structure de la table cards: " . $conn->error);
    }
    
    echo "<h2>Structure de la table 'cards'</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Nom du champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th><th>Extra</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Obtenir la structure de la table user_cards
    $result = $conn->query("SHOW COLUMNS FROM user_cards");
    
    if (!$result) {
        throw new Exception("Erreur lors de la récupération de la structure de la table user_cards: " . $conn->error);
    }
    
    echo "<h2>Structure de la table 'user_cards'</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Nom du champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th><th>Extra</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Afficher quelques enregistrements de la table cards
    $result = $conn->query("SELECT * FROM cards LIMIT 5");
    
    if (!$result) {
        throw new Exception("Erreur lors de la récupération des données de cards: " . $conn->error);
    }
    
    echo "<h2>Aperçu des données cards (5 premiers enregistrements)</h2>";
    
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        // En-têtes de colonnes
        $row = $result->fetch_assoc();
        echo "<tr>";
        foreach ($row as $key => $value) {
            echo "<th>" . htmlspecialchars($key) . "</th>";
        }
        echo "</tr>";
        
        // Réinitialiser le pointeur de résultat
        $result->data_seek(0);
        
        // Données
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Aucune donnée trouvée dans la table 'cards'.</p>";
    }
    
    // Afficher quelques enregistrements de la table user_cards
    $result = $conn->query("SELECT * FROM user_cards LIMIT 5");
    
    if (!$result) {
        throw new Exception("Erreur lors de la récupération des données de user_cards: " . $conn->error);
    }
    
    echo "<h2>Aperçu des données user_cards (5 premiers enregistrements)</h2>";
    
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        // En-têtes de colonnes
        $row = $result->fetch_assoc();
        echo "<tr>";
        foreach ($row as $key => $value) {
            echo "<th>" . htmlspecialchars($key) . "</th>";
        }
        echo "</tr>";
        
        // Réinitialiser le pointeur de résultat
        $result->data_seek(0);
        
        // Données
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Aucune donnée trouvée dans la table 'user_cards'.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur: " . $e->getMessage() . "</p>";
}
?> 