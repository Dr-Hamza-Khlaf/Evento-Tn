<?php
require_once __DIR__ . '/../includes/db.php';

if (!isset($_GET['q'])) {
    echo json_encode([]);
    exit;
}

$q = "%" . $_GET['q'] . "%";

$stmt = $pdo->prepare("
    SELECT id, title, location, image 
    FROM events 
    WHERE title LIKE ? OR location LIKE ? OR category LIKE ?
    LIMIT 5
");

$stmt->execute([$q, $q, $q]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);