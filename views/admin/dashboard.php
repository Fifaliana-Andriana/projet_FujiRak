<?php
// Variables attendues : $totalUsers, $classCounts, $registrationStats, $summary, $periodLabel
$chartLabels = json_encode(array_column($registrationStats, 'period'));
$chartData = json_encode(array_column($registrationStats, 'total'));

function classeLabel($classe) {
    return match ($classe) {
        'gold' => 'Gold',
        'plus' => 'Plus',
        default => 'Simple',
    };
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord admin - FujiRak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>Tableau de bord administrateur</h1>
                <p class="text-muted">Vue générale des gains, pertes, soldes et des utilisateurs.</p>
            </div>
            <div>
                <a href="index.php?route=admin/users" class="btn btn-outline-primary me-2">Gestion des utilisateurs</a>
                <a href="index.php?route=logout" class="btn btn-danger">Déconnexion</a>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm p-3 h-100">
                    <h5>Total utilisateurs</h5>
                    <p class="display-6 mb-0"><?php echo number_format($totalUsers); ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm p-3 h-100">
                    <h5>Total gains</h5>
                    <p class="display-6 text-success mb-0"><?php echo number_format($summary['gains'], 2, ',', ' '); ?> Ar</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm p-3 h-100">
                    <h5>Total pertes</h5>
                    <p class="display-6 text-danger mb-0"><?php echo number_format($summary['pertes'], 2, ',', ' '); ?> Ar</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm p-3 h-100">
                    <h5>Solde global</h5>
                    <p class="display-6 <?php echo $summary['solde'] >= 0 ? 'text-success' : 'text-danger'; ?> mb-0"><?php echo number_format($summary['solde'], 2, ',', ' '); ?> Ar</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5>Intégrations par <?php echo ucfirst($periodLabel); ?></h5>
                            <p class="text-muted mb-0">Filtrer par jour, mois ou année.</p>
                        </div>
                        <div>
                            <a href="index.php?route=admin/dashboard&period=day" class="btn btn-sm btn-outline-secondary<?php echo $periodLabel === 'day' ? ' active' : ''; ?>">Jour</a>
                            <a href="index.php?route=admin/dashboard&period=month" class="btn btn-sm btn-outline-secondary<?php echo $periodLabel === 'month' ? ' active' : ''; ?>">Mois</a>
                            <a href="index.php?route=admin/dashboard&period=year" class="btn btn-sm btn-outline-secondary<?php echo $periodLabel === 'year' ? ' active' : ''; ?>">Année</a>
                        </div>
                    </div>
                    <canvas id="registrationChart" height="220"></canvas>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm p-4 h-100">
                    <h5>Répartition des classes</h5>
                    <div class="mt-3">
                        <?php foreach ($classCounts as $row): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="badge bg-<?php echo $row['classe'] === 'gold' ? 'warning text-dark' : ($row['classe'] === 'plus' ? 'warning text-dark' : 'secondary'); ?>">
                                        <?php echo classeLabel($row['classe']); ?>
                                    </span>
                                </div>
                                <div><strong><?php echo $row['total']; ?></strong></div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($classCounts)): ?>
                            <p class="text-muted">Aucun utilisateur actif pour le moment.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const labels = <?php echo $chartLabels ?: '[]'; ?>;
        const data = <?php echo $chartData ?: '[]'; ?>;

        new Chart(document.getElementById('registrationChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nouveaux membres',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
