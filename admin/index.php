<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

// STATS
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalEvents = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$totalRegs = $pdo->query("SELECT COUNT(*) FROM registrations")->fetchColumn();
$totalSponsors = $pdo->query("SELECT COUNT(*) FROM sponsors")->fetchColumn();
$totalNews = $pdo->query("SELECT COUNT(*) FROM newsletter")->fetchColumn();

// DATA
$events = $pdo->query("SELECT * FROM events ORDER BY event_date DESC LIMIT 5")->fetchAll();
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();

$regs = $pdo->query("
    SELECT r.*, e.title AS event_title, u.name AS user_name, u.email AS user_email
    FROM registrations r
    LEFT JOIN users u ON r.user_id = u.id
    LEFT JOIN events e ON r.event_id = e.id
    ORDER BY r.created_at DESC LIMIT 10
")->fetchAll();

$sponsors = $pdo->query("
    SELECT s.*, e.title AS event_title 
    FROM sponsors s 
    LEFT JOIN events e ON s.event_id = e.id 
    ORDER BY s.created_at DESC LIMIT 10
")->fetchAll();

$news = $pdo->query("SELECT * FROM newsletter ORDER BY created_at DESC LIMIT 10")->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<style>
.admin-layout { display:flex; gap:20px; }
.sidebar {
    width:230px; background:#111827; color:#fff;
    border-radius:12px; padding:20px;
}
.sidebar a { display:block; color:#cbd5e1; margin:10px 0; text-decoration:none; }
.sidebar a:hover { color:#fff; }

.admin-main { flex:1; }

.stats-grid {
    display:grid; grid-template-columns:repeat(5,1fr);
    gap:15px; margin-bottom:25px;
}

.stat-card {
    background:#fff; padding:20px; border-radius:12px;
    text-align:center; box-shadow:0 6px 20px rgba(0,0,0,0.05);
}

.section {
    background:#fff; padding:20px;
    border-radius:12px; margin-bottom:20px;
    box-shadow:0 6px 20px rgba(0,0,0,0.05);
}

.admin-table { width:100%; border-collapse:collapse; }
.admin-table th, .admin-table td {
    padding:10px; border-bottom:1px solid #eee;
}
.admin-table th { background:#f9fafb; }

.badge {
    padding:4px 8px; border-radius:6px;
    font-size:12px; color:#fff;
}
.badge.user { background:#16a34a; }
.badge.guest { background:#f59e0b; }

.btn-small {
    padding:5px 10px;
    border-radius:6px;
    text-decoration:none;
    font-size:12px;
    margin-right:5px;
}

.btn-edit {
    background:#2563eb;
    color:#fff;
}

.btn-delete {
    background:#dc2626;
    color:#fff;
}
</style>

<div class="container">
<div class="admin-layout">

<!-- SIDEBAR -->
<div class="sidebar">
    <h3>⚡ Admin</h3>
    <a href="#">Dashboard</a>
    <a href="<?= BASE_URL ?>/admin/event_form.php">Create Event</a>
    <a href="<?= BASE_URL ?>/admin/user_form.php">Add User</a>
</div>

<!-- MAIN -->
<div class="admin-main">

<h2>Dashboard 🚀</h2>

<!-- STATS -->
<div class="stats-grid">
    <div class="stat-card"><h3><?= $totalUsers ?></h3><p>Users</p></div>
    <div class="stat-card"><h3><?= $totalEvents ?></h3><p>Events</p></div>
    <div class="stat-card"><h3><?= $totalRegs ?></h3><p>Registrations</p></div>
    <div class="stat-card"><h3><?= $totalSponsors ?></h3><p>Sponsors</p></div>
    <div class="stat-card"><h3><?= $totalNews ?></h3><p>Newsletter</p></div>
</div>

<!-- EVENTS -->
<div class="section">
<h3>Recent Events</h3>
<table class="admin-table">
<tr>
<th>Title</th>
<th>Date</th>
<th>Actions</th>
</tr>

<?php foreach ($events as $e): ?>
<tr>
<td><?= e($e['title']) ?></td>
<td><?= date('M d, Y', strtotime($e['event_date'])) ?></td>

<td>
<a href="<?= BASE_URL ?>/admin/event_form.php?id=<?= $e['id'] ?>" 
   class="btn-small btn-edit">Edit</a>

<a href="<?= BASE_URL ?>/admin/delete_event.php?id=<?= $e['id'] ?>" 
   class="btn-small btn-delete"
   onclick="return confirm('Are you sure you want to delete this event?')">
   Delete
</a>
</td>

</tr>
<?php endforeach; ?>
</table>
</div>

<!-- USERS -->
<div class="section">
<h3>New Users</h3>
<table class="admin-table">
<tr><th>Name</th><th>Email</th></tr>
<?php foreach ($users as $u): ?>
<tr>
<td><?= e($u['name']) ?></td>
<td><?= e($u['email']) ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>

<!-- REGISTRATIONS -->
<div class="section">
<h3>Registrations</h3>
<table class="admin-table">
<tr>
<th>Type</th>
<th>Name</th>
<th>Email</th>
<th>Event</th>
<th>CV</th>
</tr>

<?php foreach ($regs as $r): ?>
<tr>

<td>
<?php if ($r['user_id']): ?>
<span class="badge user">User</span>
<?php else: ?>
<span class="badge guest">Guest</span>
<?php endif; ?>
</td>

<td><?= e($r['user_name'] ?? $r['full_name']) ?></td>
<td><?= e($r['user_email'] ?? $r['email']) ?></td>
<td><?= e($r['event_title']) ?></td>

<td>
<?php if (!empty($r['cv'])): ?>
<a href="<?= BASE_URL . '/' . $r['cv'] ?>" target="_blank" class="btn-small btn-edit">View</a>
<?php else: ?>
—
<?php endif; ?>
</td>

</tr>
<?php endforeach; ?>
</table>
</div>

<!-- SPONSORS -->
<div class="section">
<h3>Sponsors</h3>
<table class="admin-table">
<tr>
<th>Name</th>
<th>Company</th>
<th>Email</th>
<th>Budget</th>
<th>Event</th>
</tr>

<?php foreach ($sponsors as $s): ?>
<tr>
<td><?= e($s['name']) ?></td>
<td><?= e($s['company']) ?></td>
<td><?= e($s['email']) ?></td>
<td>$<?= e($s['budget']) ?></td>
<td><?= e($s['event_title']) ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>

<!-- NEWSLETTER -->
<div class="section">
<h3>Newsletter</h3>
<table class="admin-table">
<tr><th>Email</th></tr>
<?php foreach ($news as $n): ?>
<tr><td><?= e($n['email']) ?></td></tr>
<?php endforeach; ?>
</table>
</div>

</div>
</div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>