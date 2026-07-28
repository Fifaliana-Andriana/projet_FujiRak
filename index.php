<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
|--------------------------------------------------------------------------
| Chargement des contrôleurs
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/controllers/AuthController.php';

/*
|--------------------------------------------------------------------------
| Initialisation
|--------------------------------------------------------------------------
*/

$route = $_GET['route'] ?? 'login';

$auth = new AuthController();

$page = null;

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/

if ($route === 'login') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $auth->login();
    }

    $auth->showLoginForm();
    exit();
}

if ($route === 'logout') {
    $auth->logout();
    exit();
}

/*
|--------------------------------------------------------------------------
| Vérification de connexion
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['user_id'])) {

    header('Location: index.php?route=login');
    exit();
}

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

switch ($route) {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    case 'admin/dashboard':

        if ($_SESSION['user_role'] !== 'admin') {
            $page = 'views/errors/403.php';
            break;
        }

        $page = 'views/admin/dashboard.php';
        break;

    case 'admin/users':

        if ($_SESSION['user_role'] !== 'admin') {
            $page = 'views/errors/403.php';
            break;
        }

        $page = 'views/admin/users.php';
        break;

    case 'admin/create-user':

        if ($_SESSION['user_role'] !== 'admin') {
            $page = 'views/errors/403.php';
            break;
        }

        $page = 'views/admin/create_user.php';
        break;

    case 'admin/edit-user':

        if ($_SESSION['user_role'] !== 'admin') {
            $page = 'views/errors/403.php';
            break;
        }

        $page = 'views/admin/edit_user.php';
        break;

    case 'admin/profile':

        if ($_SESSION['user_role'] !== 'admin') {
            $page = 'views/errors/403.php';
            break;
        }

        $page = 'views/admin/profile.php';
        break;

    case 'admin/statistics':

        if ($_SESSION['user_role'] !== 'admin') {
            $page = 'views/errors/403.php';
            break;
        }

        $page = 'views/admin/statistics.php';
        break;

    /*
    |--------------------------------------------------------------------------
    | Utilisateur
    |--------------------------------------------------------------------------
    */

    case 'user/dashboard':

        $page = 'views/user/dashboard.php';
        break;

    case 'user/profile':

        $page = 'views/user/profile.php';
        break;

    case 'user/history':

        $page = 'views/user/history.php';
        break;

    /*
    |--------------------------------------------------------------------------
    | Erreur 404
    |--------------------------------------------------------------------------
    */

    default:

        http_response_code(404);

        $page = 'views/errors/404.php';

        break;
}

/*
|--------------------------------------------------------------------------
| Chargement du Layout principal
|--------------------------------------------------------------------------
*/

require 'views/layouts/app.php';