<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL);
}

// 🔹 Sanitize inputs
$event_id = (int)($_POST['event_id'] ?? 0);
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$profile_type = $_POST['profile_type'] ?? null;

// 🔥 FORCE CV REQUIRED
if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
    set_flash('error', 'CV is required.');
    redirect(BASE_URL . '/pages/event.php?id=' . $event_id);
}

// 🔥 VALIDATION
$allowed = ['pdf', 'doc', 'docx'];
$ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    set_flash('error', 'Invalid CV format. Only PDF, DOC, DOCX allowed.');
    redirect(BASE_URL . '/pages/event.php?id=' . $event_id);
}

// 🔥 FILE SIZE LIMIT (5MB)
if ($_FILES['cv']['size'] > 5 * 1024 * 1024) {
    set_flash('error', 'File too large (max 5MB).');
    redirect(BASE_URL . '/pages/event.php?id=' . $event_id);
}

// 🔥 ENSURE UPLOAD FOLDER
$uploadDir = __DIR__ . '/../uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// 🔥 SAFE FILE NAME
$filename = 'cv_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
$target = $uploadDir . $filename;

// 🔥 MOVE FILE (CRITICAL)
if (!move_uploaded_file($_FILES['cv']['tmp_name'], $target)) {
    set_flash('error', 'CV upload failed. Try again.');
    redirect(BASE_URL . '/pages/event.php?id=' . $event_id);
}

// Final path saved in DB
$cvPath = 'uploads/' . $filename;

// 🔥 INSERT
$stmt = $pdo->prepare("
    INSERT INTO registrations 
    (event_id, full_name, email, phone, address, profile_type, cv) 
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $event_id,
    $full_name,
    $email,
    $phone,
    $address,
    $profile_type,
    $cvPath
]);

set_flash('success', 'Registration submitted successfully!');
redirect(BASE_URL . '/pages/event.php?id=' . $event_id);