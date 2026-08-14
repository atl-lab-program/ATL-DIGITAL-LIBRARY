<?php
/**
 * INTERACTIVE POPUP SEARCH BAR MODAL INCLUDE
 * Provides an overlay modal with real-time book search & category shortcuts.
 */
?>
<!-- POPUP SEARCH MODAL OVERLAY -->
<div id="searchModalOverlay" class="search-modal-overlay">
  <div class="search-modal-container">
    
    <!-- MODAL HEADER -->
    <div class="search-modal-header">
      <div class="search-modal-title">
        <?php echo get_icon('search', 'icon-svg highlight', 24); ?>
        <span>Search Digital Library</span>
      </div>
      <button id="closeSearchModalBtn" class="search-modal-close" title="Close Search (Esc)">
        &times;
      </button>
    </div>

    <!-- SEARCH INPUT FIELD -->
    <div class="search-modal-input-wrap">
      <?php echo get_icon('search', 'search-icon-inside', 22); ?>
      <input type="text" id="modalSearchInput" placeholder="Type book title, author, mythology, heroes..." autocomplete="off" autofocus />
      <span class="search-kbd-hint">ESC to close</span>
    </div>

    <!-- QUICK CATEGORY SHORTCUT CHIPS -->
    <div class="search-modal-tags">
      <span class="tag-label">Quick Search:</span>
      <button class="modal-tag-btn" data-query="Rama">Rama</button>
      <button class="modal-tag-btn" data-query="Shiva">Shiva</button>
      <button class="modal-tag-btn" data-query="Harry Potter">Harry Potter</button>
      <button class="modal-tag-btn" data-query="Percy Jackson">Percy Jackson</button>
      <button class="modal-tag-btn" data-query="Wimpy Kid">Wimpy Kid</button>
    </div>

    <!-- REAL-TIME SEARCH RESULTS LIST -->
    <div id="modalSearchResults" class="search-modal-results">
      <div class="search-placeholder-msg">
        <?php echo get_icon('book-open', 'icon-svg', 32); ?>
        <p>Type above to instantly search 25+ flipbooks and comics!</p>
      </div>
    </div>

  </div>
</div>
