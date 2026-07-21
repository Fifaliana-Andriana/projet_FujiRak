<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Finance.php';

class DashboardController {
    private $db;
    private $userModel;
    private $financeModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User();
        $this->financeModel = new Finance();
    }

    public function showAdminDashboard() {
        $period = $_GET['period'] ?? 'month';
        if (!in_array($period, ['day', 'month', 'year'], true)) {
            $period = 'month';
        }

        $totalUsers = $this->userModel->getTotalUsers();
        $classCounts = $this->userModel->getClassCounts();
        $registrationStats = $this->userModel->getRegistrationStats($period);
        $summary = $this->financeModel->getTotalGainsLosses();
        $userClassCounts = $classCounts;
        $periodLabel = $period;

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function showAdminUsers() {
        $users = $this->userModel->getAllUsers();
        $pendingRequests = $this->userModel->getPendingRegistrationRequests();
        $classCounts = $this->userModel->getClassCounts();
        require_once __DIR__ . '/../views/admin/users.php';
    }

    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=admin/users');
            exit();
        }

        $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $classe = $_POST['classe'] ?? 'simple';
        $role = $_POST['role'] ?? 'user';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($username) || empty($password) || empty($passwordConfirm)) {
            $_SESSION['error'] = 'Tous les champs obligatoires doivent être remplis pour créer un utilisateur.';
            header('Location: index.php?route=admin/users');
            exit();
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = 'Les mots de passe ne correspondent pas.';
            header('Location: index.php?route=admin/users');
            exit();
        }

        if ($this->userModel->findByEmail($email)) {
            $_SESSION['error'] = 'Un compte existe déjà pour cet email.';
            header('Location: index.php?route=admin/users');
            exit();
        }

        if ($this->userModel->findByUsername($username)) {
            $_SESSION['error'] = 'Ce nom d\'utilisateur est déjà utilisé.';
            header('Location: index.php?route=admin/users');
            exit();
        }

        $data = [
            'username' => $username,
            'nom' => '',
            'prenom' => '',
            'email' => $email,
            'password' => $password,
            'classe' => in_array($classe, ['simple', 'gold', 'plus'], true) ? $classe : 'simple',
            'role' => $role === 'admin' ? 'admin' : 'user'
        ];

        if ($this->userModel->create($data)) {
            $_SESSION['success'] = 'Utilisateur créé avec succès.';
        } else {
            $_SESSION['error'] = 'Impossible de créer l\'utilisateur.';
        }

        header('Location: index.php?route=admin/users');
        exit();
    }

    public function updateUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=admin/users');
            exit();
        }

        $userId = intval($_POST['user_id'] ?? 0);
        if ($userId <= 0) {
            $_SESSION['error'] = 'Utilisateur invalide.';
            header('Location: index.php?route=admin/users');
            exit();
        }

        $password = $_POST['password'] ?? '';
        $classe = $_POST['classe'] ?? 'simple';
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $username = trim($_POST['username'] ?? '');
        $email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($username)) {
            $_SESSION['error'] = 'Email et nom d\'utilisateur sont obligatoires.';
            header('Location: index.php?route=admin/users');
            exit();
        }

        $existingByEmail = $this->userModel->findByEmail($email);
        if ($existingByEmail && $existingByEmail['id'] != $userId) {
            $_SESSION['error'] = 'Cet email est déjà utilisé par un autre compte.';
            header('Location: index.php?route=admin/users');
            exit();
        }

        $existingByUsername = $this->userModel->findByUsername($username);
        if ($existingByUsername && $existingByUsername['id'] != $userId) {
            $_SESSION['error'] = 'Ce nom d\'utilisateur est déjà utilisé par un autre compte.';
            header('Location: index.php?route=admin/users');
            exit();
        }

        $data = [
            'username' => $username,
            'email' => $email,
            'classe' => in_array($classe, ['simple', 'gold', 'plus'], true) ? $classe : 'simple',
            'role' => $_POST['role'] === 'admin' ? 'admin' : 'user',
            'is_active' => $isActive,
            'nom' => $_POST['nom'] ?? '',
            'prenom' => $_POST['prenom'] ?? ''
        ];

        if (!$this->userModel->updateUser($userId, $data)) {
            $_SESSION['error'] = 'Échec de la mise à jour du profil.';
            header('Location: index.php?route=admin/users');
            exit();
        }

        if (!empty($password)) {
            $this->userModel->changePassword($userId, $password);
            $_SESSION['success'] = 'Mot de passe et informations mises à jour.';
        } else {
            $_SESSION['success'] = 'Informations mises à jour.';
        }

        header('Location: index.php?route=admin/users');
        exit();
    }

    public function addFinance() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=admin/users');
            exit();
        }

        $userId = intval($_POST['user_id'] ?? 0);
        $type = $_POST['type'] ?? 'gain';
        $amount = floatval($_POST['amount'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $date = $_POST['date'] ?? date('Y-m-d');
        $meta = trim($_POST['meta'] ?? '');

        if ($userId <= 0 || $amount <= 0 || !in_array($type, ['gain', 'perte'], true)) {
            $_SESSION['error'] = 'Les informations de transaction ne sont pas valides.';
            header('Location: index.php?route=admin/users');
            exit();
        }

        if ($type === 'gain') {
            $result = $this->financeModel->addGain($userId, $amount, $description, $meta, $date);
        } else {
            $result = $this->financeModel->addPerte($userId, $amount, $description, $meta, $date);
        }

        if ($result) {
            $_SESSION['success'] = ucfirst($type) . ' ajouté avec succès.';
        } else {
            $_SESSION['error'] = 'Impossible d\'ajouter la transaction.';
        }

        header('Location: index.php?route=admin/users');
        exit();
    }
}
