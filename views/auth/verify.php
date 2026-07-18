<?php



?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification - FujiRak Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .verify-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            width: 400px;
            text-align: center;
        }
        h2 { color: #333; }
        p { color: #666; margin-bottom: 20px; }
        input[type="text"] {
            width: 200px;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 24px;
            text-align: center;
            letter-spacing: 10px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover { background: #218838; }
        .error { background: #ffe6e6; color: #cc0000; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .success { background: #e6ffe6; color: #008000; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .debug { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 15px 0; font-size: 14px; }
        .back { color: #007bff; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="verify-box">
        <h2>📧 Vérification</h2>
        <p>Un code a été envoyé à :<br><strong><?php echo $_SESSION['login_email']; ?></strong></p>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['debug_code'])): ?>
            <div class="debug">
                ⚠️ Mode développement<br>
                Code : <strong><?php echo $_SESSION['debug_code']; ?></strong>
            </div>
        <?php endif; ?>
        
        <form action="index.php?route=check-code" method="POST">
            <label for="code">Entrez le code à 6 chiffres :</label><br>
            <input type="text" id="code" name="code" maxlength="6" placeholder="000000" required>
            <button type="submit" name="verify_code">Vérifier</button>
        </form>
        
        <br>
        <a href="index.php?route=login" class="back">← Retour</a>
    </div>
</body>
</html>