<?php

require_once __DIR__ . '/config.php'; // BASE_URL + session

// ========================================
// 🔐 SECURITY HELPERS
// ========================================
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}


// ========================================
// 🔁 REDIRECT (SAFE)
// ========================================
function redirect(string $path): void {

    // If already full URL → keep it
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        header("Location: $path");
    } else {
        header("Location: " . $path);
    }

    exit;
}


// ========================================
// 🔔 FLASH MESSAGES
// ========================================
function set_flash(string $type, string $message): void {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }

    $_SESSION['flash'][] = [
        'type' => $type,
        'message' => $message
    ];
}

function get_flash(): array {
    $flash = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flash;
}


// ========================================
// 👤 AUTH CHECKS
// ========================================
function is_logged_in(): bool {
    return isset($_SESSION['user']);
}

function is_admin(): bool {
    return is_logged_in() && ($_SESSION['user']['role'] ?? '') === 'admin';
}


// ========================================
// 🔒 PROTECTED ROUTES
// ========================================
function require_login(): void {
    if (!is_logged_in()) {
        set_flash('error', 'Please login first.');
        redirect(BASE_URL . '/auth/login.php');
    }
}

function require_admin(): void {
    if (!is_admin()) {
        set_flash('error', 'Admin access required.');

        // 🔥 REDIRECT TO ADMIN LOGIN (IMPORTANT FIX)
        redirect(BASE_URL . '/admin/login.php');
    }
}


// ========================================
// 🔐 OPTIONAL HELPERS (BONUS)
// ========================================

// Force logout safely
function logout(): void {
    session_unset();
    session_destroy();
    redirect(BASE_URL . '/auth/login.php');
}

// Get current user safely
function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}