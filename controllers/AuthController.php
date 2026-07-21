<?php
// controllers/AuthController.php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Afficher le formulaire de login
    public function showLoginForm() {
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?route=user/home');
            exit();
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Traiter la connexion
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=login');
            exit();
        }

        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email et mot de passe requis';
            header('Location: index.php?route=login');
            exit();
        }

        $result = $this->userModel->verifyPassword($email, $password);

        if ($result['success']) {
            $user = $result['user'];
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_classe'] = $user['classe'];
            $_SESSION['user_role'] = $user['role'];

            $_SESSION['success'] = 'Bienvenue !';

            if ($user['role'] === 'admin') {
                header('Location: index.php?route=admin/dashboard');
            } else {
                header('Location: index.php?route=user/home');
            }
            exit();
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: index.php?route=login');
            exit();
        }
    }

    // Déconnexion
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php?route=login');
        exit();
    }
}
?>