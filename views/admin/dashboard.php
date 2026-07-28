<?php

require_once __DIR__ . '/../../models/Finance.php';

$finance = new Finance();

$totalGains = $finance->getTotalGains();
$totalPertes = $finance->getTotalPertes();
$soldeGeneral = $finance->getSoldeGeneral();

$lastTransactions = $finance->getLastTransactions(10);

?>

<div class="container-fluid">

    <div class="page-header mb-4">
        <h2 class="fw-bold">
            Dashboard Administrateur
        </h2>

        <p class="text-muted">
            Vue générale des finances
        </p>
    </div>

    <div class="row g-4 mb-4 d-flex justify-content-evenly">

        <div class="col-md-4 bg-success d-flex justify-content-center w-25 card_transaction">
            <div class="dashboard-card gain-card">

                <i class="bi bi-graph-up-arrow"></i>

                <h6>Total des gains</h6>

                <h3>
                    <?= number_format($totalGains, 2, ',', ' ') ?> €
                </h3>

            </div>
        </div>

        <div class="col-md-4 bg-danger d-flex justify-content-center w-25 card_transaction">
            <div class="dashboard-card perte-card">

                <i class="bi bi-graph-down-arrow"></i>

                <h6>Total des pertes</h6>

                <h3>
                    <?= number_format($totalPertes, 2, ',', ' ') ?> €
                </h3>

            </div>
        </div>

        <div class="col-md-4 bg-primary d-flex justify-content-center w-25 card_transaction">
            <div class="dashboard-card solde-card">

                <i class="bi bi-wallet2"></i>

                <h6>Solde général</h6>

                <h3>
                    <?= number_format($soldeGeneral, 2, ',', ' ') ?> €
                </h3>

            </div>
        </div>

    </div>

    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h5>Graphique général</h5>

        </div>

        <div class="card-body">

            <canvas id="financeChart"></canvas>

        </div>

    </div>

    <div class="card shadow-sm">

        <div class="card-header">

            <h5>Dernières transactions</h5>

        </div>

        <div class="card-body table-responsive">

            <table class="table table-hover">

                <thead>

                    <tr>

                        <th>Utilisateur</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Description</th>
                        <th>Date</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($lastTransactions as $row): ?>

                        <tr>

                            <td><?= htmlspecialchars($row['username']) ?></td>

                            <td>

                                <?php if ($row['type'] == "Gain"): ?>

                                    <span class="badge bg-success">Gain</span>

                                <?php else: ?>

                                    <span class="badge bg-danger">Perte</span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?= number_format($row['montant'], 2, ',', ' ') ?> €

                            </td>

                            <td>

                                <?= htmlspecialchars($row['description']) ?>

                            </td>

                            <td>

                                <?= $row['date'] ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>

    const financeChart = new Chart(document.getElementById('financeChart'), {

        type: 'bar',

        data: {

            labels: ['Gains', 'Pertes', 'Solde'],

            datasets: [{

                data: [
                    <?= $totalGains ?>,
                    <?= $totalPertes ?>,
                    <?= $soldeGeneral ?>
                ]

            }]

        },

        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }

    });

</script>