<?php
$trendLabels = array_column($registrationStats, 'periode');
$trendData = array_map('intval', array_column($registrationStats, 'total'));
?>

<div class="page-header mb-4">
    <h2 class="fw-bold mb-1">Statistiques utilisateurs</h2>
    <p class="text-muted mb-0">Croissance et répartition de la communauté Finixiias</p>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card">
            <div class="card-icon"><i class="bi bi-people-fill"></i></div>
            <div class="card-content">
                <small>Total utilisateurs</small>
                <h2><?= $totalUsers ?></h2>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card">
            <div class="card-icon class-badge" style="background: linear-gradient(
    135deg,
    #c0c0c0 0%,
    #f5f5f5 45%,
    #a8a8a8 55%,
    #eeeeee 75%,
    #8e8e8e 100%
  );
  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.6);
  box-shadow: 0 2px 6px rgba(120, 120, 120, 0.4);"><i class="bi bi-person"></i></div>
            <div class="card-content">
                <small>Classe Simple</small>
                <h2><?= $classCounts['simple'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card">
            <div class="card-icon class-badge" style="
  background: linear-gradient(
    135deg,
    #bf953f 0%,
    #fcf6ba 45%,
    #b38728 55%,
    #fbf5b7 75%,
    #aa771c 100%
  );"><i class="bi bi-person"></i></div>
            <div class="card-content">
                <small>Classe Gold</small>
                <h2><?= $classCounts['gold'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card">
            <div class="card-icon class-badge" style="background: linear-gradient(
    135deg,
    #bf953f 0%,
    #fcf6ba 45%,
    #b38728 55%,
    #fbf5b7 75%,
    #aa771c 100%
  );"><i class="bi bi-person"></i>
                <p class="bolder">+</p>
            </div>
            <div class="card-content">
                <small>Classe Gold+</small>
                <h2><?= $classCounts['plus'] ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Évolution des inscriptions</h5>
    </div>
    <div class="card-body"><canvas id="usersChart" data-trend-chart data-single-series
            data-endpoint="index.php?route=admin/statistics-json"
            data-initial='<?= json_encode(['labels' => $trendLabels, 'gains' => $trendData], JSON_HEX_APOS) ?>'
            data-label1="Inscriptions">
        </canvas>
        <small class="text-muted d-block mt-3">
            Le graphique compte le nombre d'inscriptions.
        </small>
    </div>
</div>