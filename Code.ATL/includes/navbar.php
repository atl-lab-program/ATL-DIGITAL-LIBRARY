<?php
/**
 * REUSABLE PHP NAVBAR INCLUDE (TIER 2 DISTINCT NAVIGATION BAR)
 * Multi-color Navigation Pills, Popup Search Button, and Theme Switcher
 */
$activePage = $activePage ?? 'home';
?>
<!-- TIER 2: DISTINCT NAVIGATION BAR (Positioned below Header) -->
<nav class="site-navbar-tier">
  <div class="navbar-tier-container">
    
    <!-- MULTI-COLOR NAVIGATION PILLS -->
    <div class="nav-pills-group">
      <a href="<?php echo BASE_URL; ?>index.php" class="nav-pill sky <?php echo $activePage === 'home' ? 'active' : ''; ?>">
        <?php echo get_icon('home', '', 18); ?> Home
      </a>
      <a href="<?php echo BASE_URL; ?>library.php" class="nav-pill violet <?php echo $activePage === 'library' ? 'active' : ''; ?>">
        <?php echo get_icon('book', '', 18); ?> eBook Catalog
      </a>
      <a href="<?php echo BASE_URL; ?>donation.php" class="nav-pill emerald <?php echo $activePage === 'donation' ? 'active' : ''; ?>">
        <?php echo get_icon('upload', '', 18); ?> Upload Station
      </a>
      <a href="<?php echo BASE_URL; ?>about.php" class="nav-pill pink <?php echo $activePage === 'about' ? 'active' : ''; ?>">
        <?php echo get_icon('info', '', 18); ?> About Us
      </a>
      <a href="<?php echo BASE_URL; ?>account.php" class="nav-pill amber <?php echo $activePage === 'account' ? 'active' : ''; ?>">
        <?php echo get_icon('user', '', 18); ?> Student Profile
      </a>
    </div>

    <!-- RIGHT ACTIONS: POPUP SEARCH & THEME TOGGLE -->
    <div class="nav-actions-right">
      <!-- POPUP SEARCH BUTTON -->
      <button class="popup-search-trigger" id="openSearchModalBtn" title="Search Books (Popup)">
        <?php echo get_icon('search', '', 18); ?> <span>Search Books</span>
      </button>

      <!-- LIGHT/DARK THEME TOGGLE -->
      <button class="theme-toggle-btn" id="themeToggleBtn" title="Toggle Light/Dark Theme">
        <span id="themeIconContainer">
          <?php echo get_icon('moon', '', 18); ?> <span class="theme-text">Dark</span>
        </span>
      </button>
    </div>

  </div>
</nav>
