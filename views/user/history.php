<div class="page-header mb-4">
    <h2 class="fw-bold mb-1">Historique des transactions</h2>
    <p class="text-muted mb-0">Liste complète de tes gains et pertes.</p>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Montant</th>
                    <th>Description</th>
                    <th>Catégorie / Source</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Aucune transaction trouvée.</td></tr>
                <?php else: ?>
                    <?php foreach ($transactions as $item): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($item['date_transaction'])) ?></td>
                            <td>
                                <?= $item['type'] === 'gain'
                                    ? '<span class="badge bg-success">Gain</span>'
                                    : '<span class="badge bg-danger">Perte</span>' ?>
                            </td>
                            <td><?= number_format($item['montant'], 2, ',', ' ') ?> Ar</td>
                            <td><?= htmlspecialchars($item['description'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['category'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
