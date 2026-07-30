<div class="page-header mb-4">
    <h2 class="fw-bold mb-1">Mes documents</h2>
    <p class="text-muted mb-0">Factures et documents envoyés par l'administration.</p>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="text-center">Fichier</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Taille</th>
                    <th class="text-center">Reçu le</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($factures)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Aucun document reçu pour le moment.</td></tr>
                <?php else: ?>
                    <?php foreach ($factures as $f): ?>
                        <tr>
                            <td class="text-center">
                                <i class="bi bi-file-earmark-<?= $f['file_type'] === 'pdf' ? 'pdf' : (in_array($f['file_type'], ['xls','xlsx']) ? 'excel' : 'word') ?> me-1"></i>
                                <?= htmlspecialchars($f['original_name']) ?>
                            </td>
                            <td class="text-center"><?= htmlspecialchars($f['description'] ?? '') ?></td>
                            <td class="text-center"><?= round($f['file_size'] / 1024) ?> Ko</td>
                            <td class="text-center"><?= date('d/m/Y', strtotime($f['created_at'])) ?></td>
                            <td class="text-center">
                                <a href="index.php?route=download/facture&id=<?= $f['id'] ?>" class="btn btn-sm btn-primary bolder">
                                    Télécharger
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
