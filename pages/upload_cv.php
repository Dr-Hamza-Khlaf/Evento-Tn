<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$userId = $_SESSION['user']['id'];

if (!empty($_FILES['cv']['name'])) {

    $allowed = ['pdf', 'doc', 'docx'];
    $ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        set_flash('error', 'Invalid file type.');
        redirect(BASE_URL . '/pages/dashboard.php');
    }

    $uploadDir = __DIR__ . '/../uploads/';
    $filename = 'cv_' . time() . '.' . $ext;
    $target = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['cv']['tmp_name'], $target)) {

        $cvPath = 'uploads/' . $filename;

        $stmt = $pdo->prepare("UPDATE users SET cv = ? WHERE id = ?");
        $stmt->execute([$cvPath, $userId]);

        // update session
        $_SESSION['user']['cv'] = $cvPath;

        set_flash('success', 'CV uploaded successfully!');
    } else {
        set_flash('error', 'Upload failed.');
    }
}

redirect(BASE_URL . '/pages/dashboard.php');