<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'controllers/AuthController.php';

$route = $_GET['route'] ?? 'login';

$auth = new AuthController();

/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION
|--------------------------------------------------------------------------
*/

if ($route === 'login') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $auth->login();
    } else {
        $auth->showLoginForm();
    }

    exit();
}

if ($route === 'logout') {
    $auth->logout();
    exit();
}

/*
|--------------------------------------------------------------------------
| PROTECTION DES ROUTES
|--------------------------------------------------------------------------
*/

$protectedRoutes = [

    // Admin
    'admin/dashboard',
    'admin/users',
    'admin/create-user',
    'admin/edit-user',
    'admin/profile',
    'admin/statistics',

    // Utilisateur
    'user/dashboard',
    'user/profile'

];

if (in_array($route, $protectedRoutes) && !isset($_SESSION['user_id'])) {

    header('Location: index.php?route=login');
    exit();

}

/*
|--------------------------------------------------------------------------
| ROUTES ADMIN
|--------------------------------------------------------------------------
*/

switch ($route) {

    case 'admin/dashboard':

        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?route=login');
            exit();
        }

        require 'views/admin/dashboard.php';
        break;

    case 'admin/users':

        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?route=login');
            exit();
        }

        require 'views/admin/users.php';
        break;

    case 'admin/create-user':

        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?route=login');
            exit();
        }

        require 'views/admin/create_user.php';
        break;

    case 'admin/edit-user':

        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?route=login');
            exit();
        }

        require 'views/admin/edit_user.php';
        break;

    case 'admin/profile':

        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?route=login');
            exit();
        }

        require 'views/admin/profile.php';
        break;

    case 'admin/statistics':

        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?route=login');
            exit();
        }

        require 'views/admin/statistics.php';
        break;

    /*
    |--------------------------------------------------------------------------
    | ROUTES UTILISATEUR
    |--------------------------------------------------------------------------
    */

    case 'user/dashboard':

        require 'views/user/dashboard.php';
        break;

    case 'user/profile':

        require 'views/user/profile.php';
        break;

    /*
    |--------------------------------------------------------------------------
    | PAGE INTROUVABLE
    |--------------------------------------------------------------------------
    */

    default:

        http_response_code(404);
        require 'views/errors/404.php';
        break;
}