<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'controllers/AuthController.php';

$route = $_GET['route'] ?? 'login';

$auth = new AuthController();

switch ($route) {
    case 'login':
        $auth->showLoginForm();
        break;

    case 'login-submit':
        $auth->login();
        break;

    case 'register':
        $auth->showRegisterForm();
        break;

    case 'submit-register':
        $auth->submitRegistrationRequest();
        break;

    case 'send-code':
        $auth->sendCode();
        break;
    
    case 'verify':
        $auth->showVerifyForm();
        break;
    
    case 'check-code':
        $auth->verifyCode();
        break;

    case 'logout':
        $auth->logout();
        break;

    // ====== ADMIN ======

    case 'admin/dashboard':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit();
        }
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?route=user/home');
            exit();
        }
        $auth->showAdminDashboard();
        break;
    
    case 'admin/users':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit();
        }
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?route=user/home');
            exit();
        }
        $auth->showAdminUsers();
        break;

    case 'admin/approve-request':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit();
        }
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?route=user/home');
            exit();
        }
        $auth->approveRegistrationRequest();
        break;

    case 'admin/gains':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit();
        }
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?route=user/home');
            exit();
        }
        require 'views/admin/gains.php';
        break;
    
    case 'admin/pertes':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit();
        }
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?route=user/home');
            exit();
        }
        require 'views/admin/pertes.php';
        break;
    
    case 'admin/statistics':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit();
        }
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?route=user/home');
            exit();
        }
        require 'views/admin/statistics.php';
        break;

    // ====== UTILISATEUR ======
    case 'user/home':
    case 'home':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit();
        }
        require 'views/user/home.php';
        break;

    case 'user/history':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit();
        }
        require 'views/user/history.php';
        break;

    case 'user/profile':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit();
        }
        require 'views/user/profile.php';
        break;

    // ====== PAR DÉFAUT → LOGIN ======
    default:
        $auth->showLoginForm();
        break;
}
?>