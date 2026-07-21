<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Finance.php';

class UserController {
    private $db;
    private $userModel;
    private $financeModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User();
        $this->financeModel = new Finance();
    }

    public function showHome() {
        $userId = $_SESSION['user_id'];
        $period = $_GET['period'] ?? 'month';
        if (!in_array($period, ['day', 'month', 'year'], true)) {
            $period = 'month';
        }

        $totals = $this->financeModel->getUserTotals($userId);
        $trend = $this->financeModel->getUserTrend($userId, $period);
        $transactions = $this->financeModel->getUserTransactionHistory($userId, 8);
        $classe = $_SESSION['user_classe'];
        require_once __DIR__ . '/../views/user/home.php';
    }

    public function showProfile() {
        $user = $this->userModel->findById($_SESSION['user_id']);
        require_once __DIR__ . '/../views/user/profile.php';
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=user/profile');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        if (!$user) {
            $_SESSION['error'] = 'Utilisateur introuvable.';
            header('Location: index.php?route=user/profile');
            exit();
        }

        $uploadPath = __DIR__ . '/../assets/uploads/avatars';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($_FILES['avatar']['type'], $allowed, true)) {
                $_SESSION['error'] = 'Format de fichier non pris en charge. Utilisez JPG, PNG ou WebP.';
                header('Location: index.php?route=user/profile');
                exit();
            }

            $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = 'avatar_' . $userId . '_' . time() . '.' . $extension;
            $destination = $uploadPath . '/' . $filename;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
                $relativePath = 'assets/uploads/avatars/' . $filename;
                $this->userModel->updateAvatar($userId, $relativePath);
                $_SESSION['success'] = 'Photo de profil mise à jour.';
            } else {
                $_SESSION['error'] = 'Impossible de téléverser la photo de profil.';
            }
        } else {
            $_SESSION['error'] = 'Aucun fichier reçu ou erreur pendant l\'upload.';
        }

        header('Location: index.php?route=user/profile');
        exit();
    }

    public function showHistory() {
        $userId = $_SESSION['user_id'];
        $transactions = $this->financeModel->getUserTransactionHistory($userId);
        require_once __DIR__ . '/../views/user/history.php';
    }

    public function addGain() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=user/home');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $amount = floatval($_POST['amount'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $source = trim($_POST['source'] ?? '');
        $date = $_POST['date'] ?? date('Y-m-d');

        if ($amount <= 0) {
            $_SESSION['error'] = 'Le montant doit être supérieur à 0.';
            header('Location: index.php?route=user/home');
            exit();
        }

        if ($this->financeModel->addGain($userId, $amount, $description, $source, $date)) {
            $_SESSION['success'] = 'Gain ajouté avec succès.';
        } else {
            $_SESSION['error'] = 'Échec de l\'ajout du gain.';
        }

        header('Location: index.php?route=user/home');
        exit();
    }

    public function addPerte() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=user/home');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $amount = floatval($_POST['amount'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $categorie = trim($_POST['categorie'] ?? '');
        $date = $_POST['date'] ?? date('Y-m-d');

        if ($amount <= 0) {
            $_SESSION['error'] = 'Le montant doit être supérieur à 0.';
            header('Location: index.php?route=user/home');
            exit();
        }

        if ($this->financeModel->addPerte($userId, $amount, $description, $categorie, $date)) {
            $_SESSION['success'] = 'Perte ajoutée avec succès.';
        } else {
            $_SESSION['error'] = 'Échec de l\'ajout de la perte.';
        }

        header('Location: index.php?route=user/home');
        exit();
    }

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=user/profile');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = 'Tous les champs du mot de passe sont obligatoires.';
            header('Location: index.php?route=user/profile');
            exit();
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'Le nouveau mot de passe et sa confirmation ne correspondent pas.';
            header('Location: index.php?route=user/profile');
            exit();
        }

        $user = $this->userModel->findById($userId);
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            $_SESSION['error'] = 'Mot de passe actuel incorrect.';
            header('Location: index.php?route=user/profile');
            exit();
        }

        if ($this->userModel->changePassword($userId, $newPassword)) {
            $_SESSION['success'] = 'Mot de passe mis à jour avec succès.';
        } else {
            $_SESSION['error'] = 'Impossible de mettre à jour le mot de passe.';
        }

        header('Location: index.php?route=user/profile');
        exit();
    }
}
