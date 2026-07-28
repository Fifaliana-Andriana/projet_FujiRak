<?php
// controllers/AuthController.php
require_once __DIR__ . '/../models/User.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // Afficher le formulaire de login
    public function showLoginForm()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard();
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Traiter la connexion
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=login');
            exit();
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email et mot de passe requis';
            header('Location: index.php?route=login');
            exit();
        }

        $result = $this->userModel->verifyPassword($email, $password);

        if ($result['success']) {
            $user = $result['user'];

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_classe'] = $user['classe'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_photo'] = $user['photo'] ?? 'default.png';

            $_SESSION['success'] = 'Bienvenue, ' . $user['username'] . ' !';

            $this->redirectToDashboard();
        }

        $_SESSION['error'] = $result['message'];
        header('Location: index.php?route=login');
        exit();
    }

    private function redirectToDashboard()
    {
        if ($_SESSION['user_role'] === 'admin') {
            header('Location: index.php?route=admin/dashboard');
        } else {
            header('Location: index.php?route=user/dashboard');
        }
        exit();
    }

    // Étape 1 : afficher la page de confirmation de déconnexion
    public function confirmLogout()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit();
        }
        require_once __DIR__ . '/../views/auth/logout.php';
    }

    // Étape 2 : déconnexion effective (après confirmation, en POST)
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: index.php?route=login');
        exit();
    }
}
