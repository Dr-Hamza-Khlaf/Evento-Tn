<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// If already admin → go dashboard
if (is_admin()) {
    redirect(BASE_URL . '/admin/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if ($email && $password) {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? AND role='admin' LIMIT 1");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {

            $_SESSION['user'] = [
                'id' => $admin['id'],
                'name' => $admin['name'],
                'email' => $admin['email'],
                'role' => $admin['role']
            ];

            redirect(BASE_URL . '/admin/index.php');
        }

        set_flash('error', 'Invalid admin credentials.');
    } else {
        set_flash('error', 'Fill all fields.');
    }
}

$flash = get_flash();
include __DIR__ . '/../includes/header.php';
?>

<div class="container form-wrap">
    <h2>Admin Login</h2>

    <?php foreach ($flash as $f): ?>
        <div class="alert <?= e($f['type']) ?>">
            <?= e($f['message']) ?>
        </div>
    <?php endforeach; ?>

    <form method="post">
        <input type="email" name="email" required placeholder="Admin Email">
        <input type="password" name="password" required placeholder="Password">
        <button class="btn">Login as Admin</button>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>