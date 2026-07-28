<div class="page-header mb-4">
    <h2 class="fw-bold mb-1">Modifier :
        <?= htmlspecialchars($editUser['username']) ?>
    </h2>
    <p class="text-muted mb-0">
        Modifie les infos du compte ou son mot de passe, puis renvoie les changements à l'utilisateur par Gmail.
    </p>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['error']);
        unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['success']);
        unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="row g-4">

    <!-- Infos du compte + mot de passe -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white text-center">
                <h5 class="mb-0">Informations du compte</h5>
            </div>
            <div class="card-body">
                <form action="index.php?route=admin/update-user" method="POST">
                    <input type="hidden" name="user_id" value="<?= $editUser['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($editUser['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="username" class="form-control"
                            value="<?= htmlspecialchars($editUser['username']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">New Password</label>
                        <input type="password" name="new_password" class="form-control" minlength="8"
                            placeholder="Laisser vide pour ne pas changer">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Classe (promotion)</label>
                            <select name="classe" class="form-select">
                                <?php foreach (['simple', 'gold', 'plus'] as $c): ?>
                                    <option value="<?= $c ?>" <?= $editUser['classe'] === $c ? 'selected' : '' ?>>
                                        <?= ucfirst($c) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Rôle</label>
                            <select name="role" class="form-select">
                                <option value="user" <?= $editUser['role'] === 'user' ? 'selected' : '' ?>>Utilisateur
                                </option>
                                <option value="admin" <?= $editUser['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive"
                            <?= $editUser['is_active'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>

                    <div class="d-flex gap-2 justify-content-center">
                        <button type="submit" class="btn btn-primary bolder">Enregistrer</button>
                        <a href="index.php?route=admin/users" class="btn btn-cancel">Retour</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Coins : ajout gain / perte pour cet utilisateur -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white text-center">
                <h5 class="mb-0">Transaction</h5>
            </div>
            <div class="card-body">
                <form action="index.php?route=admin/add-finance" method="POST">
                    <input type="hidden" name="user_id" value="<?= $editUser['id'] ?>">
                    <input type="hidden" name="redirect" value="admin/edit-user">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Type</label>
                        <select name="type" class="form-select">
                            <option value="gain">Gain</option>
                            <option value="perte">Perte</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Montant</label>
                        <input type="number" step="0.01" min="0.01" name="amount" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <input type="text" name="description" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Source / Catégorie</label>
                        <input type="text" name="meta" class="form-control">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Date</label>
                        <input type="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary bolder">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>