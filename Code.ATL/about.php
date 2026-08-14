<?php
$pageTitle = 'About Us - ATL Digital Library';
$activePage = 'about';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main style="max-width: 1050px; margin: 40px auto; padding: 0 20px; text-align: center;">

  <div style="margin-bottom: 40px;">
    <h1 style="font-size: 2.8rem; margin-bottom: 12px; display: flex; align-items: center; justify-content: center; gap: 12px;">
      <?php echo get_icon('info', 'icon-svg highlight', 36); ?> About ATL Digital Library
    </h1>
    <p style="font-size: 1.2rem; font-weight: 600; color: var(--text-muted); max-width: 820px; margin: 0 auto; line-height: 1.6;">
      Welcome to the ATL Digital Library! Developed by talented 7th & 8th grade students at Atal Tinkering Lab (ATL). Our mission is to make reading engaging, educational, and accessible for students through 3D digital flipbooks, comic collections, and softcopy book donations!
    </p>
  </div>

  <!-- CREATORS SHOWCASE WALL OF FAME -->
  <section class="ui-card" style="padding: 40px; margin-bottom: 40px;">
    <h2 style="font-size: 2.2rem; margin-bottom: 24px; display: flex; align-items: center; justify-content: center; gap: 10px;">
      <?php echo get_icon('award', 'icon-svg highlight', 32); ?> Meet the Student Creators
    </h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 28px;">
      <?php foreach ($STUDENT_CREATORS as $creator): ?>
        <div style="background: rgba(2, 132, 199, 0.04); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 30px 20px; text-align: center;">
          <div style="width: 80px; height: 80px; background: var(--accent-sky); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; box-shadow: 0 4px 16px rgba(2, 132, 199, 0.3);">
            <?php echo get_icon($creator['icon'], '', 38); ?>
          </div>
          <h3 style="font-size: 1.5rem; margin-bottom: 6px; color: var(--text-heading);"><?php echo htmlspecialchars($creator['name']); ?></h3>
          <span class="chip-badge" style="margin-bottom: 12px; font-size: 0.9rem;"><?php echo htmlspecialchars($creator['grade']); ?></span>
          <p style="color: var(--text-muted); font-weight: 600; font-size: 0.95rem; margin-top: 8px;">
            <?php echo htmlspecialchars($creator['role']); ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- SCHOOL EMBLEMS & ACKNOWLEDGEMENT -->
  <section class="ui-card" style="padding: 30px; text-align: left;">
    <h3 style="font-size: 1.4rem; margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
      <?php echo get_icon('school', 'icon-svg highlight', 24); ?> Institution & School Support
    </h3>
    <p style="color: var(--text-muted); font-weight: 600; line-height: 1.7;">
      This project is proudly mentored and supported by <strong>BKHM School</strong> and <strong>Rajhans Vidyalaya</strong> under the Atal Tinkering Lab (ATL) initiative to encourage innovation, software engineering skills, and a love for reading among young students.
    </p>
  </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
