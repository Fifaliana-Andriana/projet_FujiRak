<?php
// Variables attendues : $users, $pendingRequests
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administration des utilisateurs - FujiRak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>Administration des utilisateurs</h1>
                <p class="text-muted">Créer des comptes, valider les demandes et gérer les gains/pertes.</p>
            </div>
            <div>
                <a href="index.php?route=admin/dashboard" class="btn btn-outline-secondary me-2">Retour au dashboard</a>
                <a href="index.php?route=logout" class="btn btn-danger">Déconnexion</a>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <div class="row gy-4">
            <div class="col-lg-6">
                <div class="card shadow-sm p-4">
                    <h2>Créer un utilisateur</h2>
                    <form action="index.php?route=admin/create-user" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="exemple@mail.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nom d'utilisateur</label>
                            <input type="text" name="username" class="form-control" placeholder="john123" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirmer</label>
                                <input type="password" name="password_confirm" class="form-control" required>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Classe</label>
                                <select name="classe" class="form-select">
                                    <option value="simple">Simple</option>
                                    <option value="gold">Gold</option>
                                    <option value="plus">Plus</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Rôle</label>
                                <select name="role" class="form-select">
                                    <option value="user" selected>Utilisateur</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary">Créer l'utilisateur</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm p-4">
                    <h2>Ajouter un gain / une perte</h2>
                    <form action="index.php?route=admin/add-finance" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Utilisateur</label>
                            <select name="user_id" class="form-select" required>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username'] . ' - ' . $user['email']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="gain">Gain</option>
                                <option value="perte">Perte</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Montant</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control" placeholder="Description">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Source / Catégorie</label>
                            <input type="text" name="meta" class="form-control" placeholder="Commission, Frais, etc.">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <button class="btn btn-secondary">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>

        <hr>
        <h2>Demandes d'inscription</h2>
        <?php if (count($pendingRequests) === 0): ?>
            <div class="alert alert-secondary">Aucune demande en attente.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>IP</th>
                            <th>Soumise le</th>
                            <th>Créer</th>
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
                                    <form action="index.php?route=admin/approve-request" method="POST" class="row g-2 align-items-center">
                                        <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                        <div class="col-12 col-md-4">
                                            <input type="text" name="username" class="form-control form-control-sm" placeholder="Nom d'utilisateur" required>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <input type="password" name="password" class="form-control form-control-sm" placeholder="Mot de passe" required>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <input type="password" name="password_confirm" class="form-control form-control-sm" placeholder="Confirmer" required>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <button type="submit" class="btn btn-success btn-sm w-100">Valider</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <hr>
        <h2>Liste des utilisateurs</h2>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Classe</th>
                        <th>Rôle</th>
                        <th>Actif</th>
                        <th>Créé le</th>
                        <th>Modifier mot de passe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?php echo $u['id']; ?></td>
                            <td><?php echo htmlspecialchars($u['username']); ?></td>
                            <td><?php echo htmlspecialchars(trim($u['nom'] . ' ' . $u['prenom'])); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><?php echo htmlspecialchars($u['class']); ?></td>
                            <td><?php echo $u['role']; ?></td>
                            <td><?php echo $u['is_active'] ? 'Oui' : 'Non'; ?></td>
                            <td><?php echo $u['date_creation']; ?></td>
                            <td>
                                <form action="index.php?route=admin/update-user" method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                    <input type="password" name="password" class="form-control form-control-sm" placeholder="Nouveau mot de passe" required>
                                    <button type="submit" class="btn btn-sm btn-primary">Mettre à jour</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
