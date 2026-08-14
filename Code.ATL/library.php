<?php
$pageTitle = 'eBook Catalog - ATL Digital Library';
$activePage = 'library';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main style="max-width: 1350px; margin: 40px auto; padding: 0 20px;">
  
  <div style="text-align: center; margin-bottom: 28px;">
    <h1 style="font-size: 2.5rem; margin-bottom: 8px; display: flex; align-items: center; justify-content: center; gap: 12px;">
      <?php echo get_icon('book-open', 'icon-svg highlight', 36); ?> Interactive Digital Library Catalog
    </h1>
    <p style="font-size: 1.1rem; color: var(--text-muted); font-weight: 600;">
      Browse 25+ Amar Chitra Katha comic flipbooks, Percy Jackson, Harry Potter, Wimpy Kid, and student softcopies!
    </p>
  </div>

  <!-- SEARCH INPUT BAR -->
  <section>
    <div class="search-box-ui">
      <input type="text" id="searchInput" placeholder="Search by book title, author, mythology, heroes..." autocomplete="off">
      <button id="searchBtn" title="Search Books">
        <?php echo get_icon('search', '', 20); ?>
      </button>
    </div>
  </section>

  <!-- CATEGORY FILTER TABS -->
  <div class="filter-tabs" id="filterTabs">
    <button class="filter-tab active" data-cat="all">
      <?php echo get_icon('sparkles', '', 18); ?> All Books
    </button>
    <button class="filter-tab" data-cat="ack">
      <?php echo get_icon('book', '', 18); ?> Mythology & ACK
    </button>
    <button class="filter-tab" data-cat="hp">
      <?php echo get_icon('zap', '', 18); ?> Harry Potter
    </button>
    <button class="filter-tab" data-cat="pj">
      <?php echo get_icon('shield-check', '', 18); ?> Percy Jackson
    </button>
    <button class="filter-tab" data-cat="wimpyk">
      <?php echo get_icon('heart', '', 18); ?> Wimpy Kid
    </button>
    <button class="filter-tab" data-cat="donated">
      <?php echo get_icon('upload', '', 18); ?> Student Donated
    </button>
  </div>

  <!-- BOOK CATALOG GRID -->
  <div class="book-grid" id="catalogGrid">
    <!-- Dynamically rendered by db.js -->
  </div>

</main>

<script>
  document.addEventListener("DOMContentLoaded", async function () {
    const searchInput = document.getElementById("searchInput");
    const searchBtn = document.getElementById("searchBtn");
    const catalogGrid = document.getElementById("catalogGrid");
    const filterTabs = document.querySelectorAll(".filter-tab");

    const urlParams = new URLSearchParams(window.location.search);
    let currentCategory = urlParams.get("cat") || "all";
    let currentQuery = urlParams.get("q") || "";

    if (currentQuery) searchInput.value = currentQuery;

    filterTabs.forEach(tab => {
      if (tab.getAttribute("data-cat") === currentCategory) {
        filterTabs.forEach(t => t.classList.remove("active"));
        tab.classList.add("active");
      }

      tab.addEventListener("click", () => {
        filterTabs.forEach(t => t.classList.remove("active"));
        tab.classList.add("active");
        currentCategory = tab.getAttribute("data-cat");
        renderBooks();
      });
    });

    searchInput.addEventListener("input", () => {
      currentQuery = searchInput.value;
      renderBooks();
    });

    searchBtn.addEventListener("click", () => {
      currentQuery = searchInput.value;
      renderBooks();
    });

    async function renderBooks() {
      const books = await ATLDatabase.searchBooks(currentQuery, currentCategory);
      catalogGrid.innerHTML = "";

      if (books.length === 0) {
        catalogGrid.innerHTML = `
          <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
            <h3 style="font-family: var(--font-heading); font-size: 1.8rem; color: var(--accent-pink);">No Books Found</h3>
            <p style="font-weight: 600; color: var(--text-muted); margin-top: 6px;">Try searching for something else like "Rama", "Shiva", "Harry", or "Donated"!</p>
          </div>
        `;
        return;
      }

      books.forEach(book => {
        const isFav = ATLDatabase.isFavorite(book.id);
        const card = document.createElement("div");
        card.className = "book-card";

        // EVERY book opens a valid reader link!
        const readUrl = `reader.php?book=${encodeURIComponent(book.id)}`;

        card.innerHTML = `
          <div class="book-cover-wrap">
            <img src="${book.cover}" alt="${book.title}" loading="lazy" />
          </div>
          <div class="book-info">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span class="chip-badge">${book.badge || book.genre}</span>
              <button class="fav-icon-btn" data-id="${book.id}" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--accent-amber);" title="Toggle Favorite">
                ${isFav ? '★' : '☆'}
              </button>
            </div>
            <h3 style="font-size: 1.2rem; color: var(--text-heading); margin-top: 4px;">${book.title}</h3>
            <p style="font-size: 0.9rem; color: var(--text-muted); font-weight: 600;">By ${book.author}</p>
            <p style="font-size: 0.88rem; color: var(--text-muted); line-height: 1.4;">${book.description ? book.description.substring(0, 90) + '...' : 'Interactive digital comic book flipbook.'}</p>
            <div style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; padding-top: 12px; border-top: 1px dashed var(--border-color);">
              <span style="font-weight: 800; color: var(--accent-sky);">⭐ ${book.rating || '5.0'}</span>
              <a href="${readUrl}" class="btn-primary" style="padding: 6px 16px; font-size: 0.88rem;">
                Read Flipbook
              </a>
            </div>
          </div>
        `;

        const favBtn = card.querySelector('.fav-icon-btn');
        favBtn.onclick = (e) => {
          e.preventDefault();
          const nowFav = ATLDatabase.toggleFavorite(book.id);
          favBtn.textContent = nowFav ? '★' : '☆';
        };

        catalogGrid.appendChild(card);
      });
    }

    renderBooks();
  });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
