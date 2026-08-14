<?php
$pageTitle = 'Softcopy Upload Hub - ATL Digital Library';
$activePage = 'donation';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main style="max-width: 950px; margin: 40px auto; padding: 0 20px;">

  <div style="text-align: center; margin-bottom: 30px;">
    <h1 style="font-size: 2.5rem; margin-bottom: 8px; display: flex; align-items: center; justify-content: center; gap: 10px;">
      <?php echo get_icon('upload', 'icon-svg highlight', 32); ?> Softcopy Upload & Book Donation Hub
    </h1>
    <p style="font-size: 1.1rem; color: var(--text-muted); font-weight: 600;">
      Have a softcopy PDF comic or cover image? Upload it here to contribute to the ATL Library catalog!
    </p>
  </div>

  <div class="ui-card" style="padding: 35px;">
    <form id="softcopyDonationForm">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        
        <!-- LEFT COLUMN: SOFTCOPY & COVER UPLOAD -->
        <div>
          <h3 style="font-size: 1.25rem; color: var(--text-heading); margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
            <?php echo get_icon('image', 'icon-svg', 20); ?> 1. Cover Image Upload
          </h3>

          <div id="coverDropzone" style="border: 2px dashed var(--accent-sky); border-radius: var(--radius-md); padding: 30px 20px; text-align: center; cursor: pointer; background: rgba(2, 132, 199, 0.04); display: flex; flex-direction: column; align-items: center; gap: 10px;">
            <?php echo get_icon('image', 'icon-svg', 36); ?>
            <span style="font-weight: 700; color: var(--accent-sky);">Click to Upload Cover Image</span>
            <span style="font-size: 0.85rem; color: var(--text-muted);">Supports PNG, JPG, WEBP softcopies</span>
            <input type="file" id="coverFileInput" accept="image/*" style="display: none;" />
            <img id="coverPreview" style="width: 130px; height: 180px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--accent-sky); margin-top: 10px; display: none;" alt="Cover Preview" />
          </div>

          <h3 style="font-size: 1.25rem; color: var(--text-heading); margin: 24px 0 14px; display: flex; align-items: center; gap: 8px;">
            <?php echo get_icon('file-text', 'icon-svg', 20); ?> 2. Attach Softcopy PDF File (Optional)
          </h3>

          <div id="pdfDropzone" style="border: 2px dashed var(--accent-violet); border-radius: var(--radius-md); padding: 24px 20px; text-align: center; cursor: pointer; background: rgba(124, 58, 237, 0.04); display: flex; flex-direction: column; align-items: center; gap: 8px;">
            <?php echo get_icon('file-text', 'icon-svg', 32); ?>
            <span style="font-weight: 700; color: var(--accent-violet);" id="pdfStatusText">Click to Attach PDF Document</span>
            <input type="file" id="pdfFileInput" accept="application/pdf" style="display: none;" />
          </div>
        </div>

        <!-- RIGHT COLUMN: BOOK DETAILS -->
        <div>
          <h3 style="font-size: 1.25rem; color: var(--text-heading); margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
            <?php echo get_icon('user-check', 'icon-svg', 20); ?> 3. Book Metadata Details
          </h3>

          <div style="margin-bottom: 16px;">
            <label style="font-family: var(--font-heading); font-size: 0.95rem; font-weight: 700; display: block; margin-bottom: 6px;">Student Donor Name</label>
            <input type="text" id="donorName" class="search-box-ui" style="border-radius: var(--radius-sm); width: 100%; padding: 10px 14px;" placeholder="e.g. Suhaira / Siddharth / Aadi" required />
          </div>

          <div style="margin-bottom: 16px;">
            <label style="font-family: var(--font-heading); font-size: 0.95rem; font-weight: 700; display: block; margin-bottom: 6px;">Book / Comic Title</label>
            <input type="text" id="bookTitle" class="search-box-ui" style="border-radius: var(--radius-sm); width: 100%; padding: 10px 14px;" placeholder="e.g. Marvel Avengers / Indian Myth Heroes" required />
          </div>

          <div style="margin-bottom: 16px;">
            <label style="font-family: var(--font-heading); font-size: 0.95rem; font-weight: 700; display: block; margin-bottom: 6px;">Category / Genre</label>
            <select id="genreSelect" style="width: 100%; padding: 10px 14px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-heading); font-weight: 600;">
              <option value="Mythology">Mythology & History</option>
              <option value="Superhero">Superhero & Comics</option>
              <option value="Fantasy">Fantasy & Magic</option>
              <option value="Humor">Humor & Wimpy Kid</option>
              <option value="Sci-Fi">Sci-Fi & Adventure</option>
            </select>
          </div>

          <div style="margin-bottom: 16px;">
            <label style="font-family: var(--font-heading); font-size: 0.95rem; font-weight: 700; display: block; margin-bottom: 6px;">Short Overview Description</label>
            <textarea id="description" rows="3" style="width: 100%; padding: 10px 14px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-heading); font-family: var(--font-body);" placeholder="Write a brief description of this book..."></textarea>
          </div>

          <button type="submit" class="btn-primary btn-emerald" style="width: 100%; padding: 12px; font-size: 1.1rem;">
            <?php echo get_icon('check', '', 20); ?> Publish Softcopy to Digital Library
          </button>
        </div>

      </div>
    </form>

    <!-- SUCCESS NOTIFICATION CARD -->
    <div id="successCard" style="display: none; background: rgba(5, 150, 105, 0.08); border: 1px solid var(--accent-emerald); padding: 24px; border-radius: var(--radius-md); margin-top: 30px; text-align: center;">
      <h3 style="color: var(--accent-emerald); font-size: 1.6rem; margin-bottom: 8px; display: flex; align-items: center; justify-content: center; gap: 8px;">
        <?php echo get_icon('check', '', 24); ?> Softcopy Uploaded & Published Successfully!
      </h3>
      <p style="color: var(--text-main); font-weight: 600; font-size: 1rem; margin-bottom: 16px;">
        Your donated book has been added directly to the ATL Digital Library catalog.
      </p>
      <a href="library.php" class="btn-primary" style="padding: 10px 24px;">
        <?php echo get_icon('arrow-right', '', 18); ?> View Donated Book in Library Catalog
      </a>
    </div>
  </div>

