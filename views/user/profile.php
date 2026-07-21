<?php
$userAvatar = $user['avatar'] ?: 'assets/images/default-avatar.png';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - FujiRak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .avatar {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>Mon profil</h1>
                <p class="text-muted">Gérez vos informations personnelles et votre mot de passe.</p>
            </div>
            <div>
                <a href="index.php?route=user/home" class="btn btn-secondary me-2">Retour au dashboard</a>
                <a href="index.php?route=logout" class="btn btn-danger">Déconnexion</a>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-4 text-center">
                <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="Avatar" class="avatar mb-3">
                <form action="index.php?route=user/update-profile" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Photo de profil</label>
                        <input type="file" name="avatar" class="form-control" accept="image/jpeg,image/png,image/webp" required>
                    </div>
                    <button class="btn btn-primary w-100">Mettre à jour</button>
                </form>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm p-4 mb-4">
                    <h2>Informations du compte</h2>
                    <form>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['nom']); ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Prénom</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['prenom']); ?>" disabled>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nom d'utilisateur</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Classe</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['classe']); ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Rôle</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['role']); ?>" disabled>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card shadow-sm p-4">
                    <h2>Modifier le mot de passe</h2>
                    <form action="index.php?route=user/change-password" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Mot de passe actuel</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirmer le nouveau mot de passe</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button class="btn btn-primary">Changer le mot de passe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
