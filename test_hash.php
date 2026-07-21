<?php
// Test rapide : crée test_hash.php à la racine
$hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

if (password_verify('password123', $hash)) {
    echo "✅ Le mot de passe est bien 'password123'";
} else {
    echo "❌ Le hash ne correspond PAS à 'password123'";
    echo "<br>Nouveau hash : " . password_hash('password123', PASSWORD_BCRYPT);
}
?>