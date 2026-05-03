<?php
require_once 'includes/db.php';

$password = password_hash('admin123', PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password_hash=? WHERE email=?");
$stmt->execute([$password, 'admin@eventotn.com']);

echo "Admin password updated!";