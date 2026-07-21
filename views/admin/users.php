<?php
// expects $users (array) and $pendingRequests (array) from controller
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administration des utilisateurs - FujiRak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h1>Administration des utilisateurs</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <h2>Demandes d'inscription en attente (<?php echo count($pendingRequests); ?>)</h2>

        <?php if (count($pendingRequests) === 0): ?>
            <p>Aucune demande en attente.</p>
        <?php else: ?>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>IP</th>
                        <th>Soumise le</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingRequests as $req): ?>
                        <tr>
                            <td><?php echo $req['id']; ?></td>
                            <td><?php echo htmlspecialchars($req['email']); ?></td>
                            <td><?php echo htmlspecialchars($req['ip_address']); ?></td>
                            <td><?php echo $req['date_creation']; ?></td>
                            <td>
                                <form method="POST" action="index.php?route=admin/approve-request" class="d-flex gap-2 align-items-center">
                                    <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                    <input type="text" name="username" placeholder="Nom d'utilisateur" required class="form-control form-control-sm" style="max-width:180px">
                                    <input type="password" name="password" placeholder="Mot de passe" required class="form-control form-control-sm" style="max-width:180px">
                                    <input type="password" name="password_confirm" placeholder="Confirmer" required class="form-control form-control-sm" style="max-width:180px">
                                    <button type="submit" class="btn btn-success btn-sm">Créer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <hr>
        <h2>Utilisateurs</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actif</th>
                    <th>Créé le</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo $u['id']; ?></td>
                        <td><?php echo htmlspecialchars($u['username']); ?></td>
                        <td><?php echo htmlspecialchars($u['nom'] . ' ' . $u['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo $u['role']; ?></td>
                        <td><?php echo $u['is_active'] ? 'Oui' : 'Non'; ?></td>
                        <td><?php echo $u['date_creation']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="index.php?route=admin/dashboard" class="btn btn-secondary">Retour</a>
    </div>
</body>
</html>