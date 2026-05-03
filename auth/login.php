<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// If already logged in
if (is_logged_in()) {
    redirect(BASE_URL . '/pages/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        set_flash('error', 'Please enter valid email and password.');
    } else {

        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // 🔥 DEBUG (TEMPORARY — REMOVE AFTER TEST)
        if (!$user) {
            set_flash('error', 'User not found in database.');
        } else {
            if (!password_verify($password, $user['password_hash'])) {

                // 🔥 SHOW REAL ISSUE
                set_flash('error', 'Password does not match database.');

            } else {

                // SUCCESS LOGIN
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];

                redirect(BASE_URL . '/pages/dashboard.php');
            }
        }
    }
}

$flash = get_flash();

include __DIR__ . '/../includes/header.php';
?>

<div class="container form-wrap">

    <h2>Login</h2>

    <?php foreach ($flash as $f): ?>
        <div class="alert <?= e($f['type']) ?>">
            <?= e($f['message']) ?>
        </div>
    <?php endforeach; ?>

    <form method="post">
        <input type="email" name="email" required placeholder="Email">
        <input type="password" name="password" required placeholder="Password">
        <button class="btn">Login</button>
    </form>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>