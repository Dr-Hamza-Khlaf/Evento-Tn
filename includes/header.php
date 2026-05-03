<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EventoTN</title>

  <!-- CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>

<body>

<header class="site-header">
  <div class="container nav">

    <!-- LOGO -->
    <a class="logo" href="<?= BASE_URL ?>/">
      Evento<span>TN</span>
    </a>

    <!-- MOBILE TOGGLE -->
    <div class="menu-toggle" onclick="toggleMenu()">
      ☰
    </div>

    <!-- NAVIGATION -->
    <nav id="mainNav">

      <a href="<?= BASE_URL ?>/#featured">Featured</a>
      <a href="<?= BASE_URL ?>/#upcoming">Upcoming</a>
      <a href="<?= BASE_URL ?>/pages/sponsor.php">Sponsor</a>

      <?php if (is_logged_in()): ?>

        <a href="<?= BASE_URL ?>/pages/dashboard.php" class="nav-link">Dashboard</a>

        <?php if (is_admin()): ?>
          <a href="<?= BASE_URL ?>/admin/index.php" class="nav-link admin">Admin</a>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>/auth/logout.php" class="btn logout">Logout</a>

      <?php else: ?>

        <a href="<?= BASE_URL ?>/auth/login.php" class="nav-link">Login</a>

        <a href="<?= BASE_URL ?>/auth/register.php" class="btn primary">
          Sign up
        </a>

      <?php endif; ?>

    </nav>

  </div>
</header>

<main>

<!-- 🔥 NAVBAR SCRIPT -->
<script>
function toggleMenu() {
  const nav = document.getElementById("mainNav");
  nav.classList.toggle("open");
}
</script>