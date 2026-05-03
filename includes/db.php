<?php

require_once __DIR__ . '/config.php'; // 🔥 ensure config loaded

$host = '127.0.0.1';   // better than localhost
$dbname = 'evento';
$user = 'root';
$pass = '';

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    // 🔥 DEBUG (remove later)
    // echo "DB CONNECTED"; exit;

} catch (PDOException $e) {

    // 🔥 SHOW REAL ERROR (TEMP)
    die("Database connection failed: " . $e->getMessage());
}