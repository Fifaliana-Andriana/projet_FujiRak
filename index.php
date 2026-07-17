<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$route = $_GET['route'] ?? 'login';

switch ($route) {

    case 'login':
        require 'views/auth/login.php';
        break;

    case 'verify':
        require 'views/auth/verify.php';
        break;

    case 'admin/dashboard':
        require 'views/admin/dashboard.php';
        break;

    case 'admin/users':
        require 'views/admin/users.php';
        break;

    case 'user/home':
        require 'views/user/home.php';
        break;

    default:
        echo "<h1>404 - Page introuvable</h1>";
}