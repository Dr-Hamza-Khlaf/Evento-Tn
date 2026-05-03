<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Fetch data
$events = $pdo->query("SELECT * FROM events ORDER BY event_date ASC")->fetchAll();
$categories = $pdo->query("SELECT DISTINCT category FROM events ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

// Newsletter handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newsletter_email'])) {
    $email = filter_var(trim($_POST['newsletter_email']), FILTER_VALIDATE_EMAIL);

    if ($email) {
        $stmt = $pdo->prepare('INSERT IGNORE INTO newsletter (email) VALUES (?)');
        $stmt->execute([$email]);
        set_flash('success', 'Subscribed successfully.');
    } else {
        set_flash('error', 'Invalid email address.');
    }

    redirect(BASE_URL . '/#newsletter');
}

include __DIR__ . '/includes/header.php';
$flash = get_flash();
?>

<!-- HERO -->
<section class="hero">
    <div class="overlay">
        <div class="container center">
            <h1>Expand your network. <br>Grow your knowledge.</h1>

            <div class="search-wrap" style="position:relative;">
                <input id="searchInput" type="text" placeholder="Search by keyword, category, location" autocomplete="off">
                <div id="suggestions"></div>

                <select id="locationFilter">
                    <option value="">All locations</option>
                </select>
            </div>

            <div class="cta">
                <a class="btn" href="#featured">Explore events</a>
                <a class="btn ghost" href="<?= BASE_URL ?>/pages/sponsor.php">Become sponsor</a>
            </div>
        </div>
    </div>
</section>

<!-- EVENTS -->
<section class="container" id="featured">
    <h2>Featured events</h2>

    <div class="category-pills">
        <button class="pill active" data-category="all">All</button>

        <?php foreach ($categories as $cat): ?>
            <button class="pill" data-category="<?= e($cat) ?>">
                <?= e($cat) ?>
            </button>
        <?php endforeach; ?>
    </div>

    <div class="cards" id="eventsGrid">
        <?php foreach ($events as $event): ?>
            <article class="card"
                data-category="<?= e($event['category']) ?>"
                data-location="<?= e($event['location']) ?>"
                data-keywords="<?= e(strtolower($event['title'].' '.$event['description'].' '.$event['location'].' '.$event['category'])) ?>">

                <img src="<?= e($event['image']) ?>" alt="<?= e($event['title']) ?>">

                <div class="card-body">
                    <span class="tag"><?= e($event['category']) ?></span>
                    <h3><?= e($event['title']) ?></h3>
                    <p><?= e($event['description']) ?></p>

                    <p>
                        <strong><?= e(date('M d, Y', strtotime($event['event_date']))) ?></strong>
                        · <?= e($event['location']) ?>
                    </p>

                    <a class="btn small"
                       href="<?= BASE_URL ?>/pages/event.php?id=<?= (int)$event['id'] ?>">
                        Register
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- 🔥 PREMIUM CTA SECTION -->
<section class="cta-section" id="newsletter">

    <div class="container cta-grid">

        <!-- SPONSOR -->
        <div class="cta-card sponsor">
            <h2>Become a Sponsor</h2>
            <p>
                Join top brands and connect with high-value attendees.
                Boost your visibility and grow your business.
            </p>

            <a class="btn primary" href="<?= BASE_URL ?>/pages/sponsor.php">
                Apply now
            </a>
        </div>

        <!-- NEWSLETTER -->
        <div class="cta-card newsletter">
            <h2>Stay Updated</h2>
            <p>
                Get the latest events, insights, and opportunities directly in your inbox.
            </p>

            <form method="post" class="newsletter-form">
                <input 
                    type="email" 
                    name="newsletter_email" 
                    required 
                    placeholder="Enter your email"
                >

                <button class="btn primary" type="submit">
                    Subscribe
                </button>
            </form>

        </div>

    </div>

</section>

<!-- FLASH -->
<?php foreach ($flash as $f): ?>
    <div class="toast-data"
         data-type="<?= e($f['type']) ?>"
         data-message="<?= e($f['message']) ?>">
    </div>
<?php endforeach; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>