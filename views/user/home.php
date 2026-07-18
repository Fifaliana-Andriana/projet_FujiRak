<?php



?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - FujiRak</title>
    <style>
        body { font-family: Arial; padding: 50px; background: #f0f2f5; }
        .card { background: white; padding: 30px; border-radius: 10px; max-width: 600px; margin: 0 auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .classe { display: inline-block; padding: 5px 15px; border-radius: 20px; font-weight: bold; margin-left: 10px; }
        .simple { background: silver; color: #333; }
        .gold { background: gold; color: #333; }
        .plus { background: gold; color: #333; }
    </style>
</head>
<body>
    <div class="card">
        <h1>✅ Connexion réussie !</h1>
        <p>Bienvenue <strong><?php echo $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']; ?></strong></p>
        <p>Email : <?php echo $_SESSION['user_email']; ?></p>
        <p>Classe : <span class="classe <?php echo $_SESSION['user_classe']; ?>"><?php echo strtoupper($_SESSION['user_classe']); ?></span></p>
        <p>Rôle : <?php echo $_SESSION['user_role']; ?></p>
        <hr>
        <a href="index.php?route=logout">🚪 Se déconnecter</a>
    </div>
</body>
</html>