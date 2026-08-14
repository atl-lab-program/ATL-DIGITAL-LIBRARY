/* ==========================================================================
   ATL DIGITAL LIBRARY - FILE & LOCALSTORAGE DATABASE ENGINE (v2.0)
   Supports Static JSON + Dynamic Softcopy Book Donations & Uploads
   ========================================================================== */

const ATLDatabase = (function () {
  const STORAGE_KEY_USER = 'atl_current_user';
  const STORAGE_KEY_FAVORITES = 'atl_user_favorites';
  const STORAGE_KEY_PROGRESS = 'atl_user_progress';
  const STORAGE_KEY_DONATED_BOOKS = 'atl_custom_donated_books';

  let booksCache = null;

  function getBasePath() {
    const path = window.location.pathname;
    if (path.includes('/pages/') || path.includes('/sections/') || path.includes('/flipbook/')) {
      return '../..';
    }
    return '.';
  }

  // Get Custom Donated Softcopy Books from LocalStorage
  function getDonatedBooks() {
    const saved = localStorage.getItem(STORAGE_KEY_DONATED_BOOKS);
    return saved ? JSON.parse(saved) : [];
  }

  // Add New Donated Softcopy Book
  function addDonatedBook(bookObj) {
    const donated = getDonatedBooks();
    const newBook = {
      id: 'donated_' + Date.now(),
      title: bookObj.title,
      author: bookObj.donorName || 'Student Donor',
      genre: bookObj.genre || 'Donated Book',
      category: 'donated',
      cover: bookObj.coverDataUrl || `${getBasePath()}/assets/images/ack/ancestors-of-rama.png`,
      pdf: bookObj.pdfDataUrl || '',
      rating: '5.0',
      description: bookObj.description || 'Donated by a student member of ATL Library.',
      badge: '🎁 Student Donated',
      badgeColor: 'green',
      isDonated: true
    };
    donated.unshift(newBook);
    localStorage.setItem(STORAGE_KEY_DONATED_BOOKS, JSON.stringify(donated));
    booksCache = null; // reset cache
    return newBook;
  }

  // Fetch all books (JSON base + Donated softcopies)
  async function getAllBooks() {
    if (booksCache) return booksCache;
    let baseBooks = [];
    try {
      const basePath = getBasePath();
      const response = await fetch(`${basePath}/data/books.json`);
      if (response.ok) {
        baseBooks = await response.json();
      }
    } catch (e) {
      console.warn('Could not fetch data/books.json, using fallback books.', e);
      baseBooks = getFallbackBooks();
    }

    if (baseBooks.length === 0) baseBooks = getFallbackBooks();

    const donatedBooks = getDonatedBooks();
    booksCache = [...donatedBooks, ...baseBooks];
    return booksCache;
  }

  async function getBookById(id) {
    const books = await getAllBooks();
    return books.find(b => b.id === id) || books[0];
  }

  async function searchBooks(query = '', category = 'all') {
    const books = await getAllBooks();
    const q = query.toLowerCase().trim();
    return books.filter(book => {
      const matchesCategory = (category === 'all' || book.category === category || book.genre.toLowerCase() === category.toLowerCase());
      const matchesQuery = !q || 
        book.title.toLowerCase().includes(q) || 
        book.author.toLowerCase().includes(q) || 
        book.genre.toLowerCase().includes(q) || 
        (book.description && book.description.toLowerCase().includes(q));
      return matchesCategory && matchesQuery;
    });
  }

  // User Session Management
  function getCurrentUser() {
    const saved = localStorage.getItem(STORAGE_KEY_USER);
    if (saved) return JSON.parse(saved);
    return {
      username: 'suhaira',
      fullName: 'Suhaira',
      grade: '8th Grade',
      avatar: '🦁',
      role: 'Student & Creator'
    };
  }

  function setCurrentUser(userObj) {
    localStorage.setItem(STORAGE_KEY_USER, JSON.stringify(userObj));
  }

  function loginUser(username) {
    const user = {
      username: username.toLowerCase(),
      fullName: username.charAt(0).toUpperCase() + username.slice(1),
      grade: '7th/8th Grade',
      avatar: ['🦁', '🤖', '🚀', '🦉', '🧙‍♂️', '⚡'][Math.floor(Math.random() * 6)],
      role: 'Student Reader'
    };
    setCurrentUser(user);
    return user;
  }

  // Favorites Management
  function getFavorites() {
    const favs = localStorage.getItem(STORAGE_KEY_FAVORITES);
    return favs ? JSON.parse(favs) : ['ancestors-of-ram', 'monkey-stories', 'tales-of-shiva'];
  }

  function toggleFavorite(bookId) {
    const favs = getFavorites();
    const index = favs.indexOf(bookId);
    let isFav = false;
    if (index >= 0) {
      favs.splice(index, 1);
    } else {
      favs.push(bookId);
      isFav = true;
    }
    localStorage.setItem(STORAGE_KEY_FAVORITES, JSON.stringify(favs));
    return isFav;
  }

  function isFavorite(bookId) {
    return getFavorites().includes(bookId);
  }

  // Reading Progress Storage
  function saveReadingProgress(bookId, pageNum) {
    const progress = getReadingProgress();
    progress[bookId] = { page: pageNum, timestamp: Date.now() };
    localStorage.setItem(STORAGE_KEY_PROGRESS, JSON.stringify(progress));
  }

  function getReadingProgress(bookId = null) {
    const saved = localStorage.getItem(STORAGE_KEY_PROGRESS);
    const progress = saved ? JSON.parse(saved) : {};
    if (bookId) return progress[bookId] ? progress[bookId].page : 1;
    return progress;
  }

  function getFallbackBooks() {
    return [
      { id: "ancestors-of-ram", title: "Ancestors of Rama", author: "Amar Chitra Katha", genre: "Mythology", category: "ack", cover: `${getBasePath()}/assets/images/ack/ancestors-of-rama.png`, pdf: `${getBasePath()}/pdf/Ancestors-of-Rama.pdf`, rating: "4.9", description: "Glorious tales of Lord Rama's ancestors.", badge: "Mythology Master", badgeColor: "yellow" },
      { id: "ayyappan", title: "Ayyappan", author: "Amar Chitra Katha", genre: "Mythology", category: "ack", cover: `${getBasePath()}/assets/images/ack/ayyapan.png`, pdf: `${getBasePath()}/pdf/Ayyappan.pdf`, rating: "4.8", description: "The divine birth and adventures of Lord Ayyappan.", badge: "Divine Tales", badgeColor: "cyan" },
      { id: "banda-bahadur", title: "Banda Bahadur", author: "Amar Chitra Katha", genre: "History", category: "ack", cover: `${getBasePath()}/assets/images/ack/banda-bahadur.png`, pdf: `${getBasePath()}/pdf/Banda-Bahadur.pdf`, rating: "4.9", description: "Brave chronicle of warrior commander Banda Bahadur.", badge: "Hero Legend", badgeColor: "pink" },
      { id: "tales-of-shiva", title: "Tales of Shiva", author: "Amar Chitra Katha", genre: "Mythology", category: "ack", cover: `${getBasePath()}/assets/images/ack/tales-of-shiva.png`, pdf: `${getBasePath()}/pdf/tales-of-shiva.pdf`, rating: "5.0", description: "Stories of Mahadeva Lord Shiva.", badge: "Top Rated", badgeColor: "yellow" }
    ];
  }

  return {
    getAllBooks,
    getBookById,
    searchBooks,
    addDonatedBook,
    getDonatedBooks,
    getCurrentUser,
    setCurrentUser,
    loginUser,
    getFavorites,
    toggleFavorite,
    isFavorite,
    saveReadingProgress,
    getReadingProgress,
    getBasePath
  };
})();
