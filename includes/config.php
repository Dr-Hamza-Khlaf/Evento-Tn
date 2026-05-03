<?php

// ========================================
// 🌐 BASE CONFIG
// ========================================
define('APP_NAME', 'EventoTN');
define('APP_ENV', 'local'); // local | production

// 🔥 BASE URL (CHANGE ONLY THIS IF NEEDED)
define('BASE_URL', 'http://localhost/evento');


// ========================================
// 🔐 SESSION (START ONCE)
// ========================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ========================================
// 🐞 ERROR HANDLING
// ========================================
if (APP_ENV === 'local') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}


// ========================================
// 🌍 TIMEZONE
// ========================================
date_default_timezone_set('UTC');


// ========================================
// 🔑 SECURITY / APP KEY
// ========================================
define('APP_KEY', 'evento_secret_key_123'); // change in production


// ========================================
// 🧠 HELPER FUNCTIONS (GLOBAL)
// ========================================

// Safe URL helper (optional use)
function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}