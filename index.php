<?php
// index.php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'controllers/AuthController.php';

$route = $_GET['route'] ?? 'login';
$auth = new AuthController();

switch ($route) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->login();
        } else {
            $auth->showLoginForm();
        }
        break;

    case 'logout':
        $auth->logout();
        break;

    case 'user/home':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit();
        }
        require 'views/user/home.php';
        break;

    case 'admin/dashboard':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?route=login');
            exit();
        }
        require 'views/admin/dashboard.php';
        break;

    default:
        $auth->showLoginForm();
        break;
}
?>