# ⚡ ATL DIGITAL LIBRARY 📚

> **Official Repository**: [github.com/atl-lab-program/ATL-DIGITAL-LIBRARY](https://github.com/atl-lab-program/ATL-DIGITAL-LIBRARY)  
> **Student Developers**: Suhaira (Grade 7), Siddharth (Grade 7), Aadi (Grade 7)  
> **Mentoring Institutions**: BKHM School & Rajhans Vidyalaya under Atal Tinkering Lab (ATL)

---

## 🌟 Overview & Mission

The **ATL Digital Library** is an educational, interactive web platform created by 7th grade students at Atal Tinkering Lab (ATL). The goal of this project is to foster software engineering skills, innovation, and a love for reading by providing a 3D digital flipbook reader, comic collections (Amar Chitra Katha, Harry Potter, Percy Jackson, Wimpy Kid), and a student softcopy donation hub!

---

## ✨ Key Features

- **📖 Interactive 3D Flipbook Reader**: Built with `St.PageFlip` + `PDF.js` canvas rendering, realistic page turn sound effects, page zoom, auto-play, and bookmarking.
- **🎁 Softcopy Upload & Donation Station**: Students can upload PDF comics or book cover images directly to publish into the digital library catalog.
- **🎨 2-Tier Header & Multi-Color Theme**: Features a 2-tier distinct header/navbar with vibrant multi-color pill buttons, full-width hero banner, and a 1-click **Light/Dark Theme Switcher** (saved in `localStorage`).
- **🔍 Popup Search Bar Modal**: Interactive overlay search modal with real-time title/author filtering, category tag shortcuts (`Rama`, `Shiva`, `Harry Potter`, `Wimpy Kid`), and keyboard `Escape` closing.
- **⭐ Student Creators Wall of Fame**: Dedicated showcase honoring **Suhaira**, **Siddharth**, and **Aadi**.
- **⚡ 100% SVG Vector Icons**: Scalable SVG vector icons used across all pages for a crisp, professional UI.
- **💾 File & LocalStorage Database**: Zero SQL dependencies! Uses clean JSON files (`books.json`, `users.json`) merged dynamically with browser `localStorage`.

---

## 📁 Educational File Structure Guide for Students

The project follows a modular **PHP architecture** using template includes (`includes/`) so 7th & 8th grade students can learn real-world web application design:

```
ATL-DIGITAL-LIBRARY/
└── Code.ATL/
    ├── config.php                 # Global configuration, base URLs & student creators registry
    ├── index.php                  # Main landing page with full-width hero banner & student wall of fame
    ├── library.php                # Digital eBook & comic catalog with category filter tabs
    ├── donation.php               # Softcopy PDF & cover image upload station
    ├── reader.php                 # Distraction-free full-screen 3D PDF flipbook reader
    ├── account.php                # Student profile dashboard, reading streak & bookmarked favorites
    ├── about.php                  # About us page showcasing student creators & school info
    ├── terms.php                  # Library guidelines & fair use terms
    ├── includes/
    │   ├── header.php             # Tier 1 Top Header Bar (Logos, Title Banner, Student Badge)
    │   ├── navbar.php             # Tier 2 Navigation Bar (Multi-color pills & Popup Search trigger)
    │   ├── search_modal.php       # Interactive Popup Search Bar Overlay Modal
    │   ├── footer.php             # Shared site footer & script dependencies
    │   └── icons.php              # Inline SVG Vector Icon helper function get_icon()
    ├── assets/
    │   ├── css/
    │   │   └── style.css          # Core CSS design system (Vibrant Light & Dark Mode)
    │   ├── js/
    │   │   ├── main.js            # Theme switcher & Popup Search Modal engine
    │   │   ├── cursor.js          # Interactive glowing cursor follower & particle trail
    │   │   └── db.js              # Database engine handling static JSON + localStorage donations
    │   ├── images/                # School badges (BKHM & Rajhans) and hero banner
    │   └── sounds/                # Audio effects (page-flip.mp3)
    └── data/
        ├── books.json             # Static book catalog database
        └── users.json             # Demo student user accounts
```

### 📄 One-Line File Summary

| File Path | Description / Purpose |
| :--- | :--- |
| `config.php` | Defines site global constants, base URL helper, and student creators registry array. |
| `index.php` | Landing page featuring full-width hero banner, student creators showcase, and top-rated books. |
| `library.php` | Catalog page with instant search input and category filter tabs (ACK, Harry Potter, Wimpy Kid, etc.). |
| `donation.php` | Upload station allowing students to submit softcopy PDF books and cover images. |
| `reader.php` | Dedicated distraction-free 3D flipbook viewer with audio, zoom, auto-play, and bookmarking. |
| `account.php` | Student profile portal displaying unlocked reading streak badges and saved favorites. |
| `about.php` | Creators wall of fame celebrating Suhaira, Siddharth, and Aadi under Atal Tinkering Lab. |
| `terms.php` | Educational guidelines, privacy policy, and fair use guidelines for students. |
| `includes/header.php` | HTML `<head>` setup and Tier-1 Top Header Bar containing school emblems. |
| `includes/navbar.php` | Tier-2 Navigation Bar containing multi-color navigation pills, Popup Search button, and Theme Switcher. |
| `includes/search_modal.php` | Overlay modal for real-time book title/author search and category shortcuts. |
| `includes/footer.php` | Shared site footer with credit badge and script inclusions. |
| `includes/icons.php` | Helper function `get_icon($name)` rendering inline SVG vector icons. |
| `assets/css/style.css` | Universal CSS tokens, Light/Dark mode themes, responsive grid layouts, and animations. |
| `assets/js/db.js` | JavaScript database manager for searching, filtering, favoriting, and local storage persistence. |
| `assets/js/main.js` | Handles Light/Dark mode toggle state and Popup Search Modal event listeners. |
| `assets/js/cursor.js` | Interactive glowing dot cursor follower and particle canvas engine. |

---

## 🛠️ Tech Stack & Dependencies

- **Language**: PHP 8+, JavaScript ES6, HTML5, CSS3
- **Libraries**:
  - [St.PageFlip](https://github.com/Nodonomore/StPageFlip) for 3D page flip effects
  - [PDF.js](https://mozilla.github.io/pdf.js/) for rendering PDF pages directly to HTML5 canvas
- **Database**: Pure JSON files + Browser `localStorage` (No SQL installation required!)

---

## 🚀 How to Run Locally

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/atl-lab-program/ATL-DIGITAL-LIBRARY.git
   cd ATL-DIGITAL-LIBRARY
   ```

2. **Start PHP Development Server**:
   ```bash
   cd Code.ATL
   php -S localhost:8080
   ```

3. **Open in Browser**:
   Navigate to **`http://localhost:8080/index.php`** in your browser!

4. **How to run via vs code**
   ```bash
   C:/php/php.exe -d upload_max_filesize=350M -d post_max_size=355M -S localhost:8000
   ```

---

## 🌟 Student Creators Credits

Made with passion by 7th grade student innovators at Atal Tinkering Lab (ATL):
- **Suhaira** (Grade 7): Lead UI/UX & Catalog Architect
- **Siddharth** (Grade 7): PDF Database & Digital Books Manager
- **Aadi** (Grade 7): Comic Collections & Audio Curator

Mentored by **BKHM School** and **Rajhans Vidyalaya**.
