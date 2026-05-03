<?php
require_once __DIR__ . '/../includes/config.php';   // 🔥 IMPORTANT
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Get events
$events = $pdo->query('SELECT id, title FROM events ORDER BY event_date')->fetchAll();

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $company = trim($_POST['company']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $budget = (float)$_POST['budget'];
    $event_id = (int)$_POST['event_id'];

    if (!$name || !$company || !$email || !$budget || !$event_id) {
        set_flash('error', 'Please fill all fields correctly.');
    } else {

        $stmt = $pdo->prepare('
            INSERT INTO sponsors (name, company, email, budget, event_id)
            VALUES (?, ?, ?, ?, ?)
        ');
        $stmt->execute([$name, $company, $email, $budget, $event_id]);

        set_flash('success', 'Sponsorship request sent successfully 🎉');

        // 🔥 FIXED REDIRECT
        redirect(BASE_URL . '/pages/sponsor.php');
    }
}

// Flash
$flash = get_flash();

include __DIR__ . '/../includes/header.php';
?>

<div class="container form-wrap">

    <h2>Become a Sponsor 🤝</h2>

    <!-- FLASH -->
    <?php foreach ($flash as $f): ?>
        <div class="alert <?= e($f['type']) ?>">
            <?= e($f['message']) ?>
        </div>
    <?php endforeach; ?>

    <form method="post">

        <input name="name" required placeholder="Your Name">

        <input name="company" required placeholder="Company Name">

        <input type="email" name="email" required placeholder="Email Address">

        <input type="number" step="0.01" name="budget" required placeholder="Budget ($)">

        <select name="event_id" required>
            <option value="">Select Event</option>
            <?php foreach ($events as $e): ?>
                <option value="<?= (int)$e['id'] ?>">
                    <?= e($e['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button class="btn">Submit Application</button>

    </form>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>