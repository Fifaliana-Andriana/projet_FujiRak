<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique - FujiRak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>Historique des transactions</h1>
                <p class="text-muted">Liste complète de vos gains et pertes.</p>
            </div>
            <div>
                <a href="index.php?route=user/home" class="btn btn-secondary me-2">Retour au dashboard</a>
                <a href="index.php?route=logout" class="btn btn-danger">Déconnexion</a>
            </div>
        </div>

        <div class="card shadow-sm p-4">
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
                            <tr><td colspan="5" class="text-center text-muted">Aucune transaction trouvée.</td></tr>
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
</body>
</html>
