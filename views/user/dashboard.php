<div class="page-header mb-4">
    <div>
        <h2 class="fw-bold mb-1 d-flex align-items-center">
            Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?>
            <span class="class-badge class-<?= $classe ?> ms-2"><?= ucfirst($classe) ?></span>
        </h2>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-4">
        <div class="dashboard-card gain-card">
            <div class="card-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="card-content">
                <small>Total des gains</small>
                <h2><?= number_format($totals['gains'], 2, ',', ' ') ?> €</h2>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="dashboard-card perte-card">
            <div class="card-icon"><i class="bi bi-graph-down-arrow"></i></div>
            <div class="card-content">
                <small>Total des pertes</small>
                <h2><?= number_format($totals['pertes'], 2, ',', ' ') ?> €</h2>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="dashboard-card solde-card">
            <div class="card-icon"><i class="bi bi-wallet2"></i></div>
            <div class="card-content">
                <small>Mon solde</small>
                <h2><?= number_format($totals['solde'], 2, ',', ' ') ?> €</h2>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-5">
    <div class="card-header bg-white">
        <h5 class="mb-0">Évolution de mes finances</h5>
    </div>
    <div class="card-body">
        <canvas id="financeChart" data-trend-chart data-endpoint="index.php?route=user/stats-json"
            data-initial='<?= json_encode($trend, JSON_HEX_APOS) ?>' data-label1="Gains" data-label2="Pertes">
        </canvas>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Historique récent</h5>
        <a href="index.php?route=user/history">Voir tout l'historique</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Aucune transaction pour le moment.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($t['date_transaction'])) ?></td>
                            <td>
                                <?php if ($t['type'] === 'gain'): ?>
                                    <span class="badge bg-success">Gain</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Perte</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($t['description'] ?? '') ?></td>
                            <td><?= number_format($t['montant'], 2, ',', ' ') ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>