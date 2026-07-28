<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 id="head" class="fw-bold mb-1">Listes des utilisateurs</h2>
        <p class="text-muted mb-0">
            <?= $classCounts['simple'] + $classCounts['gold'] + $classCounts['plus'] ?> utilisateur(s) au total
        </p>
    </div>
    <a href="index.php?route=admin/create-user" class="btn btn-primary d-flex justify-content-center align-items-center bolder">
        <i class="bi bi-plus-lg"></i> Ajouter
    </a>
</div>

<?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']);
        unset($_SESSION['error']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']);
        unset($_SESSION['success']); ?></div>
<?php endif; ?>

<!-- Filtre par classe -->
<div class="btn-group mb-4" role="group">
    <a href="index.php?route=admin/users" class="btn btn-outline-secondary <?= empty($classeFilter) ? 'active' : '' ?>">
        Tous (<?= $classCounts['simple'] + $classCounts['gold'] + $classCounts['plus'] ?>)
    </a>
    <a href="index.php?route=admin/users&classe=simple" class="btn btn-outline-secondary <?= $classeFilter === 'simple' ? 'active' : '' ?>">
        Simple (<?= $classCounts['simple'] ?>)
    </a>
    <a href="index.php?route=admin/users&classe=gold" class="btn btn-outline-secondary <?= $classeFilter === 'gold' ? 'active' : '' ?>">
        Gold (<?= $classCounts['gold'] ?>)
    </a>
    <a href="index.php?route=admin/users&classe=plus" class="btn btn-outline-secondary <?= $classeFilter === 'plus' ? 'active' : '' ?>">
        Plus (<?= $classCounts['plus'] ?>)
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="text-center">Utilisateur</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Classe</th>
                    <th class="text-center">Rôle</th>
                    <th class="text-center">Statut</th>
                    <th class="text-center">Inscrit le</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Aucun utilisateur trouvé.</td></tr>
                <?php else: ?>
                        <?php foreach ($users as $u): ?>
                                <tr>
                                    <td class="text-center"><?= htmlspecialchars($u['username']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($u['email']) ?></td>
                                    <td class="text-center">
                                        <span class="class-badge class-<?= $u['classe'] ?>">
                                            <?= ucfirst($u['classe']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center"><?= $u['role'] === 'admin' ? 'Admin' : 'Utilisateur' ?></td>
                                    <td class="text-center">
                                        <?php if ($u['is_active']): ?>
                                                <span class="badge bg-success">Actif</span>
                                        <?php else: ?>
                                                <span class="badge bg-secondary">Désactivé</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                                    <td class="text-center">
                                        <a href="index.php?route=admin/edit-user&id=<?= $u['id'] ?>" class="me-2">
                                           <i class="bi bi-pencil-square text-success"></i>
                                        </a>
                                        <?php if ($u['is_active'] && $u['id'] != $_SESSION['user_id']): ?>
                                                <a href="index.php?route=admin/delete-user&id=<?= $u['id'] ?>">
                                                    <i class="bi bi-slash-circle text-danger"></i>
                                                </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                        <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
