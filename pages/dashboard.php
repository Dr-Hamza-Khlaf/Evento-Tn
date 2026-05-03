<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

// Fetch user registered events
$stmt = $pdo->prepare('
    SELECT e.* 
    FROM registrations r 
    JOIN events e ON r.event_id = e.id 
    WHERE r.user_id = ? 
    ORDER BY e.event_date ASC
');
$stmt->execute([$_SESSION['user']['id']]);
$events = $stmt->fetchAll();

$totalEvents = count($events);
$user = $_SESSION['user'];

include __DIR__ . '/../includes/header.php';
?>

<style>
.dashboard {
    padding: 30px 0;
}

/* HEADER */
.dashboard-header {
    background: #fff;
    padding: 25px;
    border-radius: 14px;
    margin-bottom: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
}

/* PROFILE GRID */
.dashboard-top {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}

/* CARD */
.card {
    background: #fff;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
}

/* STATS */
.dashboard-stats {
    display: flex;
    gap: 15px;
    margin-top: 15px;
}

.stat-box {
    background: #f1f5f9;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
    flex: 1;
}

/* CV SECTION */
.cv-box {
    text-align: center;
}

.cv-box a {
    display: inline-block;
    margin-bottom: 10px;
}

.cv-box input {
    margin: 10px 0;
}

/* EVENTS GRID */
.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

/* CARD */
.event-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    transition: 0.3s;
}

.event-card:hover {
    transform: translateY(-5px);
}

.event-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.event-body {
    padding: 15px;
}

.meta {
    font-size: 14px;
    color: #666;
}

.empty-box {
    background: #fff;
    padding: 30px;
    border-radius: 14px;
    text-align: center;
}
</style>

<div class="container dashboard">

    <!-- 🔥 TOP SECTION -->
    <div class="dashboard-top">

        <!-- PROFILE -->
        <div class="card">
            <h2>Welcome, <?= e($user['name']) ?> 👋</h2>
            <p><?= e($user['email']) ?></p>

            <div class="dashboard-stats">
                <div class="stat-box">
                    <strong><?= $totalEvents ?></strong><br>
                    Events Joined
                </div>
            </div>
        </div>

        <!-- 🔥 CV SECTION -->
        <div class="card cv-box">
            <h3>Your CV</h3>

            <?php if (!empty($user['cv'])): ?>
                <a href="<?= BASE_URL . '/' . $user['cv'] ?>" target="_blank" class="btn small">
                    View CV
                </a>
            <?php else: ?>
                <p>No CV uploaded</p>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/pages/upload_cv.php" enctype="multipart/form-data">
                <input type="file" name="cv" required>
                <button class="btn small">Upload CV</button>
            </form>
        </div>

    </div>

    <!-- EVENTS -->
    <h3 style="margin-top:30px;">Your Registered Events</h3>

    <?php if (empty($events)): ?>

        <div class="empty-box">
            <p>No events yet.</p>
            <a class="btn" href="<?= BASE_URL ?>">Explore events</a>
        </div>

    <?php else: ?>

        <div class="events-grid">

            <?php foreach ($events as $event): ?>

                <div class="event-card">

                    <img 
                        src="<?= e($event['image']) ?: 'https://via.placeholder.com/600x400' ?>" 
                        alt="<?= e($event['title']) ?>"
                    >

                    <div class="event-body">

                        <h4><?= e($event['title']) ?></h4>

                        <div class="meta">
                            📅 <?= e(date('M d, Y - H:i', strtotime($event['event_date']))) ?>
                        </div>

                        <div class="meta">
                            📍 <?= e($event['location']) ?>
                        </div>

                        <a class="btn small"
                           href="<?= BASE_URL ?>/pages/event.php?id=<?= (int)$event['id'] ?>">
                            View Event
                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>