<?php
// views/user/home.php
require_once __DIR__ . '/../../models/Finance.php';

$financeModel = new Finance();
$totals = $financeModel->getUserTotals($_SESSION['user_id']);
$history = $financeModel->getUserTransactionHistory($_SESSION['user_id'], 5);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FujiRak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f0f2f5; font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { 
            background: white; border-radius: 15px; padding: 20px 30px; 
            margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            display: flex; justify-content: space-between; align-items: center;
        }
        .welcome { font-size: 18px; color: #333; }
        .welcome strong { font-size: 22px; }
        .class-badge {
            display: inline-block; padding: 8px 20px; border-radius: 20px;
            font-weight: bold; font-size: 14px;
        }
        .class-simple { background: #C0C0C0; color: #333; }
        .class-gold { background: #FFD700; color: #333; }
        .class-plus { background: #FFD700; color: #333; position: relative; }
        .class-plus::after {
            content: '+'; position: absolute; top: -5px; right: -5px;
            background: #FF4500; color: white; width: 18px; height: 18px;
            border-radius: 50%; font-size: 12px;
            display: flex; align-items: center; justify-content: center;
        }
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px; }
        .stat-card {
            background: white; border-radius: 15px; padding: 25px; text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .stat-card .icon { font-size: 35px; margin-bottom: 10px; }
        .stat-card .value { font-size: 24px; font-weight: bold; }
        .stat-card .label { color: #888; font-size: 13px; margin-top: 5px; }
        .stat-card.gains { border-bottom: 4px solid #28a745; }
        .stat-card.pertes { border-bottom: 4px solid #dc3545; }
        .stat-card.solde { border-bottom: 4px solid #007bff; }
        .section {
            background: white; border-radius: 15px; padding: 25px;
            margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .section h5 { margin-bottom: 20px; color: #333; }
        .logout-btn {
            background: #dc3545; color: white; border: none;
            padding: 10px 20px; border-radius: 10px; text-decoration: none;
            transition: all 0.3s;
        }
        .logout-btn:hover { background: #bb2d3b; color: white; }
        table { width: 100%; }
        th { background: #f8f9fa; padding: 10px; font-size: 13px; }
        td { padding: 10px; font-size: 14px; border-bottom: 1px solid #eee; }
        .badge-gain { background: #d4edda; color: #155724; padding: 4px 10px; border-radius: 10px; font-size: 12px; }
        .badge-perte { background: #f8d7da; color: #721c24; padding: 4px 10px; border-radius: 10px; font-size: 12px; }
        .text-gain { color: #28a745; font-weight: bold; }
        .text-perte { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="welcome">
                👋 Bonjour <strong><?php echo htmlspecialchars($_SESSION['user_email']); ?></strong><br>
                <small class="text-muted">Votre tableau de bord personnel</small>
            </div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <span class="class-badge class-<?php echo $_SESSION['user_classe']; ?>">
                    <?php echo strtoupper($_SESSION['user_classe']); ?>
                </span>
                <a href="index.php?route=user/profile" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-person"></i> Profil
                </a>
                <a href="index.php?route=logout" class="logout-btn">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats">
            <div class="stat-card gains">
                <div class="icon">📈</div>
                <div class="value"><?php echo number_format($totals['gains'], 2, ',', ' '); ?> Ar</div>
                <div class="label">Total Gains</div>
            </div>
            <div class="stat-card pertes">
                <div class="icon">📉</div>
                <div class="value"><?php echo number_format($totals['pertes'], 2, ',', ' '); ?> Ar</div>
                <div class="label">Total Pertes</div>
            </div>
            <div class="stat-card solde">
                <div class="icon">💰</div>
                <div class="value"><?php echo number_format($totals['solde_actuel'], 2, ',', ' '); ?> Ar</div>
                <div class="label">Solde Actuel</div>
            </div>
        </div>

        <!-- Graphique -->
        <div class="section">
            <h5>📊 Comparaison Gains / Pertes</h5>
            <canvas id="userChart" height="250"></canvas>
        </div>

        <!-- Dernières transactions -->
        <div class="section">
            <h5>📋 Dernières transactions</h5>
            <?php if (!empty($history)): ?>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Description</th>
                        <th>Catégorie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $row): ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($row['date_transaction'])); ?></td>
                        <td>
                            <span class="<?php echo $row['type'] === 'gain' ? 'badge-gain' : 'badge-perte'; ?>">
                                <?php echo $row['type'] === 'gain' ? '✅ Gain' : '❌ Perte'; ?>
                            </span>
                        </td>
                        <td class="<?php echo $row['type'] === 'gain' ? 'text-gain' : 'text-perte'; ?>">
                            <?php echo number_format($row['montant'], 2, ',', ' '); ?> Ar
                        </td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p class="text-muted">Aucune transaction récente.</p>
            <?php endif; ?>
            <a href="index.php?route=user/history" class="btn btn-outline-secondary btn-sm mt-2">
                Voir tout l'historique →
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('userChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Gains', 'Pertes'],
                datasets: [{
                    data: [<?php echo $totals['gains']; ?>, <?php echo $totals['pertes']; ?>],
                    backgroundColor: ['rgba(40, 167, 69, 0.7)', 'rgba(220, 53, 69, 0.7)'],
                    borderWidth: 2,
                    borderRadius: 10
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => v.toLocaleString('fr-FR') + ' Ar' }
                    }
                }
            }
        });
    </script>
</body>
</html>