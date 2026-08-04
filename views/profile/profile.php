<?php
$photo = $user['photo'] ?? 'default.png';
$photoSrc = str_contains($photo, '/') ? $photo : 'assets/uploads/avatars/' . $photo;
?>

<div class="page-header mb-4">
    <h2 class="fw-bold mb-1">Mon profil</h2>
    <p class="text-muted mb-0">
        Pour modifier ton username, ton e-mail ou ton mot de passe, écris à l'admin par e-mail.
        Seule ta photo de profil est modifiable ici.
    </p>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="row g-4 mb-5">
    <div class="col-lg-4">
        <div class="card shadow-sm text-center p-4">
            <img src="<?= htmlspecialchars($photoSrc) ?>" alt="Photo de profil" class="rounded-circle mx-auto mb-3"
                 width="140" height="140" style="object-fit:cover;"
                 onerror="this.src='assets/images/icons/icone.png'">

            <span class="class-badge class-<?= $user['classe'] ?> mx-auto mb-3"><?= ucfirst($user['classe']) ?></span>

            <form action="index.php?route=user/update-avatar" method="POST" enctype="multipart/form-data">
                <input type="file" name="avatar" accept="image/png, image/jpeg, image/webp" class="form-control mb-2" required>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-upload"></i> Changer la photo
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Informations du compte</h5></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">Username</dt>
                    <dd class="col-sm-9"><?= htmlspecialchars($user['username']) ?></dd>

                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9"><?= htmlspecialchars($user['email']) ?></dd>

                    <dt class="col-sm-3">Classe</dt>
                    <dd class="col-sm-9"><span class="class-badge class-<?= $user['classe'] ?>"><?= ucfirst($user['classe']) ?></span></dd>

                    <dt class="col-sm-3">Membre depuis</dt>
                    <dd class="col-sm-9"><?= date('d/m/Y', strtotime($user['created_at'])) ?></dd>

                    <dt class="col-sm-3">Dernière connexion</dt>
                    <dd class="col-sm-9">
                        <?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Première connexion' ?>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Historique de transactions</h5></div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr><th>Date</th><th>Type</th><th>Description</th><th>Montant</th></tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">Aucune transaction pour le moment.</td></tr>
                <?php else: ?>
                    <?php $lastDay = null; ?>
                    <?php foreach ($transactions as $t): ?>
                        <?php table_render_day_separator($t['date_transaction'], $lastDay, 4); ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($t['date_transaction'])) ?></td>
                            <td><?= $t['type'] === 'gain' ? '<span class="badge bg-success">Gain</span>' : '<span class="badge bg-danger">Perte</span>' ?></td>
                            <td><?= htmlspecialchars($t['description'] ?? '') ?></td>
                            <td><?= number_format($t['montant'], 2, ',', ' ') ?> Ar</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
