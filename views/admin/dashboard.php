<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?></h2>
        <p class="text-muted mb-0">Vue générale des finances Finixiias</p>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-4">
        <div class="dashboard-card gain-card">
            <div class="card-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="card-content">
                <small>Total des gains</small>
                <h2><?= number_format($totalGains, 2, ',', ' ') ?> €</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="dashboard-card perte-card">
            <div class="card-icon"><i class="bi bi-graph-down-arrow"></i></div>
            <div class="card-content">
                <small>Total des pertes</small>
                <h2><?= number_format($totalPertes, 2, ',', ' ') ?> €</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="dashboard-card solde-card">
            <div class="card-icon"><i class="bi bi-wallet2"></i></div>
            <div class="card-content">
                <small>Solde Général</small>
                <h2><?= number_format($soldeGeneral, 2, ',', ' ') ?> €</h2>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-5">
    <div class="card-header bg-white">
        <h5 class="mb-0">Évolution financière</h5>
    </div>
    <div class="card-body">
        <canvas id="financeChart" data-trend-chart data-endpoint="index.php?route=admin/stats-json"
            data-initial='<?= json_encode($trend, JSON_HEX_APOS) ?>' data-label1="Gains" data-label2="Pertes">
        </canvas>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Dernières transactions</h5>
        <a href="index.php?route=admin/statistics">Voir les statistiques utilisateurs</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Montant</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lastTransactions)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Aucune transaction pour le moment.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($lastTransactions as $transaction): ?>
                        <tr>
                            <td><?= htmlspecialchars($transaction['username']) ?></td>
                            <td>
                                <?php if ($transaction['type'] === 'Gain'): ?>
                                    <span class="badge bg-success">Gain</span>
                                <?php else: ?>
                                    <span class="badge" style="background: #7c1f25;">Perte</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($transaction['description'] ?? '') ?></td>
                            <td><?= number_format($transaction['montant'], 2, ',', ' ') ?> €</td>
                            <td><?= date('d/m/Y', strtotime($transaction['date'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>