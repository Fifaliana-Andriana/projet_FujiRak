<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'controllers/AuthController.php';
require_once 'controllers/DashboardController.php';
require_once 'controllers/UserController.php';

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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $auth->logout(); // confirmé -> destruction de session
    } else {
        $auth->confirmLogout(); // affiche la page "Êtes-vous sûr ?"
    }
    exit();
}

/*
|--------------------------------------------------------------------------
| PROTECTION DES ROUTES
|--------------------------------------------------------------------------
*/

$adminRoutes = [
    'admin/dashboard',
    'admin/stats-json',
    'admin/statistics',
    'admin/statistics-json',
    'admin/users',
    'admin/create-user',
    'admin/edit-user',
    'admin/update-user',
    'admin/delete-user',
    'admin/add-finance',
    'admin/factures',
    'admin/factures/upload',
    'admin/factures/delete',
];

$userRoutes = [
    'user/dashboard',
    'user/stats-json',
    'user/profile',
    'user/update-avatar',
    'user/history',
    'user/add-gain',
    'user/add-perte',
    'user/documents',
];

if (in_array($route, array_merge($adminRoutes, $userRoutes), true) && !isset($_SESSION['user_id'])) {
    header('Location: index.php?route=login');
    exit();
}

if ($route === 'download/facture' && !isset($_SESSION['user_id'])) {
    header('Location: index.php?route=login');
    exit();
}

if (in_array($route, $adminRoutes, true) && ($_SESSION['user_role'] ?? null) !== 'admin') {
    http_response_code(403);
    require 'views/errors/403.php';
    exit();
}

/*
|--------------------------------------------------------------------------
| ROUTES ADMIN
|--------------------------------------------------------------------------
*/

$dashboardController = null;
if (in_array($route, $adminRoutes, true)) {
    $dashboardController = new DashboardController();
}

$userController = null;
if (in_array($route, $userRoutes, true)) {
    $userController = new UserController();
}

switch ($route) {

    case 'admin/dashboard':
        $dashboardController->showAdminDashboard();
        break;

    case 'admin/stats-json':
        $dashboardController->statsJson();
        break;

    case 'admin/statistics':
        $dashboardController->showAdminStatistics();
        break;

    case 'admin/statistics-json':
        $dashboardController->userStatsJson();
        break;

    case 'admin/users':
        $dashboardController->showAdminUsers();
        break;

    case 'admin/create-user':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dashboardController->createUser();
        } else {
            $dashboardController->showCreateUserForm();
        }
        break;

    case 'admin/edit-user':
        $dashboardController->showEditUserForm();
        break;

    case 'admin/update-user':
        $dashboardController->updateUser();
        break;

    case 'admin/delete-user':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dashboardController->deleteUser();
        } else {
            $dashboardController->confirmDeleteUser();
        }
        break;

    case 'admin/add-finance':
        $dashboardController->addFinance();
        break;

    case 'admin/factures':
        $dashboardController->showFactures();
        break;

    case 'admin/factures/upload':
        $dashboardController->uploadFacture();
        break;

    case 'admin/factures/delete':
        $dashboardController->deleteFacture();
        break;

    /*
    |--------------------------------------------------------------------------
    | ROUTES UTILISATEUR
    |--------------------------------------------------------------------------
    */

    case 'user/dashboard':
        $userController->showDashboard();
        break;

    case 'user/stats-json':
        $userController->statsJson();
        break;

    case 'user/profile':
        $userController->showProfile();
        break;

    case 'user/update-avatar':
        $userController->updateAvatar();
        break;

    case 'user/history':
        $userController->showHistory();
        break;

    case 'user/add-gain':
        $userController->addGain();
        break;

    case 'user/add-perte':
        $userController->addPerte();
        break;

    case 'user/documents':
        $userController->showDocuments();
        break;

    case 'download/facture':
        require_once 'controllers/UserController.php';
        (new UserController())->downloadDocument();
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
