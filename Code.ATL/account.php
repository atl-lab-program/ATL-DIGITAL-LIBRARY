<?php
$pageTitle = 'Student Account - ATL Digital Library';
$activePage = 'account';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<style>
  .account-portal-container {
    max-width: 1250px;
    margin: 35px auto;
    padding: 0 20px;
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 28px;
    box-sizing: border-box;
    overflow-x: hidden;
  }

  .profile-sidebar-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-card);
    padding: 28px 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
    max-width: 100%;
    box-sizing: border-box;
  }

  .profile-avatar-circle {
    width: 90px;
    height: 90px;
    background: rgba(2, 132, 199, 0.12);
    border: 2px solid var(--accent-sky);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent-sky);
    box-shadow: 0 4px 15px rgba(2, 132, 199, 0.25);
  }

  .account-main-panel {
    display: flex;
    flex-direction: column;
    gap: 28px;
    max-width: 100%;
    min-width: 0; /* Prevents flex/grid overflow beyond screen */
    box-sizing: border-box;
  }

  @media (max-width: 900px) {
    .account-portal-container {
      grid-template-columns: 1fr;
    }
  }
</style>

<main class="account-portal-container">

  <!-- LEFT SIDEBAR: STUDENT PROFILE CARD -->
  <aside class="profile-sidebar-card">
    <div class="profile-avatar-circle">
      <?php echo get_icon('user', '', 44); ?>
    </div>
    <h2 id="userNameDisplay" style="font-size: 1.7rem; color: var(--text-heading);">Suhaira</h2>
    <span class="chip-badge" id="userRoleDisplay">Student Creator (7th Grade)</span>

    <div style="width: 100%; border-top: 1px dashed var(--border-color); margin: 6px 0;"></div>

    <h4 style="font-size: 0.95rem; color: var(--accent-sky);">Student Account Status:</h4>
    <p style="font-size: 0.88rem; color: var(--text-muted); font-weight: 600;">
      Your reading bookmarks and progress are saved safely in local storage.
    </p>
  </aside>

  <!-- RIGHT MAIN CONTENT PANEL -->
  <section class="account-main-panel">

    <!-- READING ACHIEVEMENTS & BADGES -->
    <div class="ui-card">
      <h3 style="font-size: 1.4rem; margin-bottom: 14px; display: flex; align-items: center; gap: 10px;">
        <?php echo get_icon('award', 'icon-svg highlight', 24); ?> Student Reading Achievements & Badges
      </h3>
      
      <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <span class="chip-badge" style="padding: 8px 18px; font-size: 0.95rem; background: rgba(219, 39, 119, 0.1); color: var(--accent-pink); border-color: var(--accent-pink);">
          <?php echo get_icon('star', '', 16); ?> Creator Hero
        </span>
        <span class="chip-badge" style="padding: 8px 18px; font-size: 0.95rem; background: rgba(2, 132, 199, 0.1); color: var(--accent-sky); border-color: var(--accent-sky);">
          <?php echo get_icon('book-open', '', 16); ?> Bookworm Master
        </span>
        <span class="chip-badge" style="padding: 8px 18px; font-size: 0.95rem; background: rgba(124, 58, 237, 0.1); color: var(--accent-violet); border-color: var(--accent-violet);">
          <?php echo get_icon('shield-check', '', 16); ?> Mythology Explorer
        </span>
        <span class="chip-badge" style="padding: 8px 18px; font-size: 0.95rem; background: rgba(5, 150, 105, 0.1); color: var(--accent-emerald); border-color: var(--accent-emerald);">
          <?php echo get_icon('zap', '', 16); ?> 5-Day Reading Streak
        </span>
      </div>
    </div>

    <!-- BOOKMARKED FAVORITES GRID -->
    <div class="ui-card">
      <h3 style="font-size: 1.4rem; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
        <?php echo get_icon('heart', 'icon-svg highlight', 24); ?> My Bookmarked Books & Flipbooks
      </h3>

      <div class="book-grid" id="favoritesGrid" style="padding: 0; margin: 0; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));">
        <!-- Dynamically rendered by db.js -->
      </div>
    </div>

  </section>

</main>

<script>
  document.addEventListener("DOMContentLoaded", async function () {
    const user = ATLDatabase.getCurrentUser();
    
    const nameDisplay = document.getElementById("userNameDisplay");
    const roleDisplay = document.getElementById("userRoleDisplay");

    if (nameDisplay) nameDisplay.textContent = user.fullName || "Student Reader";
    if (roleDisplay) roleDisplay.textContent = `${user.role || 'Student Reader'} (${user.grade || '7th/8th Grade'})`;

    const favoritesGrid = document.getElementById("favoritesGrid");
    const allBooks = await ATLDatabase.getAllBooks();
    const favIds = ATLDatabase.getFavorites();

    const favBooks = allBooks.filter(b => favIds.includes(b.id));

    if (favBooks.length === 0) {
      favoritesGrid.innerHTML = `
        <div style="grid-column: 1 / -1; padding: 30px; text-align: center;">
          <p style="font-weight: 600; color: var(--text-muted);">You haven't bookmarked any books yet!</p>
          <a href="library.php" class="btn-primary" style="margin-top: 12px; padding: 6px 20px;">
            Explore Library Catalog
          </a>
        </div>
      `;
      return;
    }

    favBooks.forEach(book => {
      const card = document.createElement("a");
      card.className = "book-card";
      card.href = `reader.php?book=${encodeURIComponent(book.id)}`;

      card.innerHTML = `
        <div class="book-cover-wrap" style="height: 220px;">
          <img src="${book.cover}" alt="${book.title}" />
        </div>
        <div class="book-info">
          <span class="chip-badge">${book.badge || book.genre}</span>
          <h4 style="font-size: 1.1rem; color: var(--text-heading); margin-top: 4px;">${book.title}</h4>
          <div style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; padding-top: 10px; border-top: 1px dashed var(--border-color);">
            <span style="font-weight: 800; color: var(--accent-sky);">⭐ ${book.rating || '5.0'}</span>
            <span class="btn-primary" style="padding: 4px 10px; font-size: 0.8rem;">
              Read Flipbook
            </span>
          </div>
        </div>
      `;
      favoritesGrid.appendChild(card);
    });
  });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
