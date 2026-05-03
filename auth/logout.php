<?php
require_once __DIR__ . '/../includes/config.php';   // 🔥 BASE_URL + session
require_once __DIR__ . '/../includes/functions.php';

// Destroy session safely
$_SESSION = [];
session_destroy();

// Optional: flash message
set_flash('success', 'Logged out successfully.');

// 🔥 FIXED REDIRECT
redirect(BASE_URL . '/');