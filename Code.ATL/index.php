<?php
$pageTitle = 'Home - ATL Student Digital Library';
$activePage = 'home';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- FULL WIDTH HERO BANNER SECTION -->
<section class="hero-banner-wrapper">
  <img src="assets/images/Banner.png" alt="ATL Digital Library Main Banner" class="hero-banner-img" />
</section>

<!-- CLEAN & SIMPLE STUDENT CREATORS SHOWCASE BAR -->
<div class="creators-bar-container">
  <div class="creators-title-box">
    <?php echo get_icon('shield-check', 'icon-svg highlight', 28); ?>
    <div>
      <h3>Student Creators Showcase</h3>
      <p style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Designed & built by 7th & 8th grade students at Atal Tinkering Lab (ATL)</p>
    </div>
  </div>

  <div class="creators-row">
    <?php foreach ($STUDENT_CREATORS as $creator): ?>
      <div class="creator-simple-pill">
        <div class="creator-avatar-icon">
          <?php echo get_icon($creator['icon'], '', 20); ?>
        </div>
        <div class="creator-details">
          <span class="creator-name"><?php echo htmlspecialchars($creator['name']); ?></span>
          <span class="creator-role"><?php echo htmlspecialchars($creator['badge']); ?> • <?php echo htmlspecialchars($creator['role']); ?></span>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<main style="max-width: 1350px; margin: 0 auto; padding: 0 20px 40px;">

  <!-- POPULAR COLLECTIONS -->
  <section style="margin-bottom: 50px;">
    <h2 style="font-size: 2rem; margin-bottom: 24px; text-align: center; display: flex; align-items: center; justify-content: center; gap: 10px;">
      <?php echo get_icon('sparkles', '', 24); ?> Popular Library Collections
    </h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px;">

      <!-- AMAR CHITRA KATHA -->
      <a href="library.php?cat=ack" class="ui-card">
        <div style="height: 220px; border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--border-color); margin-bottom: 14px;">
          <img src="assets/images/ack/krishna.png" alt="Amar Chitra Katha" style="width: 100%; height: 100%; object-fit: cover;" />
        </div>
        <span class="chip-badge">25+ Flipbooks</span>
        <h3 style="margin: 8px 0 4px; font-size: 1.4rem;">Amar Chitra Katha</h3>
        <p style="color: var(--text-muted); font-size: 0.9rem; font-weight: 600;">
          Indian mythology, heroic legends, brave warriors, and timeless fables!
        </p>
      </a>

      <!-- HARRY POTTER -->
      <a href="library.php?cat=hp" class="ui-card">
        <div style="height: 220px; border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--border-color); margin-bottom: 14px;">
          <img src="assets/images/hp/hp1.jpg" alt="Harry Potter" style="width: 100%; height: 100%; object-fit: cover;" />
        </div>
        <span class="chip-badge" style="color: var(--accent-violet); background: rgba(124, 58, 237, 0.08);">Wizarding Series</span>
        <h3 style="margin: 8px 0 4px; font-size: 1.4rem;">Harry Potter</h3>
        <p style="color: var(--text-muted); font-size: 0.9rem; font-weight: 600;">
          Step into Hogwarts with Harry, Ron, and Hermione for spellbinding adventures!
        </p>
      </a>

      <!-- PERCY JACKSON -->
      <a href="library.php?cat=pj" class="ui-card">
        <div style="height: 220px; border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--border-color); margin-bottom: 14px;">
          <img src="assets/images/pj/pj1.png" alt="Percy Jackson" style="width: 100%; height: 100%; object-fit: cover;" />
        </div>
        <span class="chip-badge" style="color: var(--accent-pink); background: rgba(219, 39, 119, 0.08);">Demigod Quests</span>
        <h3 style="margin: 8px 0 4px; font-size: 1.4rem;">Percy Jackson</h3>
        <p style="color: var(--text-muted); font-size: 0.9rem; font-weight: 600;">
          Greek gods, mythical monsters, and epic demigod quests filled with action!
        </p>
      </a>

      <!-- WIMPY KID -->
      <a href="library.php?cat=wimpyk" class="ui-card">
        <div style="height: 220px; border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--border-color); margin-bottom: 14px;">
          <img src="assets/images/wimpyk/d1.png" alt="Wimpy Kid" style="width: 100%; height: 100%; object-fit: cover;" />
        </div>
        <span class="chip-badge" style="color: var(--accent-emerald); background: rgba(5, 150, 105, 0.08);">Illustrated Humor</span>
        <h3 style="margin: 8px 0 4px; font-size: 1.4rem;">Wimpy Kid</h3>
        <p style="color: var(--text-muted); font-size: 0.9rem; font-weight: 600;">
          Laugh along with Greg Heffley's hilarious journal of school life!
        </p>
      </a>

    </div>
  </section>

  <!-- TOP RATED FLIPBOOKS CATALOG -->
  <section style="margin-bottom: 50px;">
    <h2 style="font-size: 2rem; margin-bottom: 24px; text-align: center; display: flex; align-items: center; justify-content: center; gap: 10px;">
      <?php echo get_icon('book-open', '', 24); ?> Top Rated Flipbooks
    </h2>

    <div class="book-grid" id="homeBookGrid">
      <!-- Dynamically Rendered by Database Engine -->
    </div>
  </section>

</main>

<script>
  document.addEventListener("DOMContentLoaded", async function () {
    const books = await ATLDatabase.getAllBooks();
    const grid = document.getElementById("homeBookGrid");
    grid.innerHTML = "";

    const featuredList = books.slice(0, 8);
    featuredList.forEach(book => {
      const card = document.createElement("a");
      card.className = "book-card";
      card.href = book.pdf ? `reader.php?book=${book.id}` : `library.php?q=${encodeURIComponent(book.title)}`;

      card.innerHTML = `
        <div class="book-cover-wrap">
          <img src="${book.cover}" alt="${book.title}" />
        </div>
        <div class="book-info">
          <span class="chip-badge">${book.badge || book.genre}</span>
          <h3 style="font-size: 1.2rem; color: var(--text-heading);">${book.title}</h3>
          <p style="font-size: 0.9rem; color: var(--text-muted); font-weight: 600;">By ${book.author}</p>
          <div style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; padding-top: 10px; border-top: 1px dashed var(--border-color);">
            <span style="font-weight: 800; color: var(--accent-sky);">⭐ ${book.rating || '5.0'}</span>
            <span class="btn-primary" style="padding: 4px 12px; font-size: 0.85rem;">
              ${book.pdf ? 'Read Flipbook' : 'View Details'}
            </span>
          </div>
        </div>
      `;
      grid.appendChild(card);
    });
  });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
