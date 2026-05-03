<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!$name || !$email || strlen($password) < 6) {
        set_flash('error', 'Invalid data.');
    } else {

        $stmt = $pdo->prepare("
            INSERT INTO users (name,email,password_hash,role)
            VALUES (?,?,?,?)
        ");

        $stmt->execute([
            $name,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $role
        ]);

        set_flash('success', 'User created.');
        redirect(BASE_URL . '/admin/index.php');
    }
}

$flash = get_flash();

include __DIR__ . '/../includes/header.php';
?>

<div class="container form-wrap">

    <h2>Create User 👤</h2>

    <?php foreach ($flash as $f): ?>
        <div class="alert <?= e($f['type']) ?>">
            <?= e($f['message']) ?>
        </div>
    <?php endforeach; ?>

    <form method="post">

        <input name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>

        <button class="btn">Create</button>

    </form>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>