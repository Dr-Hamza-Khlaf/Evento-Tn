<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

// ==========================
// 🔍 GET EVENT
// ==========================
$id = (int)($_GET['id'] ?? 0);

$event = [
    'title' => '',
    'description' => '',
    'event_date' => '',
    'location' => '',
    'category' => 'AI',
    'image' => '',
    'capacity' => 100
];

if ($id > 0) {
    $st = $pdo->prepare('SELECT * FROM events WHERE id = ?');
    $st->execute([$id]);
    $event = $st->fetch() ?: $event;
}

// ==========================
// 💾 HANDLE FORM
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = str_replace('T', ' ', $_POST['event_date']);
    $location = trim($_POST['location']);
    $category = trim($_POST['category']);
    $capacity = (int)$_POST['capacity'];

    $imagePath = $event['image']; // keep old image if editing

    // ==========================
    // 📸 HANDLE IMAGE UPLOAD
    // ==========================
    if (!empty($_FILES['image']['name'])) {

        $file = $_FILES['image'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($file['type'], $allowedTypes)) {
            set_flash('error', 'Only JPG, PNG, WEBP allowed.');
        } else {

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'event_' . time() . '.' . $ext;

            $uploadDir = __DIR__ . '/../uploads/';
            $uploadPath = $uploadDir . $filename;

            // Create folder if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            move_uploaded_file($file['tmp_name'], $uploadPath);

            // Save relative path
            $imagePath = 'uploads/' . $filename;
        }
    }

    // ==========================
    // 🧪 VALIDATION
    // ==========================
    if (!$title || !$description || !$event_date) {
        set_flash('error', 'Please fill all required fields.');
    } else {

        try {

            if ($id > 0) {
                // UPDATE
                $st = $pdo->prepare("
                    UPDATE events 
                    SET title=?, description=?, event_date=?, location=?, category=?, image=?, capacity=? 
                    WHERE id=?
                ");
                $st->execute([
                    $title,
                    $description,
                    $event_date,
                    $location,
                    $category,
                    $imagePath,
                    $capacity,
                    $id
                ]);

                set_flash('success', 'Event updated successfully.');

            } else {
                // INSERT
                $st = $pdo->prepare("
                    INSERT INTO events (title,description,event_date,location,category,image,capacity)
                    VALUES (?,?,?,?,?,?,?)
                ");
                $st->execute([
                    $title,
                    $description,
                    $event_date,
                    $location,
                    $category,
                    $imagePath,
                    $capacity
                ]);

                set_flash('success', 'Event created successfully.');
            }

        } catch (PDOException $e) {
            set_flash('error', 'Database error.');
        }

        redirect(BASE_URL . '/admin/index.php');
    }
}

// Flash
$flash = get_flash();

include __DIR__ . '/../includes/header.php';
?>

<div class="container form-wrap">

    <h2><?= $id ? 'Edit Event ✏️' : 'Create Event ➕' ?></h2>

    <?php foreach ($flash as $f): ?>
        <div class="alert <?= e($f['type']) ?>">
            <?= e($f['message']) ?>
        </div>
    <?php endforeach; ?>

    <form method="post" enctype="multipart/form-data">

        <input name="title" value="<?= e($event['title']) ?>" required placeholder="Title">

        <textarea name="description" required placeholder="Description"><?= e($event['description']) ?></textarea>

        <input type="datetime-local"
               name="event_date"
               value="<?= e($event['event_date'] ? str_replace(' ', 'T', $event['event_date']) : '') ?>"
               required>

        <input name="location" value="<?= e($event['location']) ?>" required placeholder="Location">

        <input name="category" value="<?= e($event['category']) ?>" required placeholder="Category">

        <!-- 📸 IMAGE UPLOAD -->
        <input type="file" name="image" accept="image/*">

        <?php if (!empty($event['image'])): ?>
            <p>Current Image:</p>
            <img src="<?= BASE_URL . '/' . e($event['image']) ?>" width="120">
        <?php endif; ?>

        <input type="number" name="capacity" value="<?= (int)$event['capacity'] ?>" min="1" required>

        <button class="btn">Save</button>

    </form>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>