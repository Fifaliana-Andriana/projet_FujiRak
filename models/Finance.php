<?php

require_once __DIR__ . '/../config/database.php';

class Finance {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getTotalGainsLosses() {
        $stmtGains = $this->conn->prepare('SELECT COALESCE(SUM(montant), 0) AS total_gains FROM gains');
        $stmtGains->execute();
        $gains = $stmtGains->fetch(PDO::FETCH_ASSOC)['total_gains'];

        $stmtPerte = $this->conn->prepare('SELECT COALESCE(SUM(montant), 0) AS total_pertes FROM pertes');
        $stmtPerte->execute();
        $pertes = $stmtPerte->fetch(PDO::FETCH_ASSOC)['total_pertes'];

        return [
            'gains' => (float) $gains,
            'pertes' => (float) $pertes,
            'solde' => (float) $gains - (float) $pertes
        ];
    }

    public function getUserTotals($userId) {
        $stmtGains = $this->conn->prepare('SELECT COALESCE(SUM(montant), 0) AS total_gains FROM gains WHERE user_id = :user_id');
        $stmtGains->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmtGains->execute();
        $totalGains = (float) $stmtGains->fetch(PDO::FETCH_ASSOC)['total_gains'];

        $stmtPerte = $this->conn->prepare('SELECT COALESCE(SUM(montant), 0) AS total_pertes FROM pertes WHERE user_id = :user_id');
        $stmtPerte->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmtPerte->execute();
        $totalPerte = (float) $stmtPerte->fetch(PDO::FETCH_ASSOC)['total_pertes'];

        $stmtSolde = $this->conn->prepare('SELECT solde_initial, solde_actuel FROM soldes WHERE user_id = :user_id ORDER BY date_mise_a_jour DESC LIMIT 1');
        $stmtSolde->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmtSolde->execute();
        $soldeRow = $stmtSolde->fetch(PDO::FETCH_ASSOC);

        $initial = $soldeRow ? (float) $soldeRow['solde_initial'] : 0.0;
        $soldeActuel = $soldeRow ? (float) $soldeRow['solde_actuel'] : $initial + $totalGains - $totalPerte;
        $computedSolde = $initial + $totalGains - $totalPerte;

        return [
            'gains' => $totalGains,
            'pertes' => $totalPerte,
            'solde_initial' => $initial,
            'solde_actuel' => $soldeActuel,
            'solde_calculé' => $computedSolde,
            'balance' => $computedSolde
        ];
    }

    public function getUserTransactionHistory($userId, $limit = 0) {
        $sql = "SELECT id, 'gain' AS type, montant, description, source AS category, date_gain AS date_transaction, date_creation FROM gains WHERE user_id = :user_id"
            . " UNION ALL "
            . "SELECT id, 'perte' AS type, montant, description, categorie AS category, date_perte AS date_transaction, date_creation FROM pertes WHERE user_id = :user_id"
            . " ORDER BY date_transaction DESC, date_creation DESC";

        if ($limit > 0) {
            $sql .= " LIMIT " . intval($limit);
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserTrend($userId, $period = 'month') {
        $dateFormat = '%Y-%m-%d';

        switch ($period) {
            case 'year':
                $dateFormat = '%Y';
                break;
            case 'month':
                $dateFormat = '%Y-%m';
                break;
            default:
                $dateFormat = '%Y-%m-%d';
                break;
        }

        $stmtGains = $this->conn->prepare(
            "SELECT DATE_FORMAT(date_gain, '$dateFormat') AS period, COALESCE(SUM(montant), 0) AS total FROM gains WHERE user_id = :user_id GROUP BY period ORDER BY period ASC"
        );
        $stmtGains->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmtGains->execute();
        $gains = $stmtGains->fetchAll(PDO::FETCH_ASSOC);

        $stmtPerte = $this->conn->prepare(
            "SELECT DATE_FORMAT(date_perte, '$dateFormat') AS period, COALESCE(SUM(montant), 0) AS total FROM pertes WHERE user_id = :user_id GROUP BY period ORDER BY period ASC"
        );
        $stmtPerte->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmtPerte->execute();
        $pertes = $stmtPerte->fetchAll(PDO::FETCH_ASSOC);

        $labels = [];
        foreach ($gains as $row) {
            $labels[$row['period']] = true;
        }
        foreach ($pertes as $row) {
            $labels[$row['period']] = true;
        }

        ksort($labels);
        $labels = array_keys($labels);

        $gainsByPeriod = array_column($gains, 'total', 'period');
        $pertesByPeriod = array_column($pertes, 'total', 'period');

        $gainData = [];
        $perteData = [];
        foreach ($labels as $label) {
            $gainData[] = isset($gainsByPeriod[$label]) ? (float) $gainsByPeriod[$label] : 0.0;
            $perteData[] = isset($pertesByPeriod[$label]) ? (float) $pertesByPeriod[$label] : 0.0;
        }

        return [
            'labels' => $labels,
            'gains' => $gainData,
            'pertes' => $perteData,
            'period' => $period
        ];
    }

    public function addGain($userId, $amount, $description, $source, $date) {
        $query = "INSERT INTO gains (user_id, montant, description, source, date_gain) VALUES (:user_id, :montant, :description, :source, :date_gain)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':montant', $amount);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':source', $source);
        $stmt->bindParam(':date_gain', $date);
        return $stmt->execute();
    }

    public function addPerte($userId, $amount, $description, $categorie, $date) {
        $query = "INSERT INTO pertes (user_id, montant, description, categorie, date_perte) VALUES (:user_id, :montant, :description, :categorie, :date_perte)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':montant', $amount);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':categorie', $categorie);
        $stmt->bindParam(':date_perte', $date);
        return $stmt->execute();
    }
}
