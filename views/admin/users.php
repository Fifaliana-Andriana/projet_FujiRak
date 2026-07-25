<?php
require_once __DIR__ . '/../../models/User.php';

if (!isset($users)) {
    $userModel = new User();
    $users = $userModel->getAll();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #1a1a2e;
            --secondary-color: #16213e;
            --accent-color: #0f3460;
            --text-color: #eaeaea;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 20px 0;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.3);
        }
        .sidebar .logo {
            text-align: center;
            color: var(--text-color);
            font-weight: bold;
            font-size: 20px;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 30px;
        }
        .sidebar ul {
            list-style: none;
        }
        .sidebar ul li {
            margin: 0;
        }
        .sidebar ul li a {
            display: block;
            color: var(--text-color);
            text-decoration: none;
            padding: 12px 20px;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background: var(--accent-color);
            border-left-color: #e74c3c;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: var(--primary-color);
            font-weight: 600;
            margin: 0;
        }
        .add-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
        .alert-container {
            margin-bottom: 20px;
        }
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .table {
            margin: 0;
            font-size: 14px;
        }
        .table thead {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--text-color);
        }
        .table thead th {
            font-weight: 600;
            padding: 15px;
            border: none;
            vertical-align: middle;
        }
        .table tbody tr {
            transition: background 0.2s;
            border-bottom: 1px solid #f0f0f0;
        }
        .table tbody tr:hover {
            background: #f9f9f9;
        }
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-role-admin {
            background: #ff6b6b;
            color: white;
        }
        .badge-role-user {
            background: #4ecdc4;
            color: white;
        }
        .badge-class-simple {
            background: #95e1d3;
            color: #333;
        }
        .badge-class-gold {
            background: #ffd93d;
            color: #333;
        }
        .badge-class-plus {
            background: #ff6b9d;
            color: white;
        }
        .badge-active {
            background: #51cf66;
            color: white;
        }
        .badge-inactive {
            background: #868e96;
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        .btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 16px;
        }
        .btn-edit {
            background: #3498db;
            color: white;
        }
        .btn-edit:hover {
            background: #2980b9;
            transform: scale(1.1);
        }
        .btn-delete {
            background: #e74c3c;
            color: white;
        }
        .btn-delete:hover {
            background: #c0392b;
            transform: scale(1.1);
        }
        .empty-state {
            text-align: center;
            padding: 60px 30px;
            color: #7f8c8d;
        }
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }
        .modal-backdrop.show {
            background-color: rgba(0, 0, 0, 0.7);
        }
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--text-color);
            border: none;
            border-radius: 12px 12px 0 0;
        }
        .modal-header .btn-close {
            filter: invert(1);
        }
        .modal-body {
            padding: 25px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
        }
        .form-control,
        .form-select {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 12px;
            transition: border-color 0.2s;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .modal-footer {
            border-top: 1px solid #eee;
            padding: 20px;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            color: white;
        }
        .btn-secondary-custom {
            background: #e0e0e0;
            color: #333;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-secondary-custom:hover {
            background: #d0d0d0;
        }
        .no-data {
            text-align: center;
            color: #95a5a6;
            padding: 40px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo">
        <i class="bi bi-speedometer2"></i> FujiRak
    </div>
    <ul>
        <li><a href="index.php?route=admin/dashboard"><i class="bi bi-graph-up"></i> Dashboard</a></li>
        <li><a href="index.php?route=admin/users" class="active"><i class="bi bi-people"></i> Utilisateurs</a></li>
        <li><a href="index.php?route=admin/gains"><i class="bi bi-plus-circle"></i> Gains</a></li>
        <li><a href="index.php?route=admin/pertes"><i class="bi bi-dash-circle"></i> Pertes</a></li>
        <li><a href="index.php?route=logout"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="header">
        <h1><i class="bi bi-people"></i> Gestion des Utilisateurs</h1>
        <button type="button" class="add-btn" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-plus-lg"></i> Ajouter Utilisateur
        </button>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-container">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-container">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="table-container">
        <?php if (count($users) > 0): ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Classe</th>
                        <th>Rôle</th>
                        <th>Date d'inscription</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><strong>#<?= htmlspecialchars($user['id']) ?></strong></td>
                            <td><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="badge badge-class-<?= htmlspecialchars($user['classe']) ?>">
                                    <?= ucfirst(htmlspecialchars($user['classe'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-role-<?= htmlspecialchars($user['role']) ?>">
                                    <?= ucfirst(htmlspecialchars($user['role'])) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($user['date_creation'])) ?></td>
                            <td>
                                <span class="badge badge-<?= ($user['is_active'] ? 'active' : 'inactive') ?>">
                                    <?= ($user['is_active'] ? 'Actif' : 'Inactif') ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-icon btn-edit" title="Modifier (bientôt)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn-icon btn-delete" onclick="confirmDelete(<?= $user['id'] ?>)" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <i class="bi bi-inbox"></i>
                <p>Aucun utilisateur trouvé</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" backdrop="static" keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus"></i> Ajouter un nouvel utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?route=admin/create-user">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Prénom *</label>
                        <input type="text" name="prenom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom *</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mot de passe *</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Classe</label>
                        <select name="classe" class="form-select">
                            <option value="simple">Simple</option>
                            <option value="gold">Gold</option>
                            <option value="plus">Plus</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rôle</label>
                        <select name="role" class="form-select">
                            <option value="user">Utilisateur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn-primary-custom">Créer Utilisateur</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" action="index.php?route=admin/delete-user" style="display:none;">
    <input type="hidden" name="user_id" id="deleteUserId">
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmDelete(userId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
        document.getElementById('deleteUserId').value = userId;
        document.getElementById('deleteForm').submit();
    }
}
</script>

</body>
</html>
