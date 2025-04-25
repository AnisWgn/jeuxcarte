<?php
// Démarrer la session au tout début
session_start();

// Vérifier si l'utilisateur est déjà connecté AVANT d'inclure le header
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Inclure la base de données
require_once 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = 'Veuillez remplir tous les champs.';
    } elseif ($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas.';
    } elseif (strlen($password) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères.';
    } else {
        // Vérifier si le nom d'utilisateur existe déjà
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        
        if ($stmt->fetch()) {
            $error = 'Ce nom d\'utilisateur est déjà utilisé.';
        } else {
            // Créer le nouvel utilisateur
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, password, is_admin) VALUES (?, ?, 0)');
            
            if ($stmt->execute([$username, $hashed_password])) {
                $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
            } else {
                $error = 'Une erreur est survenue lors de l\'inscription.';
            }
        }
    }
}

// Inclure le header APRÈS avoir traité la redirection
require_once 'includes/header.php';
?>

<style>
    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 2rem 0;
    }

    .register-card {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        width: 100%;
        max-width: 600px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    .register-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #6e8efb, #a777e3);
    }

    .register-title {
        font-size: 2.5rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 2rem;
        text-align: center;
    }

    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .form-label {
        font-weight: 600;
        color: #34495e;
        margin-bottom: 0.5rem;
    }

    .form-control {
        padding: 0.8rem 1rem;
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #6e8efb;
        box-shadow: 0 0 0 3px rgba(110, 142, 251, 0.1);
    }

    .form-control::placeholder {
        color: #bdc3c7;
    }

    .btn-register {
        background: linear-gradient(135deg, #6e8efb 0%, #a777e3 100%);
        border: none;
        padding: 1rem;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        width: 100%;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .login-link {
        text-align: center;
        margin-top: 2rem;
        color: #7f8c8d;
    }

    .login-link a {
        color: #6e8efb;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .login-link a:hover {
        color: #a777e3;
    }

    .alert {
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: none;
    }

    .alert-danger {
        background-color: #ffe6e6;
        color: #e74c3c;
    }

    .input-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #bdc3c7;
    }

    .password-toggle {
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .password-toggle:hover {
        color: #6e8efb;
    }

    .password-requirements {
        font-size: 0.85rem;
        color: #7f8c8d;
        margin-top: 0.5rem;
    }

    .requirement {
        display: flex;
        align-items: center;
        margin-bottom: 0.3rem;
    }

    .requirement i {
        margin-right: 0.5rem;
        font-size: 0.9rem;
    }

    .requirement.valid {
        color: #2ecc71;
    }

    .requirement.invalid {
        color: #e74c3c;
    }
</style>

<div class="register-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="register-card">
                    <h1 class="register-title">Créer un compte</h1>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="username" class="form-label">Nom d'utilisateur</label>
                                    <div class="position-relative">
                                        <input type="text" 
                                               class="form-control" 
                                               id="username" 
                                               name="username" 
                                               placeholder="Choisissez un nom d'utilisateur" 
                                               required>
                                        <i class="fas fa-user input-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">Mot de passe</label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Créez un mot de passe" 
                                               required>
                                        <i class="fas fa-eye password-toggle input-icon" 
                                           onclick="togglePassword('password')"></i>
                                    </div>
                                    <div class="password-requirements">
                                        <div class="requirement" id="length">
                                            <i class="fas fa-circle"></i>
                                            Au moins 8 caractères
                                        </div>
                                        <div class="requirement" id="uppercase">
                                            <i class="fas fa-circle"></i>
                                            Une majuscule
                                        </div>
                                        <div class="requirement" id="number">
                                            <i class="fas fa-circle"></i>
                                            Un chiffre
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               class="form-control" 
                                               id="confirm_password" 
                                               name="confirm_password" 
                                               placeholder="Confirmez votre mot de passe" 
                                               required>
                                        <i class="fas fa-eye password-toggle input-icon" 
                                           onclick="togglePassword('confirm_password')"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-register">
                            <i class="fas fa-user-plus me-2"></i>Créer mon compte
                        </button>
                    </form>
                    
                    <div class="login-link">
                        <p>Déjà un compte ? <a href="login.php">Connectez-vous</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const icon = passwordInput.nextElementSibling;
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Vérification des exigences du mot de passe
document.getElementById('password').addEventListener('input', function(e) {
    const password = e.target.value;
    
    // Longueur minimale
    document.getElementById('length').classList.toggle('valid', password.length >= 8);
    document.getElementById('length').classList.toggle('invalid', password.length > 0 && password.length < 8);
    
    // Majuscule
    document.getElementById('uppercase').classList.toggle('valid', /[A-Z]/.test(password));
    document.getElementById('uppercase').classList.toggle('invalid', password.length > 0 && !/[A-Z]/.test(password));
    
    // Chiffre
    document.getElementById('number').classList.toggle('valid', /[0-9]/.test(password));
    document.getElementById('number').classList.toggle('invalid', password.length > 0 && !/[0-9]/.test(password));
});
</script>

<?php
require_once 'includes/footer.php';
?> 