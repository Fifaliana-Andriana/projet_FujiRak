<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Nasiko Class d'authocontroller
class AuthController {
    private $userModel;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();

        $this->userModel = new User();
    }


    public function showLoginForm() {
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?route=home');
            exit();
        }

        require_once __DIR__ . "/../views/auth/login.php";
    }


    public function sendCode() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: index.php?route=login');
            exit();
        }

        $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Format email invalide';
            header('Location: index.php?route=login');
            exit();
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            $_SESSION['error'] = 'Aucun compte trouvé avec cet email';
            header('Location: index.php?route=login');
            exit();
        }


        if (!$user['is_active']) {
            $_SESSION['error'] = 'Votre compte est désactivé. Contactez l\'administrateur.';
            header('Location: index.php?route=login');
            exit();
        }

        $code = rand(100000, 999999);

        $stmt = $this->db->prepare(
            "UPDATE users SET verification_code = ?, code_expiration = DATE_ADD(NOW(), INTERVAL 14 MINUTE) WHERE email = ?"
        );

        $stmt->execute([$code, $email]);

        $_SESSION['login_email'] = $email;

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'finixiias_mada@gmail.com';
            $mail->Password = 'Finixiias@123';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('finixiias_mada@gmail.com', 'FujiRak Dashboard');
            $mail->addAddress($email, $user['prenom'] . ' ' . $user['nom']);

            $mail->isHTML(true);
            $mail->Subject = 'Votre code de connexion - FujiRak Dashboard';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #333;'>🔐 Connexion FujiRak Dashboard</h2>
                    <p>Bonjour <strong>{$user['prenom']} {$user['nom']}</strong>,</p>
                    <p>Voici votre code de connexion :</p>
                    <div style='background: #f4f4f4; padding: 20px; text-align: center; border-radius: 10px; margin: 20px 0;'>
                        <h1 style='font-size: 48px; letter-spacing: 10px; color: #007bff; margin: 0;'>{$code}</h1>
                    </div>
                    <p style='color: #666;'>Ce code expire dans <strong>15 minutes</strong>.</p>
                    <p style='color: #999;'>Ne partagez jamais ce code avec qui que ce soit.</p>
                    <hr>
                    <p style='color: #999; font-size: 12px;'>FujiRak Dashboard - Tous droits réservés</p>
                </div>
            ";

            $mail->send();

            $_SESSION['success'] = 'Un code de connexion a été envoyé à ' . $email;

            header('Location: index.php?route=verify');
            exit();
        } catch (Exception $e) {
            $_SESSION['debug_code'] = $code;
            $_SESSION['error'] = "L'email n'a pas pu être envoyé. Code de secours : {$code}";

            header('Location: index.php?route=verify');
            exit();
        }
    }

    public function showVerifyForm() {
        if (!isset($_SESSION['login_email'])) {
            header('Location: index.php?route=login');
            exit();
        }

        require_once __DIR__ . '/../views/auth/verify.php';
    }

    public function verifyCode() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=verify');
            exit();
        }

        if (!isset($_SESSION['login_email'])) {
            header('Location: index.php?route=login');
            exit();
        }

        $code = trim($_POST['code']);
        $email = $_SESSION['login_email'];

        if (strlen($code) !== 6 || !is_numeric($code)) {
            $_SESSION['error'] = 'Le code doit contenir exactement 6 chiffres';
            header('Location: index.php?route=verify');
            exit();
        }

        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email = ? AND verification_code = ? AND code_expiration > NOW()"
        );

        $stmt->execute([$email, $code]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['prenom'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_classe'] = $user['classe'];
            $_SESSION['user_role'] = $user['role'];

            $clear = $this->db->prepare(
                "UPDATE users SET verification_code = NULL, code_expiration = NULL, last_login = NOW() WHERE id = ?"
            );

            $clear->execute([$user['id']]);

            unset($_SESSION['login_email']);
            unset($_SESSION['debug_code']);

            $_SESSION['success'] = 'Bienvenue ' . $user['prenom'] . ' !';

            if ($user['role'] == 'admin') {
                header('Location: index.php?route=admin/dashboard');
            } else {
                header('Location: index.php?route=home');
            }

            exit();
        } else {
            $_SESSION['error'] = 'Code incorrect ou expiré';
            header('Location: index.php?route=verify');
            exit();
        }
    }

    public function logout() {
        session_unset();
        session_destroy();

        header('Location: index.php?route=login');
        exit();
    }
}
?>