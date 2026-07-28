<?php
// controllers/DashboardController.php — espace Admin

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Finance.php';
require_once __DIR__ . '/../models/Facture.php';

class DashboardController
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

    // "Accueil" : vue financière générale
    public function showAdminDashboard()
    {
        $period = $_GET['period'] ?? 'month';
        if (!in_array($period, ['day', 'month', 'year'], true)) {
            $period = 'month';
        }

        $totals = $this->financeModel->getTotalGainsLosses();
        $totalGains = $totals['gains'];
        $totalPertes = $totals['pertes'];
        $soldeGeneral = $totals['solde'];

        $trend = $this->financeModel->getGeneralTrend($period);
        $lastTransactions = $this->financeModel->getLastTransactions(10);
        $periodLabel = $period;

        $page = 'views/admin/dashboard.php';
        require __DIR__ . '/../views/layouts/app.php';
    }

    // Retourne la courbe générale en JSON (utilisée par filter.js)
    public function statsJson()
    {
        $period = $_GET['period'] ?? 'month';
        if (!in_array($period, ['day', 'month', 'year'], true)) {
            $period = 'month';
        }
        header('Content-Type: application/json');
        echo json_encode($this->financeModel->getGeneralTrend($period));
        exit();
    }

    // "Dashboard" : statistiques utilisateurs (nombre, classes, croissance)
    public function showAdminStatistics()
    {
        $period = $_GET['period'] ?? 'month';
        if (!in_array($period, ['day', 'month', 'year'], true)) {
            $period = 'month';
        }

        $totalUsers = $this->userModel->getTotalUsers();
        $classCounts = $this->userModel->getClassCounts();
        $registrationStats = $this->userModel->getRegistrationStats($period);
        $periodLabel = $period;

        $page = 'views/admin/statistics.php';
        require __DIR__ . '/../views/layouts/app.php';
    }

    public function userStatsJson()
    {
        $period = $_GET['period'] ?? 'month';
        if (!in_array($period, ['day', 'month', 'year'], true)) {
            $period = 'month';
        }
        header('Content-Type: application/json');
        echo json_encode($this->userModel->getRegistrationStats($period));
        exit();
    }

    // Liste des utilisateurs, avec filtre optionnel par classe
    public function showAdminUsers()
    {
        $classeFilter = $_GET['classe'] ?? null;
        $users = $this->userModel->getAllUsers($classeFilter);
        $classCounts = $this->userModel->getClassCounts();
        $classeFilter = $classeFilter;

        $page = 'views/admin/users.php';
        require __DIR__ . '/../views/layouts/app.php';
    }

    // Formulaire de création (GET)
    public function showCreateUserForm()
    {
        $page = 'views/admin/create_user.php';
        require __DIR__ . '/../views/layouts/app.php';
    }

    // Traitement de la création (POST)
    public function createUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=admin/create-user');
            exit();
        }

        $email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $classe = $_POST['classe'] ?? 'simple';
        $role = $_POST['role'] ?? 'user';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($username) || empty($password)) {
            $_SESSION['error'] = 'Tous les champs obligatoires doivent être remplis.';
            header('Location: index.php?route=admin/create-user');
            exit();
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = 'Les mots de passe ne correspondent pas.';
            header('Location: index.php?route=admin/create-user');
            exit();
        }

        if (strlen($password) < 8) {
            $_SESSION['error'] = 'Le mot de passe doit contenir au moins 8 caractères.';
            header('Location: index.php?route=admin/create-user');
            exit();
        }

        $created = $this->userModel->create([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'classe' => in_array($classe, ['simple', 'gold', 'plus'], true) ? $classe : 'simple',
            'role' => $role === 'admin' ? 'admin' : 'user',
        ]);

        if ($created) {
            $_SESSION['success'] = "Compte créé. Pense à envoyer le nom d'utilisateur et le mot de passe à $email par Gmail.";
            header('Location: index.php?route=admin/users');
        } else {
            $_SESSION['error'] = 'Cet email ou ce nom d\'utilisateur est déjà utilisé.';
            header('Location: index.php?route=admin/create-user');
        }
        exit();
    }

    // Formulaire d'édition (GET, ?id=)
    public function showEditUserForm()
    {
        $id = intval($_GET['id'] ?? 0);
        $editUser = $this->userModel->findById($id);

        if (!$editUser) {
            $_SESSION['error'] = 'Utilisateur introuvable.';
            header('Location: index.php?route=admin/users');
            exit();
        }

        $page = 'views/admin/edit_user.php';
        require __DIR__ . '/../views/layouts/app.php';
    }

    // Traitement de l'édition (POST) : username, email, classe, rôle, statut, mot de passe optionnel
    public function updateUser()
    {
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

        $username = trim($_POST['username'] ?? '');
        $email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
        $classe = $_POST['classe'] ?? 'simple';
        $role = ($_POST['role'] ?? 'user') === 'admin' ? 'admin' : 'user';
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $newPassword = $_POST['new_password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($username)) {
            $_SESSION['error'] = "Email et nom d'utilisateur sont obligatoires.";
            header('Location: index.php?route=admin/edit-user&id=' . $userId);
            exit();
        }

        $existingByEmail = $this->userModel->findByEmail($email);
        if ($existingByEmail && $existingByEmail['id'] != $userId) {
            $_SESSION['error'] = 'Cet email est déjà utilisé par un autre compte.';
            header('Location: index.php?route=admin/edit-user&id=' . $userId);
            exit();
        }

        $this->userModel->updateUser($userId, [
            'username' => $username,
            'email' => $email,
            'classe' => in_array($classe, ['simple', 'gold', 'plus'], true) ? $classe : 'simple',
            'role' => $role,
            'is_active' => $isActive,
        ]);

        if (!empty($newPassword)) {
            if (strlen($newPassword) < 8) {
                $_SESSION['error'] = 'Informations mises à jour, mais le mot de passe doit contenir au moins 8 caractères (non changé).';
                header('Location: index.php?route=admin/users');
                exit();
            }
            $this->userModel->changePassword($userId, $newPassword);
            $_SESSION['success'] = "Compte mis à jour. Pense à envoyer le nouveau mot de passe à $email par Gmail.";
        } else {
            $_SESSION['success'] = 'Informations mises à jour.';
        }

        header('Location: index.php?route=admin/users');
        exit();
    }

    // Étape 1 : page de confirmation avant désactivation (GET, ?id=)
    public function confirmDeleteUser()
    {
        $id = intval($_GET['id'] ?? 0);
        $deleteUser = $this->userModel->findById($id);

        if (!$deleteUser) {
            $_SESSION['error'] = 'Utilisateur introuvable.';
            header('Location: index.php?route=admin/users');
            exit();
        }

        if ($deleteUser['id'] == $_SESSION['user_id']) {
            $_SESSION['error'] = 'Tu ne peux pas désactiver ton propre compte.';
            header('Location: index.php?route=admin/users');
            exit();
        }

        $page = 'views/admin/delete_user.php';
        require __DIR__ . '/../views/layouts/app.php';
    }

    // Étape 2 : désactivation effective (POST, après confirmation)
    public function deleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=admin/users');
            exit();
        }

        $id = intval($_POST['user_id'] ?? 0);

        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = 'Tu ne peux pas désactiver ton propre compte.';
            header('Location: index.php?route=admin/users');
            exit();
        }

        $this->userModel->softDelete($id);
        $_SESSION['success'] = 'Compte désactivé. Son historique est conservé, tu peux le réactiver depuis "Modifier".';

        header('Location: index.php?route=admin/users');
        exit();
    }

    // Liste de tous les documents envoyés + formulaire d'upload
    public function showFactures()
    {
        $factures = $this->factureModel->getAll();
        $users = $this->userModel->getAllUsers();

        $page = 'views/admin/factures.php';
        require __DIR__ . '/../views/layouts/app.php';
    }

    // Upload d'un document (PDF/DOC/XLSX...) vers un utilisateur donné
    public function uploadFacture()
    {
        require_once __DIR__ . '/../config/upload_rules.php';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=admin/factures');
            exit();
        }

        $userId = intval($_POST['user_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');

        if ($userId <= 0 || !$this->userModel->findById($userId)) {
            $_SESSION['error'] = 'Utilisateur destinataire invalide.';
            header('Location: index.php?route=admin/factures');
            exit();
        }

        $validation = validateFactureUpload($_FILES['document'] ?? []);
        if (!$validation['ok']) {
            $_SESSION['error'] = $validation['error'];
            header('Location: index.php?route=admin/factures');
            exit();
        }

        $uploadDir = __DIR__ . '/../assets/uploads/factures';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $storedName = 'facture_' . $userId . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $validation['extension'];
        $destination = $uploadDir . '/' . $storedName;

        if (move_uploaded_file($_FILES['document']['tmp_name'], $destination)) {
            $this->factureModel->create(
                $userId,
                $_SESSION['user_id'],
                $_FILES['document']['name'],
                $storedName,
                $validation['extension'],
                $_FILES['document']['size'],
                $description
            );
            $_SESSION['success'] = "Document envoyé. Pense à prévenir l'utilisateur par e-mail si besoin.";
        } else {
            $_SESSION['error'] = "Impossible d'enregistrer le fichier sur le serveur.";
        }

        header('Location: index.php?route=admin/factures');
        exit();
    }

    // Suppression d'un document envoyé par erreur
    public function deleteFacture()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=admin/factures');
            exit();
        }

        $id = intval($_POST['facture_id'] ?? 0);
        $facture = $this->factureModel->findById($id);

        if ($facture) {
            $filePath = __DIR__ . '/../assets/uploads/factures/' . $facture['stored_name'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $this->factureModel->delete($id);
            $_SESSION['success'] = 'Document supprimé.';
        }

        header('Location: index.php?route=admin/factures');
        exit();
    }

    // Ajout d'un gain ou d'une perte pour un utilisateur donné (admin)
    public function addFinance()
    {
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
        $redirect = $_POST['redirect'] ?? 'admin/users';

        if ($userId <= 0 || $amount <= 0 || !in_array($type, ['gain', 'perte'], true)) {
            $_SESSION['error'] = 'Les informations de transaction ne sont pas valides.';
            header('Location: index.php?route=' . $redirect);
            exit();
        }

        $result = $type === 'gain'
            ? $this->financeModel->addGain($userId, $amount, $description, $meta, $date)
            : $this->financeModel->addPerte($userId, $amount, $description, $meta, $date);

        $_SESSION[$result ? 'success' : 'error'] = $result
            ? ucfirst($type) . ' ajouté avec succès.'
            : "Impossible d'ajouter la transaction.";

        header('Location: index.php?route=' . $redirect . '&id=' . $userId);
        exit();
    }
}
