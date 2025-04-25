<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inclure la configuration de la base de données
require_once '../config/database.php';

echo "<h1>Diagnostic de l'API draw_cards.php</h1>";

// Vérifier la connexion à la base de données
echo "<h2>1. Vérification de la connexion à la base de données</h2>";
if ($conn->connect_error) {
    echo "<p style='color: red;'>Erreur de connexion : " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color: green;'>Connexion à la base de données OK.</p>";
}

// Vérifier si la table cards existe
echo "<h2>2. Vérification de l'existence de la table cards</h2>";
$result = $conn->query("SHOW TABLES LIKE 'cards'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>La table cards existe.</p>";
} else {
    echo "<p style='color: red;'>La table cards n'existe pas !</p>";
}

// Vérifier la structure de la table cards
echo "<h2>3. Vérification de la structure de la table cards</h2>";
$result = $conn->query("SHOW COLUMNS FROM cards");
if (!$result) {
    echo "<p style='color: red;'>Erreur lors de la vérification de la structure : " . $conn->error . "</p>";
} else {
    echo "<p>Colonnes de la table cards :</p>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . $row['Field'] . " (" . $row['Type'] . ")</li>";
    }
    echo "</ul>";
}

// Vérifier s'il y a des cartes dans la table
echo "<h2>4. Vérification des données dans la table cards</h2>";
$result = $conn->query("SELECT COUNT(*) as count FROM cards");
if (!$result) {
    echo "<p style='color: red;'>Erreur lors du comptage des cartes : " . $conn->error . "</p>";
} else {
    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        echo "<p style='color: green;'>Il y a " . $row['count'] . " cartes dans la table.</p>";
    } else {
        echo "<p style='color: red;'>Il n'y a aucune carte dans la table !</p>";
    }
}

// Tester la requête problématique directement
echo "<h2>5. Test direct de la requête problématique</h2>";
try {
    $query = "SELECT id, name, image_url, description FROM cards ORDER BY RAND() LIMIT 2";
    echo "<p>Requête SQL : " . htmlspecialchars($query) . "</p>";
    
    // D'abord sans prepare
    $result = $conn->query($query);
    if (!$result) {
        echo "<p style='color: red;'>Erreur lors de l'exécution directe : " . $conn->error . "</p>";
    } else {
        echo "<p style='color: green;'>Exécution directe réussie !</p>";
        echo "<p>Résultats :</p>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Nom</th><th>URL Image</th><th>Description</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['image_url']) . "</td>";
            echo "<td>" . htmlspecialchars($row['description'] ?? '') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Ensuite avec prepare
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "<p style='color: red;'>Erreur lors de la préparation : " . $conn->error . "</p>";
    } else {
        echo "<p style='color: green;'>Préparation réussie !</p>";
        if (!$stmt->execute()) {
            echo "<p style='color: red;'>Erreur lors de l'exécution : " . $stmt->error . "</p>";
        } else {
            echo "<p style='color: green;'>Exécution réussie !</p>";
            $result = $stmt->get_result();
            if (!$result) {
                echo "<p style='color: red;'>Erreur lors de la récupération du résultat : " . $stmt->error . "</p>";
            } else {
                echo "<p style='color: green;'>Récupération du résultat réussie !</p>";
            }
            $stmt->close();
        }
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Exception : " . $e->getMessage() . "</p>";
}

// Vérifier les colonnes de la table user_cards
echo "<h2>6. Vérification de la table user_cards</h2>";
$result = $conn->query("SHOW TABLES LIKE 'user_cards'");
if ($result->num_rows == 0) {
    echo "<p style='color: red;'>La table user_cards n'existe pas !</p>";
} else {
    echo "<p style='color: green;'>La table user_cards existe.</p>";
    
    $result = $conn->query("SHOW COLUMNS FROM user_cards");
    if (!$result) {
        echo "<p style='color: red;'>Erreur lors de la vérification de la structure : " . $conn->error . "</p>";
    } else {
        echo "<p>Colonnes de la table user_cards :</p>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['Field'] . " (" . $row['Type'] . ")</li>";
        }
        echo "</ul>";
    }
}

// Tester l'accès aux dernières cartes tirées
echo "<h2>7. Test de l'accès aux dernières cartes tirées</h2>";
if (!isset($_SESSION)) {
    session_start();
}

echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>User ID en session: " . ($_SESSION['user_id'] ?? 'Non défini') . "</p>";

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    $result = $conn->query("SELECT * FROM user_cards WHERE user_id = $userId ORDER BY id DESC LIMIT 2");
    if (!$result) {
        echo "<p style='color: red;'>Erreur lors de la récupération des cartes : " . $conn->error . "</p>";
    } else {
        if ($result->num_rows > 0) {
            echo "<p style='color: green;'>Dernières cartes trouvées :</p>";
            echo "<table border='1'>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $key => $value) {
                    echo "<td>" . htmlspecialchars($key) . ": " . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>Aucune carte trouvée pour cet utilisateur.</p>";
        }
    }
} else {
    echo "<p style='color: orange;'>Vous n'êtes pas connecté, impossible de tester l'accès aux cartes.</p>";
}

// Recommandations
echo "<h2>8. Recommandations</h2>";
echo "<ul>";
echo "<li>Vérifiez que votre serveur MySQL accepte les requêtes préparées.</li>";
echo "<li>Assurez-vous que les tables 'cards' et 'user_cards' ont toutes les colonnes nécessaires.</li>";
echo "<li>Si la requête directe fonctionne mais pas la requête préparée, essayez d'utiliser une requête directe.</li>";
echo "<li>Vérifiez les droits de l'utilisateur de la base de données.</li>";
echo "</ul>";

?> 