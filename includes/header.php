<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fonction pour vérifier si l'utilisateur est admin
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PokeGeo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #2d3748;
        }
        
        .navbar {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 0.7rem 0;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }
        
        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.6rem;
            letter-spacing: -0.5px;
        }
        
        .navbar-brand i {
            color: #ffd700;
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.1));
        }
        
        .nav-link {
            color: white !important;
            font-weight: 500;
            padding: 0.8rem 1rem;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0 3px;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(31, 41, 55, 0.1);
            padding: 0.8rem 0;
            margin-top: 10px;
        }
        
        .dropdown-item {
            padding: 0.7rem 1.5rem;
            font-weight: 500;
            color: #4b5563;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .dropdown-item:hover {
            background-color: #f3f4f6;
            color: #4f46e5;
            padding-left: 1.8rem;
        }
        
        .dropdown-item i {
            width: 1.2rem;
            text-align: center;
            margin-right: 8px;
            color: #6d7280;
        }
        
        .dropdown-item:hover i {
            color: #4f46e5;
        }
        
        .account-btn, .logout-btn {
            color: white !important;
            padding: 10px 16px;
            border-radius: 8px;
            margin-left: 10px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .account-btn {
            background-color: rgba(255,255,255,0.15);
        }
        
        .account-btn:hover {
            background-color: rgba(255,255,255,0.25);
            transform: translateY(-2px);
        }
        
        .logout-btn {
            background-color: rgba(248, 113, 113, 0.6);
        }
        
        .logout-btn:hover {
            background-color: rgba(248, 113, 113, 0.8);
            transform: translateY(-2px);
        }
        
        .login-btn, .register-btn {
            color: white !important;
            text-decoration: none;
            padding: 10px 16px;
            margin-left: 10px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .login-btn {
            background-color: rgba(255,255,255,0.15);
        }
        
        .login-btn:hover {
            background-color: rgba(255,255,255,0.25);
            transform: translateY(-2px);
        }
        
        .register-btn {
            background-color: white;
            color: #4f46e5 !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }
        
        .coin-display {
            color: white;
            margin-right: 16px;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .coin-display i {
            color: #ffd700;
            margin-right: 8px;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1));
        }
        
        .navbar-toggler {
            border: none;
            color: white;
            padding: 8px;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
            outline: none;
        }
        
        .container {
            position: relative;
        }
        
        @media (max-width: 992px) {
            .navbar-collapse {
                background-color: #4f46e5;
                border-radius: 12px;
                padding: 1rem;
                margin-top: 1rem;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            }
            
            .account-btn, .logout-btn, .login-btn, .register-btn {
                display: block;
                margin: 8px 0;
                text-align: center;
                justify-content: center;
            }
            
            .coin-display {
                margin: 10px 0;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/portfolio/project/Jeux_carte/index.php">
                <i class="fas fa-globe-americas me-2"></i>PokeGeo
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/portfolio/project/Jeux_carte/cards.php">
                            <i class="fas fa-th me-1"></i> Cartes
                        </a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/portfolio/project/Jeux_carte/collection.php">
                                <i class="fas fa-book-open me-1"></i> Collection
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/portfolio/project/Jeux_carte/shop.php">
                                <i class="fas fa-shopping-cart me-1"></i> Boutique
                            </a>
                        </li>
                        <?php if (isAdmin()): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-cog me-1"></i> Admin
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                    <li>
                                        <a class="dropdown-item" href="/portfolio/project/Jeux_carte/admin/users.php">
                                            <i class="fas fa-users"></i> Utilisateurs
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/portfolio/project/Jeux_carte/admin/cards.php">
                                            <i class="fas fa-th-list"></i> Cartes
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/portfolio/project/Jeux_carte/account.php">
                                            <i class="fas fa-user-cog"></i> Gestion du Compte
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <div class="navbar-nav align-items-center">
                    <?php if (isLoggedIn()): ?>
                        <?php if (isset($_SESSION['coins'])): ?>
                            <div class="coin-display">
                                <i class="fas fa-coins"></i>
                                <?php echo number_format($_SESSION['coins']); ?>
                            </div>
                        <?php endif; ?>
                        <div class="d-flex">
                            <a class="account-btn" href="/portfolio/project/Jeux_carte/account.php">
                                <i class="fas fa-user-edit me-2"></i> Mon Compte
                            </a>
                            <a class="logout-btn" href="/portfolio/project/Jeux_carte/logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="d-flex">
                            <a class="login-btn" href="/portfolio/project/Jeux_carte/login.php">
                                <i class="fas fa-sign-in-alt me-2"></i> Connexion
                            </a>
                            <a class="register-btn" href="/portfolio/project/Jeux_carte/register.php">
                                <i class="fas fa-user-plus me-2"></i> Inscription
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mt-4"> 