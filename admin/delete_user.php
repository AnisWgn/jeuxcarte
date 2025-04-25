<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/portfolio/project/Jeux_carte/includes/header.php';

// Vérifier si l'utilisateur est admin
if (!isAdmin()) {
    header('Location: ../index.php');
    exit();
}

$user_id = $_GET['id'] ?? null;

if (!$user_id) {
    header('Location: users.php');
    exit();
}

try {
    // Vérifier si l'utilisateur existe
    $stmt = $pdo->prepare('SELECT id FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    
    if (!$stmt->fetch()) {
        $_SESSION['error'] = 'L\'utilisateur n\'existe pas.';
        header('Location: users.php');
        exit();
    }

    // Supprimer les entrées dans user_cards (grâce à ON DELETE CASCADE, voir database)
    // Supprimer l'utilisateur
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$user_id]);

    $_SESSION['success'] = 'L\'utilisateur a été supprimée avec succès.';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Une erreur est survenue lors de la suppression de l\'utilisateur.'; //Si il y a une erreur, on affiche un message d'erreur
}

header('Location: users.php');
exit(); 