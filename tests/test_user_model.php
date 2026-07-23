<?php
// test_user_model.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'models/User.php';



require_once 'models/User.php';

echo "<h1>Test du modèle User</h1>";

$user = new User();

// Test 1 : Trouver un utilisateur par email
echo "<h2>Test findByEmail</h2>";
$result = $user->findByEmail('jean.dupont@email.com');
if ($result) {
    echo "<p style='color:green'>✅ Utilisateur trouvé : " . $result['nom'] . " " . $result['prenom'] . "</p>";
    echo "<p>Classe : " . $result['classe'] . " | Rôle : " . $result['role'] . "</p>";
} else {
    echo "<p style='color:red'>❌ Utilisateur non trouvé</p>";
}

// Test 2 : Demande de connexion
echo "<h2>Test createLoginRequest</h2>";
$loginResult = $user->createLoginRequest('jean.dupont@email.com');
if ($loginResult['success']) {
    echo "<p style='color:green'>✅ Code généré : " . $loginResult['code'] . "</p>";
    echo "<p>Email : " . $loginResult['email'] . "</p>";
} else {
    echo "<p style='color:red'>❌ " . $loginResult['message'] . "</p>";
}

// Test 3 : Nombre total d'utilisateurs
echo "<h2>Test getTotalUsers</h2>";
$total = $user->getTotalUsers();
echo "<p>✅ Nombre total d'utilisateurs : " . $total . "</p>";

// Test 4 : Statistiques par classe
echo "<h2>Test getStatsByClass</h2>";
$stats = $user->getStatsByClass();
echo "<ul>";
foreach ($stats as $stat) {
    echo "<li>" . $stat['classe'] . " : " . $stat['total'] . "</li>";
}
echo "</ul>";

echo "<hr><p>✅ Tous les tests sont terminés !</p>";
?>