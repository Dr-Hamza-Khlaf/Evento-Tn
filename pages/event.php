<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$id = (int)($_GET['id'] ?? 0);

// Fetch event
$stmt = $pdo->prepare('SELECT * FROM events WHERE id = ?');
$stmt->execute([$id]);
$event = $stmt->fetch();

if (!$event) {
    http_response_code(404);
    exit('Event not found');
}

// Image
$imagePath = !empty($event['image'])
    ? BASE_URL . '/' . $event['image']
    : BASE_URL . '/assets/img/default.jpg';

// Count registrations
$countStmt = $pdo->prepare('SELECT COUNT(*) FROM registrations WHERE event_id = ?');
$countStmt->execute([$id]);
$currentCount = (int)$countStmt->fetchColumn();
$isFull = $currentCount >= (int)$event['capacity'];

// Logged user
$alreadyRegistered = false;
$userCV = null;

if (is_logged_in()) {
    $userId = $_SESSION['user']['id'];

    $check = $pdo->prepare('SELECT id FROM registrations WHERE user_id = ? AND event_id = ?');
    $check->execute([$userId, $id]);
    $alreadyRegistered = (bool)$check->fetch();

    $userCV = $_SESSION['user']['cv'] ?? null;
}

// REGISTER USER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_user'])) {

    require_login();

    if ($alreadyRegistered) {
        set_flash('error', 'You are already registered.');
    } elseif ($isFull) {
        set_flash('error', 'Event is full.');
    } else {

        $cv = $_SESSION['user']['cv'] ?? null;

        $ins = $pdo->prepare('
            INSERT INTO registrations (user_id, event_id, cv) 
            VALUES (?, ?, ?)
        ');

        $ins->execute([$_SESSION['user']['id'], $id, $cv]);

        set_flash('success', 'Registered successfully 🎉');
    }

    redirect(BASE_URL . '/pages/event.php?id=' . $id);
}

$flash = get_flash();

include __DIR__ . '/../includes/header.php';
?>

<div class="container event-page">

    <div class="event-hero">
        <img src="<?= $imagePath ?>" alt="<?= e($event['title']) ?>">
    </div>

    <div class="event-content">

        <h1><?= e($event['title']) ?></h1>

        <p class="event-meta">
            📍 <?= e($event['location']) ?> |
            📅 <?= e(date('F d, Y - H:i', strtotime($event['event_date']))) ?>
        </p>

        <p>
            👥 <?= $currentCount ?> / <?= (int)$event['capacity'] ?> participants
        </p>

        <p class="event-description">
            <?= e($event['description']) ?>
        </p>

        <!-- REGISTER -->
        <?php if ($isFull): ?>

            <button class="btn" disabled style="background:#999;">Event Full</button>

        <?php else: ?>

            <?php if (is_logged_in()): ?>

                <form method="post">
                    <input type="hidden" name="register_user" value="1">

                    <?php if ($alreadyRegistered): ?>
                        <button class="btn" disabled style="background:#16a34a;">
                            Already Registered
                        </button>
                    <?php else: ?>

                        <?php if (!$userCV): ?>
                            <p style="color:#dc2626; font-size:14px; margin-bottom:10px;">
                                ⚠️ Upload your CV in dashboard for better visibility.
                            </p>
                        <?php endif; ?>

                        <button class="btn">Register to event</button>

                    <?php endif; ?>
                </form>

            <?php else: ?>

                <button class="btn" onclick="openGuestModal()">
                    Register as Guest
                </button>

            <?php endif; ?>

        <?php endif; ?>

        <a href="<?= BASE_URL ?>" class="btn ghost" style="margin-top:10px;">
            ← Back to events
        </a>

    </div>
</div>

<!-- 🔥 GUEST MODAL -->
<div id="guestModal" class="modal">
  <div class="modal-content premium">

    <div class="modal-header">
      <h3>Register for this Event</h3>
      <span class="close" onclick="closeGuestModal()">×</span>
    </div>

    <form method="POST" 
          action="<?= BASE_URL ?>/pages/register_guest.php" 
          enctype="multipart/form-data" 
          class="premium-form">

      <input type="hidden" name="event_id" value="<?= $event['id'] ?>">

      <h4>Personal Information</h4>

      <div class="grid-2">
        <input type="text" name="full_name" placeholder="Full name" required>
        <input type="email" name="email" placeholder="Email address" required>
      </div>

      <div class="grid-2">
        <input type="text" name="phone" placeholder="Phone number" required>
        <input type="text" name="address" placeholder="Address">
      </div>

      <select name="profile_type" required>
        <option value="">Select profile</option>
        <option value="student">Student</option>
        <option value="freelancer">Freelancer</option>
        <option value="entrepreneur">Entrepreneur</option>
      </select>

      <h4>CV / Resume</h4>

      <!-- 🔥 REQUIRED FILE -->
      <div class="upload-box" onclick="document.getElementById('cvInput').click()">
        <p id="uploadText">Click to upload your CV</p>
        <small>PDF, DOC, DOCX — max 5MB</small>
        <input type="file" name="cv" id="cvInput" required hidden>
      </div>

      <button class="btn full">✔ Complete Registration</button>

    </form>

  </div>
</div>

<!-- FLASH -->
<?php foreach ($flash as $f): ?>
    <div class="toast-data"
         data-type="<?= e($f['type']) ?>"
         data-message="<?= e($f['message']) ?>">
    </div>
<?php endforeach; ?>

<script>
// 🔥 Show selected file name
const cvInput = document.getElementById("cvInput");

if (cvInput) {
  cvInput.addEventListener("change", () => {
    const file = cvInput.files[0];
    const text = document.getElementById("uploadText");

    if (file) {
      text.innerHTML = "✔ " + file.name;
      text.style.color = "green";
    }
  });
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>