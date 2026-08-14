<?php
/**
 * REUSABLE PHP HEADER INCLUDE (TIER 1 TOP HEADER)
 * Includes HTML head, CSS imports, and Tier-1 Top Header Section.
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/icons.php';
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' . SITE_NAME : SITE_NAME; ?></title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
  
  <!-- PageFlip & PDF.js Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/page-flip/dist/js/page-flip.browser.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
</head>
<body>

<!-- TIER 1: DISTINCT TOP HEADER BAR (Logos, Title Banner, Creators Badge) -->
<header class="site-header-tier">
  <div class="header-tier-container">
    
    <!-- SCHOOL EMBLEMS GROUP -->
    <div class="school-emblems-group">
      <a href="https://bkhm.edu.in/" target="_blank" class="school-badge-item">
        <img src="<?php echo BASE_URL; ?>assets/images/Bkhamct.png" alt="BKHM" />
        <span>BKHM School</span>
      </a>
      <span style="opacity: 0.3;">|</span>
      <a href="https://rajhans.bkhm.edu.in/" target="_blank" class="school-badge-item">
        <img src="<?php echo BASE_URL; ?>assets/images/Rajhans_Logo.jpg" alt="Rajhans" />
        <span>Rajhans</span>
      </a>
    </div>

    <!-- MAIN BRAND TITLE BANNER -->
    <a href="<?php echo BASE_URL; ?>index.php" class="brand-main-title">
      <?php echo get_icon('zap', 'icon-svg highlight', 28); ?>
      <span>ATL <span class="highlight">DIGITAL LIBRARY</span></span>
      <?php echo get_icon('book-open', 'icon-svg', 24); ?>
    </a>

    <!-- CREATORS SHOWCASE MINI BADGE -->
    <a href="<?php echo BASE_URL; ?>about.php" class="creators-mini-chip">
      <?php echo get_icon('award', 'icon-svg', 18); ?>
      <span>Student Creators (Grades 7 & 8)</span>
    </a>

  </div>
</header>
