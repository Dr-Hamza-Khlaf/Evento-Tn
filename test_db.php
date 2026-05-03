<?php
require_once 'includes/db.php';

$users = $pdo->query("SELECT email FROM users")->fetchAll();

echo "<pre>";
print_r($users);