<?php
// controllers/UserController.php — espace Utilisateur

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Finance.php';
require_once __DIR__ . '/../models/Facture.php';

class UserController
{
    private $userModel;
    private $financeModel;
    private $factureModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->financeModel = new Finance();
        $this->factureModel = new Facture();
    }

    public function showDashboard()
    {
        $userId = $_SESSION['user_id'];
        $period = $_GET['period'] ?? 'month';
        if (!in_array($period, ['day', 'month', 'year'], true)) {
            $period = 'month';
        }

        $totals = $this->financeModel->getUserTotals($userId);
        $trend = $this->financeModel->getUserTrend($userId, $period);
        $transactions = $this->financeModel->getUserTransactionHistory($userId, 8);
        $classe = $_SESSION['user_classe'];
        $periodLabel = $period;

        $page = 'views/user/dashboard.php';
        require __DIR__ . '/../views/layouts/app.php';
    }

    public function statsJson()
    {
        $userId = $_SESSION['user_id'];
        $period = $_GET['period'] ?? 'month';
        if (!in_array($period, ['day', 'month', 'year'], true)) {
            $period = 'month';
        }
        header('Content-Type: application/json');
        echo json_encode($this->financeModel->getUserTrend($userId, $period));
        exit();
    }

    public function showProfile()
    {
        $user = $this->userModel->findById($_SESSION['user_id']);
        $transactions = $this->financeModel->getUserTransactionHistory($_SESSION['user_id'], 10);

        $page = 'views/profile/profile.php';
        require __DIR__ . '/../views/layouts/app.php';
    }

    // Seul champ que l'utilisateur peut modifier lui-même : la photo de profil
    public function updateAvatar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=user/profile');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $uploadPath = __DIR__ . '/../assets/uploads/avatars';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = "Aucun fichier reçu ou erreur pendant l'upload.";
            header('Location: index.php?route=user/profile');
            exit();
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $realMime = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
        finfo_close($finfo);

        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (!isset($allowed[$realMime])) {
            $_SESSION['error'] = 'Format non pris en charge. Utilise JPG, PNG ou WebP.';
            header('Location: index.php?route=user/profile');
            exit();
        }

        if ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
            $_SESSION['error'] = 'Fichier trop volumineux (2 Mo maximum).';
            header('Location: index.php?route=user/profile');
            exit();
        }

        $filename = 'avatar_' . $userId . '_' . time() . '.' . $allowed[$realMime];
        $destination = $uploadPath . '/' . $filename;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
            $relativePath = 'assets/uploads/avatars/' . $filename;
            $this->userModel->updateAvatar($userId, $relativePath);
            $_SESSION['user_photo'] = $relativePath;
            $_SESSION['success'] = 'Photo de profil mise à jour.';
        } else {
            $_SESSION['error'] = 'Impossible de téléverser la photo de profil.';
        }

        header('Location: index.php?route=user/profile');
        exit();
    }

    public function showDocuments()
    {
        $factures = $this->factureModel->getByUser($_SESSION['user_id']);

        $page = 'views/user/documents.php';
        require __DIR__ . '/../views/layouts/app.php';
    }

    // Téléchargement contrôlé : un utilisateur ne peut télécharger que SES propres documents
    public function downloadDocument()
    {
        $id = intval($_GET['id'] ?? 0);
        $facture = $this->factureModel->findById($id);

        $isOwner = $facture && $facture['user_id'] == $_SESSION['user_id'];
        $isAdmin = ($_SESSION['user_role'] ?? null) === 'admin';

        if (!$facture || (!$isOwner && !$isAdmin)) {
            http_response_code(403);
            require __DIR__ . '/../views/errors/403.php';
            exit();
        }

        $filePath = __DIR__ . '/../assets/uploads/factures/' . $facture['stored_name'];
        if (!file_exists($filePath)) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            exit();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($facture['original_name']) . '"');
        header('Content-Length: ' . filesize($filePath));
        header('X-Content-Type-Options: nosniff');
        readfile($filePath);
        exit();
    }

    public function showHistory()
    {
        $userId = $_SESSION['user_id'];
        $transactions = $this->financeModel->getUserTransactionHistory($userId);

        $page = 'views/user/history.php';
        require __DIR__ . '/../views/layouts/app.php';
    }
}
