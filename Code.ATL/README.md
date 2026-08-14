# ⚡ ATL DIGITAL LIBRARY (Code.ATL) 📚

> **Official Repository**: [github.com/atl-lab-program/ATL-DIGITAL-LIBRARY](https://github.com/atl-lab-program/ATL-DIGITAL-LIBRARY)  
> **Student Developers**: Suhaira (Grade 8), Siddharth (Grade 8), Aadi (Grade 7)  
> **Mentoring Institutions**: BKHM School & Rajhans Vidyalaya under Atal Tinkering Lab (ATL)

---

## 📁 Educational File Structure Guide for Students

This codebase follows a modular **PHP architecture** using template includes (`includes/`) so 7th & 8th grade students can learn real-world web application design:

```
Code.ATL/
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

## 🚀 How to Run Locally

```bash
cd Code.ATL
php -S localhost:8080
```
Open **`http://localhost:8080/index.php`** in your browser!
