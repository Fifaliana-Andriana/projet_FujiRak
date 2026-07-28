<div class="page-header mb-4">
    <h2 class="fw-bold mb-1">Facturation</h2>
    <p class="text-muted mb-0">Envoie un document (facture, relevé...) à un utilisateur : PDF, DOC, DOCX, XLS ou XLSX (10 Mo max).</p>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="row g-4">

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white text-center"><h5 class="mb-0">Envoyer un document</h5></div>
            <div class="card-body">
                <form action="index.php?route=admin/factures/upload" method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Destinataire</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Choisir un utilisateur --</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?> (<?= htmlspecialchars($u['email']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Fichier</label>
                        <input type="file" name="document" class="form-control"
                               accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Description</label>
                        <input type="text" name="description" class="form-control" placeholder="ex: Facture juillet 2026">
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary bolder w-100">
                            <i class="bi bi-upload"></i> Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white text-center"><h5 class="mb-0">Documents envoyés</h5></div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">Destinataire</th>
                            <th class="text-center">Fichier</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Taille</th>
                            <th class="text-center">Envoyé le</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($factures)): ?>
                            <tr><td colspan="6" class="text-center text-muted py-4">Aucun document envoyé pour le moment.</td></tr>
                        <?php else: ?>
                            <?php foreach ($factures as $f): ?>
                                <tr>
                                    <td class="text-center"><?= htmlspecialchars($f['destinataire']) ?></td>
                                    <td class="text-center">
                                        <i class="bi bi-file-earmark-<?= $f['file_type'] === 'pdf' ? 'pdf' : (in_array($f['file_type'], ['xls','xlsx']) ? 'excel' : 'word') ?>"></i>
                                        <?= htmlspecialchars($f['original_name']) ?>
                                    </td>
                                    <td class="text-center"><?= htmlspecialchars($f['description'] ?? '') ?></td>
                                    <td class="text-center"><?= round($f['file_size'] / 1024) ?> Ko</td>
                                    <td class="text-center"><?= date('d/m/Y', strtotime($f['created_at'])) ?></td>
                                    <td class="text-center">
                                        <a href="index.php?route=download/facture&id=<?= $f['id'] ?>" class="">
                                            <i class="bi bi-download text-primary"></i>
                                        </a>
                                        <form action="index.php?route=admin/factures/delete" method="POST" class="d-inline"
                                              onsubmit="return confirm('Supprimer ce document ?');">
                                            <input type="hidden" name="facture_id" value="<?= $f['id'] ?>">
                                            <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
