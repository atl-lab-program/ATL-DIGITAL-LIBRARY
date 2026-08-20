<?php
/**
 * REUSABLE PHP FOOTER INCLUDE
 * Shared site footer, Popup Search Modal include, and script dependencies.
 */
require_once __DIR__ . '/search_modal.php';
?>
<footer class="site-footer">
  <div class="footer-content">
    <div class="chip-badge" style="padding: 10px 24px; font-size: 1rem; background: var(--accent-sky); color: white; border: none;">
      <?php echo get_icon('award', 'icon-svg', 20); ?>
      Built by Student Creators Suhaira, Siddharth & Aadi (Grade 7 )
    </div>
    <p style="font-weight: 600; color: var(--text-muted); font-size: 0.95rem; margin-top: 10px;">
      ATL Student Digital Library • Powered by Modular PHP & Local Storage Engine
    </p>
  </div>
</footer>

<!-- JS Dependencies -->
<script src="<?php echo BASE_URL; ?>assets/js/db.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/cursor.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
</body>
</html>
