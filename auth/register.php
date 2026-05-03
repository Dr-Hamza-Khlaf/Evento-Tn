<?php
require_once __DIR__ . '/../includes/config.php';   // 🔥 BASE_URL + session
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// If already logged in → redirect
if (is_logged_in()) {
    redirect(BASE_URL . '/pages/dashboard.php');
}

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$name || !$email || strlen($password) < 6) {
        set_flash('error', 'Please fill valid data (min 6 characters password).');
    } else {

        $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, "user")');

        try {
            $stmt->execute([
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT)
            ]);

            set_flash('success', 'Account created successfully. Please login.');

            // 🔥 FIXED REDIRECT
            redirect(BASE_URL . '/auth/login.php');

        } catch (PDOException $e) {
            set_flash('error', 'Email already exists.');
        }
    }
}

// Flash messages
$flash = get_flash();

include __DIR__ . '/../includes/header.php';
?>

<div class="container form-wrap">

    <h2>Create account</h2>

    <!-- FLASH MESSAGES -->
    <?php foreach ($flash as $f): ?>
        <div class="alert <?= e($f['type']) ?>">
            <?= e($f['message']) ?>
        </div>
    <?php endforeach; ?>

    <form method="post">

        <input name="name" required placeholder="Full name">

        <input type="email" name="email" required placeholder="Email">

        <input type="password" name="password" required minlength="6" placeholder="Password">

        <button class="btn">Register</button>

    </form>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>