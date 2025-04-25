<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/portfolio/project/Jeux_carte/includes/header.php';

// Vérifier si l'utilisateur est admin
if (!isAdmin()) {
    header('Location: ../index.php');
    exit();
}

$card_id = $_GET['id'] ?? null;

if (!$card_id) {
    header('Location: cards.php');
    exit();
}

try {
    // Vérifier si la carte existe
    $stmt = $pdo->prepare('SELECT id FROM cards WHERE id = ?');
    $stmt->execute([$card_id]);
    
    if (!$stmt->fetch()) {
        $_SESSION['error'] = 'La carte n\'existe pas.';
        header('Location: cards.php');
        exit();
    }

    // Supprimer les entrées dans user_cards (grâce à ON DELETE CASCADE, voir database)
    // Supprimer la carte
    $stmt = $pdo->prepare('DELETE FROM cards WHERE id = ?');
    $stmt->execute([$card_id]);

    $_SESSION['success'] = 'La carte a été supprimée avec succès.';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Une erreur est survenue lors de la suppression de la carte.'; //Si il y a une erreur, on affiche un message d'erreur
}

header('Location: cards.php');
exit(); 