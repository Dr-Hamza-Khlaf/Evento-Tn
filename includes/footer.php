</main>

<footer class="footer-advanced">

  <!-- TOP BAR -->
  <div class="footer-top container">
    <div class="footer-brand">
      <h4>EventoTN</h4>
      <p>Premium events for innovators.</p>
    </div>

    <div class="footer-social">
      <span>Follow EventoTN</span>
      <div class="icons">
        <a href="#">🌐</a>
        <a href="#">𝕏</a>
        <a href="#">in</a>
      </div>
    </div>
  </div>

  <!-- MAIN GRID -->
  <div class="footer-main container">

    <div class="col">
      <h5>What's new</h5>
      <a href="#">Upcoming events</a>
      <a href="#">Tech summits</a>
      <a href="#">Workshops</a>
      <a href="#">AI events</a>
      <a href="#">Startup meetups</a>
    </div>

    <div class="col">
      <h5>EventoTN</h5>
      <a href="#">Account</a>
      <a href="#">My dashboard</a>
      <a href="#">Support</a>
      <a href="#">Help center</a>
    </div>

    <div class="col">
      <h5>Education</h5>
      <a href="#">Learning events</a>
      <a href="#">Workshops</a>
      <a href="#">Courses</a>
      <a href="#">Certifications</a>
    </div>

    <div class="col">
      <h5>Business</h5>
      <a href="#">Sponsors</a>
      <a href="#">Partnerships</a>
      <a href="#">Enterprise events</a>
      <a href="#">Solutions</a>
    </div>

    <div class="col">
      <h5>Developer & IT</h5>
      <a href="#">Hackathons</a>
      <a href="#">Developer hub</a>
      <a href="#">API</a>
      <a href="#">Community</a>
    </div>

    <div class="col">
      <h5>Company</h5>
      <a href="#">About</a>
      <a href="#">Careers</a>
      <a href="#">Contact</a>
      <a href="#">Privacy Policy</a>
    </div>

  </div>

  <!-- BOTTOM -->
  <div class="footer-bottom container">
    <div>
      <span>🌍 English</span>
    </div>

    <div class="links">
      <a href="#">Privacy</a>
      <a href="#">Terms</a>
      <a href="#">Sitemap</a>
      <a href="#">Contact</a>
    </div>

    <div>
      © EventoTN <?= date('Y') ?>
    </div>
  </div>

</footer>

<!-- 🔔 TOAST CONTAINER -->
<div id="toast-container"></div>

<!-- JS -->
<script>
  window.BASE_URL = "<?= BASE_URL ?>";
</script>

<script defer src="<?= BASE_URL ?>/assets/js/app.js?v=2.0"></script>

</body>
</html>