<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

// Get ID safely
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    set_flash('error', 'Invalid event ID.');
    redirect(BASE_URL . '/admin/index.php');
}

// ==============================
// 🔥 DELETE RELATED DATA FIRST
// ==============================
try {

    // Delete registrations
    $stmt = $pdo->prepare("DELETE FROM registrations WHERE event_id = ?");
    $stmt->execute([$id]);

    // Delete sponsors
    $stmt = $pdo->prepare("DELETE FROM sponsors WHERE event_id = ?");
    $stmt->execute([$id]);

    // Delete event
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$id]);

    set_flash('success', 'Event deleted successfully.');

} catch (PDOException $e) {

    set_flash('error', 'Error deleting event.');
}

// Redirect correctly
redirect(BASE_URL . '/admin/index.php');