<?php

require_once __DIR__ . '/../config/database.php';

class Finance
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getUserTotals($userId)
    {
        $stmt = $this->conn->prepare("
        SELECT
        COALESCE(SUM(montant),0)
        AS total
        FROM gains
        WHERE user_id=?
    ");

        $stmt->execute([$userId]);

        $gain = (float) $stmt->fetch()['total'];


        $stmt = $this->conn->prepare("
        SELECT
        COALESCE(SUM(montant),0)
        AS total
        FROM pertes
        WHERE user_id=?
    ");

        $stmt->execute([$userId]);

        $perte = (float) $stmt->fetch()['total'];

        return [

            'gains' => $gain,

            'pertes' => $perte,

            'solde' => $gain - $perte

        ];
    }

    public function getUserTransactionHistory($userId, $limit = 0)
    {
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

    public function getLastTransactions($limit = 10)
    {

        $sql = "
SELECT
u.username,
g.montant,
'Gain' AS type,
g.description,
g.date_gain AS date
FROM gains g
INNER JOIN users u
ON u.id=g.user_id
UNION ALL
SELECT
u.username,
p.montant,
'Perte',
p.description,
p.date_perte
FROM pertes p
INNER JOIN users u
ON u.id=p.user_id
ORDER BY date DESC
LIMIT ?
";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(1, $limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getUserTrend($userId, $period = 'month')
    {
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

    public function addGain($userId, $amount, $description, $source, $date)
    {
        $query = "INSERT INTO gains (user_id, montant, description, source, date_gain) VALUES (:user_id, :montant, :description, :source, :date_gain)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':montant', $amount);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':source', $source);
        $stmt->bindParam(':date_gain', $date);
        return $stmt->execute();
    }

    public function addPerte($userId, $amount, $description, $categorie, $date)
    {
        $query = "INSERT INTO pertes (user_id, montant, description, categorie, date_perte) VALUES (:user_id, :montant, :description, :categorie, :date_perte)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':montant', $amount);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':categorie', $categorie);
        $stmt->bindParam(':date_perte', $date);
        return $stmt->execute();
    }

    public function getTotalGains()
    {
        $sql = "SELECT COALESCE(SUM(montant),0)
            AS total
            FROM gains";

        $stmt = $this->conn->query($sql);

        return (float) $stmt->fetch()['total'];
    }

    public function getTotalPertes()
    {
        $sql = "SELECT COALESCE(SUM(montant),0)
            AS total
            FROM pertes";

        $stmt = $this->conn->query($sql);

        return (float) $stmt->fetch()['total'];
    }

    public function getSoldeGeneral()
    {
        return
            $this->getTotalGains()
            -
            $this->getTotalPertes();
    }

    public function getTodayGains()
    {
        $sql = "SELECT COALESCE(SUM(montant),0) AS total
            FROM gains
            WHERE date_gain = CURDATE()";

        return (float) $this->conn
            ->query($sql)
            ->fetch()['total'];
    }

    public function getTodayPertes()
    {
        $sql = "SELECT COALESCE(SUM(montant),0) AS total
            FROM pertes
            WHERE date_perte = CURDATE()";

        return (float) $this->conn
            ->query($sql)
            ->fetch()['total'];
    }

    public function getMonthGains()
    {
        $sql = "
        SELECT COALESCE(SUM(montant),0) AS total
        FROM gains
        WHERE MONTH(date_gain)=MONTH(CURDATE())
        AND YEAR(date_gain)=YEAR(CURDATE())
    ";

        return (float) $this->conn
            ->query($sql)
            ->fetch()['total'];
    }

    public function getMonthPertes()
    {
        $sql = "
        SELECT COALESCE(SUM(montant),0) AS total
        FROM pertes
        WHERE MONTH(date_perte)=MONTH(CURDATE())
        AND YEAR(date_perte)=YEAR(CURDATE())
    ";

        return (float) $this->conn
            ->query($sql)
            ->fetch()['total'];
    }

    public function getYearGains()
    {
        $sql = "
        SELECT COALESCE(SUM(montant),0) AS total
        FROM gains
        WHERE YEAR(date_gain)=YEAR(CURDATE())
    ";

        return (float) $this->conn
            ->query($sql)
            ->fetch()['total'];
    }

    public function getYearPertes()
    {
        $sql = "
        SELECT COALESCE(SUM(montant),0) AS total
        FROM pertes
        WHERE YEAR(date_perte)=YEAR(CURDATE())
    ";

        return (float) $this->conn
            ->query($sql)
            ->fetch()['total'];
    }

    public function getMonthlyStatistics()
    {
        $sql = "

    SELECT
        mois,
        SUM(gains) gains,
        SUM(pertes) pertes

    FROM

    (

        SELECT
        DATE_FORMAT(date_gain,'%b') mois,
        montant gains,
        0 pertes
        FROM gains

        UNION ALL

        SELECT
        DATE_FORMAT(date_perte,'%b'),
        0,
        montant
        FROM pertes

    ) stats

    GROUP BY mois

    ";

        return $this->conn
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}
