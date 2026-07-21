<?php
$chartLabels = json_encode($trend['labels']);
$chartGains = json_encode($trend['gains']);
$chartPertes = json_encode($trend['pertes']);
$totalGain = number_format($totals['gains'], 2, ',', ' ');
$totalPerte = number_format($totals['pertes'], 2, ',', ' ');
$balance = number_format($totals['balance'], 2, ',', ' ');
$class = $_SESSION['user_classe'];
$username = htmlspecialchars($_SESSION['user_username']);
$fullname = htmlspecialchars(trim($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard utilisateur - FujiRak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .classe-simple {background: silver; color: #222;}
        .classe-gold {background: gold; color: #222;}
        .classe-plus {background: #d4af37; color: #222;}
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4 gap-3">
            <div>
                <h1>Bonjour <?php echo $fullname ?: $username; ?></h1>
                <p class="text-muted">Votre tableau de bord personnel de gestion des gains et pertes.</p>
            </div>
            <div class="text-end">
                <span class="badge px-3 py-2 classe-<?php echo htmlspecialchars($class); ?>">Classe : <?php echo ucfirst($class); ?></span>
                <div class="mt-2">
                    <a href="index.php?route=user/profile" class="btn btn-outline-primary btn-sm">Profil</a>
                    <a href="index.php?route=user/history" class="btn btn-outline-secondary btn-sm">Historique</a>
                    <a href="index.php?route=logout" class="btn btn-danger btn-sm">Déconnexion</a>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm p-4 h-100">
                    <h5>Gains</h5>
                    <p class="display-6 text-success mb-0"><?php echo $totalGain; ?> Ar</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-4 h-100">
                    <h5>Pertes</h5>
                    <p class="display-6 text-danger mb-0"><?php echo $totalPerte; ?> Ar</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-4 h-100">
                    <h5>Solde</h5>
                    <p class="display-6 <?php echo $totals['balance'] >= 0 ? 'text-success' : 'text-danger'; ?> mb-0"><?php echo $balance; ?> Ar</p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm p-4 mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-3">
                <div>
                    <h2>Comparaison des gains et pertes</h2>
                    <p class="text-muted mb-0">Affichage par <?php echo ucfirst($trend['period']); ?>.</p>
                </div>
                <div class="d-flex flex-column flex-md-row gap-2">
                    <div>
                        <a href="index.php?route=user/home&period=day" class="btn btn-outline-secondary btn-sm<?php echo $trend['period'] === 'day' ? ' active' : ''; ?>">Jour</a>
                        <a href="index.php?route=user/home&period=month" class="btn btn-outline-secondary btn-sm<?php echo $trend['period'] === 'month' ? ' active' : ''; ?>">Mois</a>
                        <a href="index.php?route=user/home&period=year" class="btn btn-outline-secondary btn-sm<?php echo $trend['period'] === 'year' ? ' active' : ''; ?>">Année</a>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-chart-type="line">Courbe</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-chart-type="bar">Barres</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-chart-type="pie">Circulaire</button>
                    </div>
                </div>
            </div>
            <canvas id="financeChart" height="180"></canvas>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card shadow-sm p-4">
                    <h3>Ajouter un gain</h3>
                    <form action="index.php?route=user/add-gain" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Montant</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Source</label>
                            <input type="text" name="source" class="form-control" placeholder="Commission, Salaire...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <button class="btn btn-success">Ajouter le gain</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm p-4">
                    <h3>Ajouter une perte</h3>
                    <form action="index.php?route=user/add-perte" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Montant</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catégorie</label>
                            <input type="text" name="categorie" class="form-control" placeholder="Frais, Achat...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <button class="btn btn-danger">Ajouter la perte</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card shadow-sm p-4">
            <h3>Dernières transactions</h3>
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
                            <tr><td colspan="5" class="text-center text-muted">Aucune transaction récente.</td></tr>
                        <?php else: ?>
                            <?php foreach ($transactions as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['date_transaction']); ?></td>
                                    <td><?php echo $item['type'] === 'gain' ? '<span class="badge bg-success">Gain</span>' : '<span class="badge bg-danger">Perte</span>'; ?></td>
                                    <td><?php echo number_format($item['montant'], 2, ',', ' '); ?> Ar</td>
                                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                                    <td><?php echo htmlspecialchars($item['category']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('financeChart').getContext('2d');
        let chartType = 'line';
        const chartData = {
            labels: <?php echo $chartLabels; ?>,
            datasets: [
                {
                    label: 'Gains',
                    data: <?php echo $chartGains; ?>,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.25)',
                    fill: true,
                    tension: 0.35
                },
                {
                    label: 'Pertes',
                    data: <?php echo $chartPertes; ?>,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.25)',
                    fill: true,
                    tension: 0.35
                }
            ]
        };
        let financeChart = new Chart(ctx, {
            type: chartType,
            data: chartData,
            options: {
                responsive: true,
                scales: {
                    y: {beginAtZero: true}
                }
            }
        });

        document.querySelectorAll('[data-chart-type]').forEach(button => {
            button.addEventListener('click', () => {
                const type = button.getAttribute('data-chart-type');
                if (type === 'pie') {
                    financeChart.destroy();
                    financeChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: ['Gains', 'Pertes'],
                            datasets: [{
                                data: [<?php echo array_sum($trend['gains']); ?>, <?php echo array_sum($trend['pertes']); ?>],
                                backgroundColor: ['#198754', '#dc3545']
                            }]
                        }
                    });
                    return;
                }
                financeChart.destroy();
                financeChart = new Chart(ctx, {
                    type: type,
                    data: chartData,
                    options: {
                        responsive: true,
                        scales: {
                            y: {beginAtZero: true}
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
