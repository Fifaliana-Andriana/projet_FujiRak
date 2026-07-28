<div class="page-header mb-4">
    <h2 class="fw-bold mb-1">Désactiver un compte</h2>
</div>

<div class="card shadow-sm mx-auto" style="max-width: 520px;">
    <div class="card-body p-4 text-center">
        <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 42px;"></i>

        <h5 class="mt-3">
            Désactiver le compte de <strong><?= htmlspecialchars($deleteUser['username']) ?></strong> ?
        </h5>

        <p class="text-muted">
            Le compte (<?= htmlspecialchars($deleteUser['email']) ?>) sera désactivé et ne pourra plus se connecter.
            <br>
            <strong>Son historique de gains/pertes est conservé</strong> — tu peux le réactiver à tout moment
            depuis "Modifier".
        </p>

        <form action="index.php?route=admin/delete-user" method="POST" class="d-flex gap-2 justify-content-center mt-4">
            <input type="hidden" name="user_id" value="<?= $deleteUser['id'] ?>">
            <button type="submit" class="btn btn-danger px-4">Oui, désactiver</button>
            <a href="index.php?route=admin/users" class="btn btn-cancel px-4">Annuler</a>
        </form>
    </div>
</div>
