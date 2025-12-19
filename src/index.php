<?php
echo "<h1>Test de la Stack Docker Compose</h1>";

// 1. Test PHP
echo "<p>✅ PHP fonctionne (Version " . phpversion() . ")</p>";

// 2. Test Connexion MySQL
// Attention : 'db' est le nom du service dans docker-compose.yml !
$servername = "db";
$username = "user"; // Vos identifiants du docker-compose
$password = "user";
$dbname = "cesi";

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification
if ($conn->connect_error) {
  die("<p style='color:red'>❌ Echec de la connexion MySQL : " . $conn->connect_error . "</p>");
}

echo "<p style='color:green'>✅ Succès ! PHP est connecté à MySQL.</p>";
?>
