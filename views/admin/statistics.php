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
            <div class="card-icon" style="background:#C0C0C0;"><i class="bi bi-person"></i></div>
            <div class="card-content">
                <small>Classe Simple</small>
                <h2><?= $classCounts['simple'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card">
            <div class="card-icon" style="background:#FFD700;"><i class="bi bi-star-fill"></i></div>
            <div class="card-content">
                <small>Classe Gold</small>
                <h2><?= $classCounts['gold'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card">
            <div class="card-icon" style="background:#FFD700;"><i class="bi bi-star-fill"></i></div>
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
    <div class="card-body">
        <canvas
            id="usersChart"
            data-trend-chart
            height="320"
            data-endpoint="index.php?route=admin/statistics-json"
            data-initial='<?= json_encode(['labels' => $trendLabels, 'gains' => $trendData, 'pertes' => array_fill(0, count($trendData), 0)], JSON_HEX_APOS) ?>'>
        </canvas>
        <small class="text-muted d-block mt-3">
            Le graphique compte les nouvelles inscriptions (les "pertes" sont affichées à zéro ; seule la série verte est pertinente ici).
        </small>
    </div>
</div>
