<?php
session_start();

require '../config/database.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// =====================================================
// ENVOI DU CODE
// =====================================================
if (isset($_POST['send_code'])) {

    $email = trim($_POST['email']);

    // Vérifier si l'utilisateur existe
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        die("Utilisateur introuvable");
    }

    // Générer un code à 6 chiffres
    $code = rand(100000, 999999);

    // Enregistrer le code dans la base
    $update = $pdo->prepare(
        "UPDATE users SET code_connexion = ? WHERE email = ?"
    );
    $update->execute([$code, $email]);

    // Sauvegarder l'email en session
    $_SESSION['email'] = $email;

    // Envoi de l'email
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'finixiias_mada@gmail.com';
        $mail->Password = 'Finixiias@123';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('finixiias_mada@gmail.com', 'Finixiias');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Votre code de connexion';
        $mail->Body = "
            <h2>Connexion Finixiias</h2>
            <p>Votre code de connexion est :</p>
            <h1>$code</h1>
            <p>Ne partagez jamais ce code.</p>
        ";

        $mail->send();

        header('Location: ../index.php?route=verify');
        exit();

    } catch (Exception $e) {
        echo "Erreur lors de l'envoi : {$mail->ErrorInfo}";
    }
}

// =====================================================
// CODE CHECKING
// =====================================================
if (isset($_POST['verify_code'])) {

    $code = trim($_POST['code']);
    $email = $_SESSION['email'] ?? null;

    if (!$email) {
        header('Location: ../index.php?route=login');
        exit();
    }

    $stmt = $pdo->prepare(
        "SELECT * FROM users WHERE email = ? AND code_connexion = ?"
    );
    $stmt->execute([$email, $code]);
    $user = $stmt->fetch();

    if ($user) {

        // Connexion réussie
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['classe'] = $user['classe'];

        // Supprimer le code après utilisation
        $clear = $pdo->prepare(
            "UPDATE users SET code_connexion = NULL WHERE id = ?"
        );
        $clear->execute([$user['id']]);

        // Redirection selon le rôle
        if ($user['role'] == 'admin') {
            header('Location: ../index.php?route=admin/dashboard');
        } else {
            header('Location: ../index.php?route=user/home');
        }

        exit();

    } else {
        echo "Code incorrect";
    }
}