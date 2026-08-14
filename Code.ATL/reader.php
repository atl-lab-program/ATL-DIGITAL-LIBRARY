<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/icons.php';
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flipbook Reader - <?php echo SITE_NAME; ?></title>

  <!-- Modern Fonts & CSS -->
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
  
  <!-- PageFlip & PDF.js -->
  <script src="https://cdn.jsdelivr.net/npm/page-flip/dist/js/page-flip.browser.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

  <style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      overflow: hidden;
      background: #090D16 !important;
      color: #FFFFFF;
    }

    /* TOP TOOLBAR ALWAYS ON TOP (Z-INDEX 1000) */
    .reader-toolbar {
      position: relative;
      height: 60px;
      background: #0F172A;
      border-bottom: 1px solid rgba(255, 255, 255, 0.12);
      padding: 0 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      z-index: 1000;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    }

    .tool-btn-reader {
      font-family: var(--font-heading);
      font-size: 0.9rem;
      font-weight: 800;
      padding: 7px 16px;
      border-radius: var(--radius-full);
      border: 1px solid rgba(255, 255, 255, 0.15);
      background: rgba(255, 255, 255, 0.08);
      color: #FFFFFF;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
      user-select: none;
    }

    .tool-btn-reader:hover {
      background: var(--accent-sky);
      color: #FFFFFF;
      border-color: var(--accent-sky);
      transform: translateY(-2px);
    }

    /* FULL HEIGHT BOOK STAGE WITH OVERFLOW CONTAINMENT */
    .book-stage-full {
      height: calc(100vh - 60px);
      width: 100vw;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
      padding: 20px 70px;
      background: radial-gradient(circle at center, #172033 0%, #090D16 100%);
    }

    #bookContainer {
      margin: 0 auto;
      transition: transform 0.3s ease;
      box-shadow: 0 25px 65px rgba(0, 0, 0, 0.85);
      border-radius: 8px;
    }

    .my-page {
      background: #FFFDF9;
      overflow: hidden;
      position: relative;
      cursor: grab;
      border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .my-page canvas {
      width: 100%;
      height: 100%;
      display: block;
      object-fit: contain;
    }

    .nav-arrow {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 52px;
      height: 52px;
      border-radius: 50%;
      background: rgba(15, 23, 42, 0.95);
      border: 2px solid var(--accent-cyan);
      color: var(--accent-cyan);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 900;
      transition: all 0.2s;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
    }

    .nav-arrow:hover {
      transform: translateY(-50%) scale(1.15);
      background: var(--accent-cyan);
      color: #FFFFFF;
    }

    .prev-arrow { left: 24px; }
    .next-arrow { right: 24px; }

    .page-indicator-floating {
      position: absolute;
      bottom: 16px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(15, 23, 42, 0.95);
      border: 1px solid var(--accent-sky);
      color: #FFFFFF;
      padding: 6px 20px;
      border-radius: var(--radius-full);
      font-family: var(--font-heading);
      font-weight: 800;
      font-size: 0.88rem;
      z-index: 900;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6);
    }
  </style>
</head>
<body>

<audio id="flipSound" src="assets/sounds/page-flip.mp3" preload="auto"></audio>

<!-- SLEEK SINGLE TOOLBAR (Z-INDEX 1000 GUARANTEES NO BOOK OVERLAP) -->
<div class="reader-toolbar">
  <div style="display: flex; align-items: center; gap: 14px;">
    <a href="library.php" class="tool-btn-reader" style="background: var(--accent-sky); border: none;">
      <?php echo get_icon('arrow-left', '', 18); ?> Back to Library
    </a>
    <h2 style="font-family: var(--font-heading); font-size: 1.15rem; font-weight: 800; color: #FFFFFF;" id="bookTitleDisplay">Loading Book...</h2>
    <span class="chip-badge" id="bookAuthorDisplay" style="background: rgba(2, 132, 199, 0.2); color: var(--accent-sky); border-color: var(--accent-sky);">Comic</span>
  </div>

  <div style="display: flex; align-items: center; gap: 8px;">
    <button class="tool-btn-reader" id="favBtn">
      <?php echo get_icon('star', '', 16); ?> Bookmark
    </button>
    <button class="tool-btn-reader" id="soundBtn">
      <?php echo get_icon('volume-2', '', 16); ?> Sound
    </button>
    <button class="tool-btn-reader" id="playBtn">
      <?php echo get_icon('play', '', 16); ?> Auto Play
    </button>
    <button class="tool-btn-reader" id="zoomOutBtn">
      <?php echo get_icon('zoom-out', '', 16); ?>
    </button>
    <button class="tool-btn-reader" id="zoomResetBtn">100%</button>
    <button class="tool-btn-reader" id="zoomInBtn">
      <?php echo get_icon('zoom-in', '', 16); ?>
    </button>
    <button class="tool-btn-reader" id="fullscreenBtn">
      <?php echo get_icon('maximize', '', 16); ?> Fullscreen
    </button>
  </div>