</main>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const coverDropzone = document.getElementById("coverDropzone");
    const coverFileInput = document.getElementById("coverFileInput");
    const coverPreview = document.getElementById("coverPreview");

    const pdfDropzone = document.getElementById("pdfDropzone");
    const pdfFileInput = document.getElementById("pdfFileInput");
    const pdfStatusText = document.getElementById("pdfStatusText");

    let coverDataUrl = "";
    let pdfDataUrl = "";

    coverDropzone.onclick = () => coverFileInput.click();
    coverFileInput.onchange = (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (ev) => {
          coverDataUrl = ev.target.result;
          coverPreview.src = coverDataUrl;
          coverPreview.style.display = "block";
        };
        reader.readAsDataURL(file);
      }
    };

    pdfDropzone.onclick = () => pdfFileInput.click();
    pdfFileInput.onchange = (e) => {
      const file = e.target.files[0];
      if (file) {
        pdfStatusText.textContent = `Attached: ${file.name} (${Math.round(file.size / 1024)} KB)`;
        pdfStatusText.style.color = "var(--accent-sky)";
        const reader = new FileReader();
        reader.onload = (ev) => {
          pdfDataUrl = ev.target.result;
        };
        reader.readAsDataURL(file);
      }
    };

    const form = document.getElementById("softcopyDonationForm");
    const successCard = document.getElementById("successCard");

    form.onsubmit = (e) => {
      e.preventDefault();
      const donorName = document.getElementById("donorName").value.trim();
      const title = document.getElementById("bookTitle").value.trim();
      const genre = document.getElementById("genreSelect").value;
      const description = document.getElementById("description").value.trim();

      ATLDatabase.addDonatedBook({
        donorName,
        title,
        genre,
        description,
        coverDataUrl,
        pdfDataUrl
      });

      successCard.style.display = "block";
      form.reset();
    };
  });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
