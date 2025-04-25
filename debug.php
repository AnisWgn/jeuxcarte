<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure la configuration de la base de données
require_once 'config/database.php';

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

echo "<h1>Page de Débogage</h1>";

if (!isLoggedIn()) {
    echo "<p>Vous n'êtes pas connecté. Certaines fonctionnalités ne seront pas disponibles.</p>";
} else {
    echo "<p>Vous êtes connecté en tant que: " . htmlspecialchars($_SESSION['username']) . " (ID: " . $_SESSION['user_id'] . ")</p>";
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
        echo "<p>Vous avez les droits administrateur.</p>";
    }
}

// Informations sur la connexion à la base de données
echo "<h2>Informations sur la Base de Données</h2>";
echo "<p>Hôte: " . DB_HOST . "</p>";
echo "<p>Base de données: " . DB_NAME . "</p>";
echo "<p>Utilisateur: " . DB_USER . "</p>";

// Tester la structure de la base de données
echo "<h2>Structure de la Base de Données</h2>";

// Afficher les tables disponibles
$tables_result = $conn->query("SHOW TABLES");
if ($tables_result) {
    echo "<h3>Tables disponibles:</h3>";
    echo "<ul>";
    while ($table = $tables_result->fetch_array()) {
        echo "<li>" . htmlspecialchars($table[0]) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Erreur lors de la récupération des tables: " . $conn->error . "</p>";
}

// Test direct des requêtes problématiques
echo "<h2>Test direct des requêtes problématiques</h2>";
echo "<h3>1. Requête de tirage de cartes:</h3>";
$query = "SELECT id, name, image_url, description FROM cards ORDER BY RAND() LIMIT 2";
echo "<p>Requête: <code>" . htmlspecialchars($query) . "</code></p>";
$result = $conn->query($query);

if (!$result) {
    echo "<p style='color: red;'>Erreur: " . $conn->error . "</p>";
} else {
    echo "<p style='color: green;'>Requête directe réussie</p>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nom</th><th>Image</th><th>Description</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['image_url'] . "</td>";
        echo "<td>" . ($row['description'] ?? '') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<h3>2. Test du prepare sur la même requête:</h3>";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo "<p style='color: red;'>Erreur lors de la préparation: " . $conn->error . "</p>";
} else {
    echo "<p style='color: green;'>Préparation réussie</p>";
    if (!$stmt->execute()) {
        echo "<p style='color: red;'>Erreur lors de l'exécution: " . $stmt->error . "</p>";
    } else {
        echo "<p style='color: green;'>Exécution réussie</p>";
    }
    $stmt->close();
}

echo "<h3>3. Test d'insertion avec ON DUPLICATE KEY UPDATE:</h3>";
if (isLoggedIn()) {
    $userId = $conn->real_escape_string($_SESSION['user_id']);
    $cardId = 1; // Premier ID de carte, à ajuster si nécessaire
    
    $query = "INSERT INTO user_cards (user_id, card_id, quantity) 
              VALUES ($userId, $cardId, 1) 
              ON DUPLICATE KEY UPDATE quantity = quantity + 1";
    
    echo "<p>Requête: <code>" . htmlspecialchars($query) . "</code></p>";
    
    if (!$conn->query($query)) {
        echo "<p style='color: red;'>Erreur: " . $conn->error . "</p>";
    } else {
        echo "<p style='color: green;'>Insertion réussie</p>";
    }
} else {
    echo "<p style='color: orange;'>Vous devez être connecté pour tester cette requête</p>";
}

// Tester les fonctionnalités de l'API
echo "<h2>Test des API</h2>";

// Formulaire pour tester l'API draw_cards.php
echo "<h3>Tester l'API draw_cards.php</h3>";
echo "<form action='#' method='post'>";
echo "<input type='hidden' name='test_api' value='draw_cards'>";
echo "<button type='submit'>Tester draw_cards.php</button>";
echo "</form>";

// Formulaire pour tester l'API get_drawn_cards.php
echo "<h3>Tester l'API get_drawn_cards.php</h3>";
echo "<form action='#' method='post'>";
echo "<input type='hidden' name='test_api' value='get_drawn_cards'>";
echo "<button type='submit'>Tester get_drawn_cards.php</button>";
echo "</form>";

// Traitement des tests d'API
if (isset($_POST['test_api'])) {
    $api = $_POST['test_api'];
    
    echo "<h3>Résultat du test pour " . htmlspecialchars($api) . ".php</h3>";
    
    // Requête cURL pour appeler l'API
    $ch = curl_init("http://" . $_SERVER['HTTP_HOST'] . "/portfolio/project/Jeux_carte/api/" . $api . ".php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, session_name() . "=" . session_id());
    
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    // Afficher les détails de la requête
    echo "<h4>Détails de la requête:</h4>";
    echo "<pre>";
    echo "URL: " . $info['url'] . "\n";
    echo "HTTP Code: " . $info['http_code'] . "\n";
    echo "Content Type: " . $info['content_type'] . "\n";
    echo "Total Time: " . $info['total_time'] . " secondes\n";
    
    if ($error) {
        echo "Erreur cURL: " . htmlspecialchars($error) . "\n";
    }
    
    echo "</pre>";
    
    // Afficher la réponse
    echo "<h4>Réponse brute:</h4>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    
    // Si la réponse est du JSON, l'afficher de manière formatée
    $json = json_decode($response, true);
    if ($json !== null) {
        echo "<h4>Réponse JSON décodée:</h4>";
        echo "<pre>";
        print_r($json);
        echo "</pre>";
    }
}

// Vérifier les variables de session
echo "<h2>Variables de Session</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>
