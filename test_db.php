<?php
// test_db_cli.php
require_once 'config/database.php';

echo "=== Test de connexion MySQL ===\n\n";

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "✅ Connexion réussie !\n\n";
        
        $stmt = $db->query("SELECT VERSION() as version");
        $result = $stmt->fetch();
        echo "Version MySQL : " . $result['version'] . "\n\n";
        
        $stmt = $db->query("SELECT DATABASE() as db_name");
        $result = $stmt->fetch();
        echo "Base de données : " . $result['db_name'] . "\n\n";
        
        $stmt = $db->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "Tables (" . count($tables) . ") :\n";
        foreach ($tables as $table) {
            echo "  - $table\n";
        }
        
        echo "\n=== Statistiques utilisateurs ===\n";
        $stmt = $db->query("SELECT COUNT(*) as total FROM users");
        $result = $stmt->fetch();
        echo "Total utilisateurs : " . $result['total'] . "\n";
        
        $stmt = $db->query("SELECT role, COUNT(*) as nb FROM users GROUP BY role");
        while ($row = $stmt->fetch()) {
            echo "  {$row['role']} : {$row['nb']}\n";
        }
        
        $stmt = $db->query("SELECT classe, COUNT(*) as nb FROM users GROUP BY classe");
        while ($row = $stmt->fetch()) {
            echo "  {$row['classe']} : {$row['nb']}\n";
        }
        
        echo "\n✅ Test terminé avec succès !\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage() . "\n";
    exit(1);
}
?>