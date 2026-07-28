<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finixiias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #04a59d 0%, #16213e 50%, #1a1a2e 100%);
            font-family: Arial, sans-serif;
        }
        .confirm-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }
        .confirm-card i { font-size: 48px; color: #04a59d; }
    </style>
</head>
<body>
    <div class="confirm-card">
        <i class="bi bi-box-arrow-right mb-3 d-block"></i>
        <h4>Confirmer la déconnexion</h4>
        <p class="text-muted">Êtes-vous sûr de vouloir vous déconnecter de ton espace Finixiias ?</p>

        <form action="index.php?route=logout" method="POST" class="d-flex gap-2 justify-content-center mt-4">
            <button type="submit" class="btn btn-danger px-4">Oui, déconnecter</button>
            <a href="javascript:history.back()" class="btn btn-cancel px-4">Annuler</a>
        </form>
    </div>
</body>
</html>
