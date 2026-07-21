<?php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - FujiRak Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .register-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 480px;
            width: 100%;
            margin: 0 auto;
        }
        .register-form {
            padding: 40px;
        }
        .register-form h2 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #333;
            text-align: center;
        }
        .register-form p {
            color: #666;
            margin-bottom: 30px;
            text-align: center;
        }
        .input-group-custom {
            margin-bottom: 20px;
        }
        .input-group-custom label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        .input-group-custom input {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
        }
        .btn-primary {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            border: none;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .message {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            font-size: 14px;
        }
        .error {
            background: #ffe6e6;
            color: #cc0000;
        }
        .success {
            background: #e6ffe6;
            color: #008000;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-form">
            <h2>Demande d'inscription</h2>
            <p>Entrez votre adresse email pour demander la création d'un compte.</p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="message error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="message success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <form action="index.php?route=submit-register" method="POST">
                <div class="input-group-custom">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email" placeholder="jeanpaul123@gmail.com" required autocomplete="email">
                </div>
                <button type="submit" class="btn btn-primary">Envoyer ma demande</button>
            </form>

            <div class="footer">
                <p>Vous avez déjà un compte ? <a href="index.php?route=login">Se connecter</a></p>
            </div>
        </div>
    </div>
</body>
</html>