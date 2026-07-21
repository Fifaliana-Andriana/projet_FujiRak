<?php
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Finance.php';

$userModel = new User();
$financeModel = new Finance();

$totalUsers = $userModel->getTotalUsers();
$statsByClass = $userModel->getStatsByClass();

// ✅ Utilise TA méthode getTotalGainsLosses()
$totals = $financeModel->getTotalGainsLosses();
$totalGains = $totals['gains'];
$totalPertes = $totals['pertes'];
$soldeGlobal = $totals['solde'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FujiRak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --sidebar-width: 250px;
        }
        body {
            margin: 0;
            padding: 0;
            background: #f0f2f5;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            padding: 20px 0;
            z-index: 1000;
        }
        .sidebar .logo {
            text-align: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar a {
            display: block;
            color: #aaa;
            padding: 15px 25px;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 15px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .sidebar a i {
            margin-right: 10px;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card .icon {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .stat-card .value {
            font-size: 28px;
            font-weight: bold;
        }
        .stat-card .label {
            color: #888;
            font-size: 14px;
        }
        .stat-card.gains { border-bottom: 4px solid #28a745; }
        .stat-card.pertes { border-bottom: 4px solid #dc3545; }
        .stat-card.solde { border-bottom: 4px solid #007bff; }
        .stat-card.users { border-bottom: 4px solid #ffc107; }
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }
        .class-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 13px;
        }
        .class-simple { background: #C0C0C0; color: #333; }
        .class-gold { background: #FFD700; color: #333; }
        .class-plus { background: #FFD700; color: #333; position: relative; }
        .class-plus::after {
            content: '+';
            position: absolute;
            top: -5px;
            right: -5px;
            background: #FF4500;
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .user-count-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }
        .user-count-card:hover {
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">⚙️ FujiRak</div>
        <a href="index.php?route=admin/dashboard" class="active">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="index.php?route=admin/users">
            <i class="bi bi-people"></i> Utilisateurs
        </a>
        <a href="index.php?route=logout" style="margin-top: 50px; color: #ff6b6b;">
            <i class="bi bi-box-arrow-right"></i> Déconnexion
        </a>
    </div>

    <div class="main-content">
        <h1 class="mb-4">📊 Tableau de Bord Administrateur</h1>
        <p class="text-muted mb-4">Bienvenue, <?php echo $_SESSION['user_email']; ?></p>

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card users">
                    <div class="icon">👥</div>
                    <div class="value"><?php echo $totalUsers; ?></div>
                    <div class="label">Utilisateurs Total</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card gains">
                    <div class="icon">📈</div>
                    <div class="value"><?php echo number_format($totalGains, 0, ',', ' '); ?> €</div>
                    <div class="label">Total Gains</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card pertes">
                    <div class="icon">📉</div>
                    <div class="value"><?php echo number_format($totalPertes, 0, ',', ' '); ?> €</div>
                    <div class="label">Total Pertes</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card solde">
                    <div class="icon">💰</div>
                    <div class="value"><?php echo number_format($soldeGlobal, 0, ',', ' '); ?> €</div>
                    <div class="label">Solde Global</div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <?php 
            $classNames = ['simple' => 'Silver', 'gold' => 'Gold', 'plus' => 'Gold Plus'];
            foreach ($statsByClass as $stat): 
            ?>
            <div class="col-md-4 mb-3">
                <div class="user-count-card">
                    <span class="class-badge class-<?php echo $stat['classe']; ?>">
                        <?php echo $classNames[$stat['classe']]; ?>
                    </span>
                    <h3 class="mt-2"><?php echo $stat['total']; ?></h3>
                    <small class="text-muted">utilisateurs</small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="chart-container">
            <h5 class="mb-3">📈 Statistiques Globales</h5>
            <canvas id="adminChart" height="300"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('adminChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Gains', 'Pertes', 'Solde'],
                datasets: [{
                    label: 'Montants (€)',
                    data: [<?php echo $totalGains; ?>, <?php echo $totalPertes; ?>, <?php echo $soldeGlobal; ?>],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.7)',
                        'rgba(220, 53, 69, 0.7)',
                        'rgba(0, 123, 255, 0.7)'
                    ],
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
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('fr-FR') + ' €';
                            }
                        }
                    }
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>