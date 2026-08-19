<?php
// MMV database connection. Change only these values for your local MySQL setup.
$dbHost = '127.0.0.1'; $dbName = 'mmv_db'; $dbUser = 'root'; $dbPass = '';
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    http_response_code(500); exit('Database connection is unavailable.');
}
?>