</div>

<!-- CONTAINER STAGE -->
<div class="book-stage-full">
  <button class="nav-arrow prev-arrow" id="prevBtn" title="Previous Page">
    <?php echo get_icon('arrow-left', '', 24); ?>
  </button>
  
  <div id="bookContainer"></div>
  
  <button class="nav-arrow next-arrow" id="nextBtn" title="Next Page">
    <?php echo get_icon('arrow-right', '', 24); ?>
  </button>

  <div class="page-indicator-floating" id="pageBadge">Page 1</div>

  <div id="loadingOverlay" style="position: absolute; inset: 0; background: #090D16; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 16px; z-index: 2000;">
    <div style="width: 54px; height: 54px; border: 5px solid rgba(2, 132, 199, 0.2); border-top-color: var(--accent-sky); border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
    <h2 style="font-family: var(--font-heading); color: var(--accent-sky); font-size: 1.6rem;">
      Opening Digital Flipbook...
    </h2>
  </div>
</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<script src="assets/js/db.js"></script>
<script src="assets/js/cursor.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", async function () {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    const urlParams = new URLSearchParams(window.location.search);
    const bookId = urlParams.get('book') || 'ancestors-of-ram';
    
    let currentBook = await ATLDatabase.getBookById(bookId);
    let pdfPath = currentBook && currentBook.pdf ? currentBook.pdf : 'pdf/Ancestors-of-Rama.pdf';
    if (urlParams.get('pdf')) pdfPath = urlParams.get('pdf');

    document.getElementById('bookTitleDisplay').textContent = currentBook ? currentBook.title : 'Flipbook Reader';
    document.getElementById('bookAuthorDisplay').textContent = currentBook ? currentBook.author : 'Comic';
    document.title = `${currentBook ? currentBook.title : 'Flipbook'} - ATL Reader`;

    const favBtn = document.getElementById('favBtn');
    function updateFavUI() {
      const isFav = currentBook ? ATLDatabase.isFavorite(currentBook.id) : false;
      favBtn.textContent = isFav ? '★ Bookmarked' : '☆ Bookmark';
    }
    if (currentBook) updateFavUI();
    favBtn.onclick = () => {
      if (currentBook) {
        ATLDatabase.toggleFavorite(currentBook.id);
        updateFavUI();
      }
    };

    let soundMuted = false;
    let currentZoom = 1;
    let autoPlayInterval = null;
    let pageFlip = null;

    const bookContainer = document.getElementById("bookContainer");
    const soundBtn = document.getElementById("soundBtn");
    const flipSound = document.getElementById("flipSound");
    const loadingOverlay = document.getElementById("loadingOverlay");

    soundBtn.onclick = () => {
      soundMuted = !soundMuted;
      soundBtn.textContent = soundMuted ? "🔇 Muted" : "🔊 Sound";
    };

    try {
      const pdf = await pdfjsLib.getDocument(pdfPath).promise;
      
      for (let i = 1; i <= pdf.numPages; i++) {
        const pdfPage = await pdf.getPage(i);
        const viewport = pdfPage.getViewport({ scale: 1.6 });

        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");
        canvas.width = viewport.width;
        canvas.height = viewport.height;

        await pdfPage.render({
          canvasContext: ctx,
          viewport: viewport
        }).promise;

        const pageDiv = document.createElement("div");
        pageDiv.className = "my-page";
        pageDiv.appendChild(canvas);
        bookContainer.appendChild(pageDiv);
      }

      // CALIBRATED DIMENSIONS TO PREVENT OVERLAPPING TOOLBAR OR FOOTER
      const stageHeight = window.innerHeight - 100;
      const targetHeight = Math.min(Math.max(stageHeight, 420), 560);
      const targetWidth = Math.round(targetHeight * 0.7);

      pageFlip = new St.PageFlip(bookContainer, {
        width: targetWidth,
        height: targetHeight,
        size: "stretch",
        minWidth: 280,
        maxWidth: 480,
        minHeight: 380,
        maxHeight: 600,
        showCover: true,
        drawShadow: true,
        usePortrait: true,
        autoCenter: true
      });

      pageFlip.loadFromHTML(document.querySelectorAll(".my-page"));
      loadingOverlay.style.display = 'none';

      if (currentBook) {
        const lastPage = ATLDatabase.getReadingProgress(currentBook.id);
        if (lastPage > 1) {
          try { pageFlip.flip(lastPage - 1); } catch (e) {}
        }
      }

      updateUI();

      pageFlip.on("flip", e => {
        updateUI(e);
        if (!soundMuted) {
          flipSound.currentTime = 0;
          flipSound.play().catch(() => {});
        }
        if (currentBook) ATLDatabase.saveReadingProgress(currentBook.id, e.data + 1);
      });

    } catch (err) {
      console.error("PDF Load Error:", err);
      loadingOverlay.innerHTML = `
        <h2 style="color: var(--accent-pink); font-family: var(--font-heading);">Could not load PDF document</h2>
        <p style="color: white;">File path: ${pdfPath}</p>
        <a href="library.php" class="btn-primary">Return to Library</a>
      `;
    }

    function updateUI(e) {
      if (!pageFlip) return;
      let index = e ? e.data : pageFlip.getCurrentPageIndex();
      let total = pageFlip.getPageCount();
      document.getElementById("pageBadge").textContent = `Page ${index + 1} of ${total}`;
      bookContainer.style.transform = `scale(${currentZoom})`;
    }

    document.getElementById("prevBtn").onclick = () => pageFlip && pageFlip.flipPrev();
    document.getElementById("nextBtn").onclick = () => pageFlip && pageFlip.flipNext();

    const zoomReset = document.getElementById("zoomResetBtn");
    function setZoom(val) {
      currentZoom = Math.min(Math.max(val, 0.75), 1.5);
      updateUI();
      zoomReset.textContent = Math.round(currentZoom * 100) + "%";
    }
    document.getElementById("zoomInBtn").onclick = () => setZoom(currentZoom + 0.15);
    document.getElementById("zoomOutBtn").onclick = () => setZoom(currentZoom - 0.15);
    zoomReset.onclick = () => setZoom(1);

    const playBtn = document.getElementById("playBtn");
    playBtn.onclick = () => {
      if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
        autoPlayInterval = null;
        playBtn.textContent = "▶ Auto Play";
      } else {
        playBtn.textContent = "⏸ Pause";
        autoPlayInterval = setInterval(() => {
          if (!pageFlip) return;
          let current = pageFlip.getCurrentPageIndex();
          let total = pageFlip.getPageCount();
          if (current < total - 1) {
            pageFlip.flipNext();
          } else {
            pageFlip.flip(0);
          }
        }, 3500);
      }
    };

    document.getElementById("fullscreenBtn").onclick = () => {
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
      } else {
        document.exitFullscreen();
      }
    };

    document.addEventListener("keydown", e => {
      if (!pageFlip) return;
      if (e.key === "ArrowLeft") pageFlip.flipPrev();
      if (e.key === "ArrowRight") pageFlip.flipNext();
      if (e.key === "+" || e.key === "=") setZoom(currentZoom + 0.15);
      if (e.key === "-" || e.key === "_") setZoom(currentZoom - 0.15);
      if (e.key === "0") setZoom(1);
      if (e.key === "f" || e.key === "F") document.getElementById("fullscreenBtn").click();
    });
  });
</script>

</body>
</html>
