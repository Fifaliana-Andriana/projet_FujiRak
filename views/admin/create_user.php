<div class="page-header mb-4">
    <h2 class="fw-bold mb-1">Créer un utilisateur</h2>
    <p class="text-muted mb-0">
        À utiliser après réception d'une demande de compte par E-mail. Une fois créé, envoie le nom
        d'utilisateur et le mot de passe à l'utilisateur par E-mail.
    </p>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="card shadow-sm d-flex align-items-center justify-content-center w-100">
    <div class="card-body w-50">
        <form action="index.php?route=admin/create-user" method="POST">

            <div class="mb-3">
                <label class="form-label fw-bold">E-mail</label>
                <input type="email" name="email" class="form-control" placeholder="utilisateur@email.com" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Username</label>
                <input type="text" name="username" class="form-control" placeholder="ex: jdupont" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" minlength="8" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Confirmer</label>
                    <input type="password" name="password_confirm" class="form-control" minlength="8" required>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Classe</label>
                    <select name="classe" class="form-select">
                        <option value="simple">Simple</option>
                        <option value="gold">Gold</option>
                        <option value="plus">Plus</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Rôle</label>
                    <select name="role" class="form-select">
                        <option value="user" selected>Utilisateur</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>

            <div class="d-flex gap-2 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary bolder">Créer le compte</button>
                <a href="index.php?route=admin/users" class="btn btn-cancel">Annuler</a>
            </div>
        </form>
    </div>
</div>
