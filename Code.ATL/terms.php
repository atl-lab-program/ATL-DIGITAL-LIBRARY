<?php
$pageTitle = 'Library Terms - ATL Digital Library';
$activePage = 'terms';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
  <div class="ui-card" style="padding: 40px 30px;">
    <div style="text-align: center; margin-bottom: 24px;">
      <h1 style="font-size: 2.3rem; display: flex; align-items: center; justify-content: center; gap: 10px;">
        <?php echo get_icon('file-text', 'icon-svg highlight', 32); ?> Library Guidelines & Terms
      </h1>
    </div>

    <h3 style="font-size: 1.3rem; color: var(--accent-sky); margin: 20px 0 8px;">1. Educational Purpose</h3>
    <p style="color: var(--text-muted); font-weight: 600; line-height: 1.7;">
      The ATL Digital Library is an educational student project developed by 7th & 8th grade students at Atal Tinkering Lab (ATL) for reading, learning, and sharing digital books among school students.
    </p>

    <h3 style="font-size: 1.3rem; color: var(--accent-sky); margin: 20px 0 8px;">2. Respectful Reading & Fair Use</h3>
    <p style="color: var(--text-muted); font-weight: 600; line-height: 1.7;">
      All digital flipbooks, Amar Chitra Katha comics, and reading materials are intended solely for personal study, enjoyment, and student learning.
    </p>

    <h3 style="font-size: 1.3rem; color: var(--accent-sky); margin: 20px 0 8px;">3. Privacy & Accounts</h3>
    <p style="color: var(--text-muted); font-weight: 600; line-height: 1.7;">
      Student accounts and bookmarked favorites are saved locally on your browser. No personal data or private passwords are sold or shared with outside third parties.
    </p>

    <h3 style="font-size: 1.3rem; color: var(--accent-sky); margin: 20px 0 8px;">4. Softcopy Book Uploads & Donations</h3>
    <p style="color: var(--text-muted); font-weight: 600; line-height: 1.7;">
      Submitted book softcopies and digital image uploads are stored locally in the library database for student sharing. Please ensure uploaded files respect school guidelines.
    </p>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
