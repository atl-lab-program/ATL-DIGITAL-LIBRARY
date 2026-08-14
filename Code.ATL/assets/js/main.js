/* ==========================================================================
   ATL DIGITAL LIBRARY - MAIN ENGINE & POPUP SEARCH MODAL
   Light/Dark Theme Switcher & Interactive Popup Search Bar
   ========================================================================== */

(function () {
  document.addEventListener('DOMContentLoaded', () => {
    // ----------------------------------------------------------------------
    // 1. Light / Dark Theme Switcher Logic
    // ----------------------------------------------------------------------
    const themeBtn = document.getElementById('themeToggleBtn');
    const themeIconContainer = document.getElementById('themeIconContainer');

    const savedTheme = localStorage.getItem('atl_theme') || 'light';
    setTheme(savedTheme);

    if (themeBtn) {
      themeBtn.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        const nextTheme = currentTheme === 'light' ? 'dark' : 'light';
        setTheme(nextTheme);
      });
    }

    function setTheme(theme) {
      document.documentElement.setAttribute('data-theme', theme);
      localStorage.setItem('atl_theme', theme);

      if (themeIconContainer) {
        if (theme === 'dark') {
          themeIconContainer.innerHTML = `<svg class="icon-svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg> <span class="theme-text">Light</span>`;
        } else {
          themeIconContainer.innerHTML = `<svg class="icon-svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg> <span class="theme-text">Dark</span>`;
        }
      }
    }

    // ----------------------------------------------------------------------
    // 2. Interactive Popup Search Bar Modal Engine
    // ----------------------------------------------------------------------
    const openModalBtn = document.getElementById('openSearchModalBtn');
    const closeModalBtn = document.getElementById('closeSearchModalBtn');
    const modalOverlay = document.getElementById('searchModalOverlay');
    const modalInput = document.getElementById('modalSearchInput');
    const modalResults = document.getElementById('modalSearchResults');

    function openSearchModal() {
      if (modalOverlay) {
        modalOverlay.classList.add('active');
        if (modalInput) {
          modalInput.value = '';
          modalInput.focus();
        }
      }
    }

    function closeSearchModal() {
      if (modalOverlay) {
        modalOverlay.classList.remove('active');
      }
    }

    if (openModalBtn) openModalBtn.addEventListener('click', openSearchModal);
    if (closeModalBtn) closeModalBtn.addEventListener('click', closeSearchModal);

    // Close on backdrop click or ESC key
    if (modalOverlay) {
      modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) closeSearchModal();
      });
    }

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && modalOverlay && modalOverlay.classList.contains('active')) {
        closeSearchModal();
      }
    });

    // Real-Time Search Input Filter inside Modal
    if (modalInput && modalResults) {
      modalInput.addEventListener('input', async function () {
        const query = this.value.trim();
        if (!query) {
          modalResults.innerHTML = `
            <div class="search-placeholder-msg">
              <svg class="icon-svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></svg>
              <p>Type above to instantly search 25+ flipbooks and comics!</p>
            </div>
          `;
          return;
        }

        const books = await ATLDatabase.searchBooks(query, 'all');
        if (books.length === 0) {
          modalResults.innerHTML = `
            <div style="text-align: center; padding: 24px; color: var(--text-muted);">
              <h4 style="font-family: var(--font-heading); color: var(--accent-pink); font-size: 1.2rem;">No matching books found</h4>
              <p style="font-size: 0.9rem;">Try searching for "Rama", "Shiva", "Harry", or "Comic"!</p>
            </div>
          `;
          return;
        }

        modalResults.innerHTML = '';
        books.forEach(book => {
          const item = document.createElement('a');
          item.className = 'search-result-item';
          item.href = book.pdf ? `reader.php?book=${book.id}` : `library.php?q=${encodeURIComponent(book.title)}`;

          item.innerHTML = `
            <img src="${book.cover}" alt="${book.title}" class="search-result-thumb" />
            <div style="flex: 1;">
              <h4 style="font-family: var(--font-heading); font-size: 1.05rem; color: var(--text-heading); margin-bottom: 2px;">${book.title}</h4>
              <p style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">By ${book.author} • ${book.genre}</p>
            </div>
            <span class="btn-primary" style="padding: 4px 12px; font-size: 0.8rem; height: fit-content;">
              Read
            </span>
          `;
          modalResults.appendChild(item);
        });
      });
    }

    // Modal Category Tag Shortcuts
    document.querySelectorAll('.modal-tag-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const query = btn.getAttribute('data-query');
        if (modalInput) {
          modalInput.value = query;
          modalInput.dispatchEvent(new Event('input'));
        }
      });
    });
  });
})();
