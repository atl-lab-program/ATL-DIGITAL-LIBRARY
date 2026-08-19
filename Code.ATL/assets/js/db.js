/*
 * ATL DIGITAL LIBRARY - DATABASE ENGINE (db.js)
 */

const ATLDatabase = (function () {
  const STORAGE_KEY_USER = 'atl_user_session';
  const STORAGE_KEY_FAVORITES = 'atl_user_favorites';
  const STORAGE_KEY_PROGRESS = 'atl_user_reading_progress';
  const STORAGE_KEY_DONATED_BOOKS = 'atl_custom_donated_books';

  let booksCache = null;

  function getBasePath() {
    const path = window.location.pathname;
    if (path.includes('/pages/') || path.includes('/sections/') || path.includes('/flipbook/')) {
      return '..';
    }
    return '.';
  }

  /* DONATED BOOKS (LOCALSTORAGE FALLBACK) */
  function getDonatedBooks() {
    try {
      const saved = localStorage.getItem(STORAGE_KEY_DONATED_BOOKS);
      return saved ? JSON.parse(saved) : [];
    } catch (e) {
      console.warn("Could not read local donated books", e);
      return [];
    }
  }

  function addDonatedBook(bookData) {
    const donated = getDonatedBooks();
    const newBook = {
      id: 'donated_' + Date.now(),
      title: bookData.title || 'Untitled',
      author: bookData.donorName ? `Donated by ${bookData.donorName}` : 'Student Donor',
      genre: bookData.genre || 'General',
      category: 'donated',
      cover: bookData.coverDataUrl || `${getBasePath()}/assets/images/ack/ancestors-of-rama.png`,
      pdf: bookData.pdfDataUrl || '',
      rating: '5.0',
      description: bookData.description || `Donated by a student member of ATL Library.`,
      badge: '🎁 Donated Book',
      badgeColor: 'green',
      isDonated: true
    };

    donated.unshift(newBook);
    try {
      localStorage.setItem(STORAGE_KEY_DONATED_BOOKS, JSON.stringify(donated));
    } catch (e) {
      console.warn("LocalStorage space full for PDF DataURL", e);
    }
    booksCache = null; // Reset cache
    return newBook;
  }

  /* CATALOG FETCHING */
  async function getAllBooks() {
    if (booksCache) return booksCache;

    let baseBooks = [];
    let serverDonatedBooks = [];

    const basePath = getBasePath();

    // 1. Fetch Main Catalog Books
    try {
      const response = await fetch(`${basePath}/data/books.json`);
      if (response.ok) {
        baseBooks = await response.json();
      } else {
        const altResponse = await fetch(`${basePath}/json/books.json`);
        if (altResponse.ok) baseBooks = await altResponse.json();
      }
    } catch (e) {
      console.warn('Could not fetch main books.json:', e);
      baseBooks = getFallbackBooks();
    }

    // 2. Fetch Server Donated Books from data/donated_books.json
    try {
      const donatedRes = await fetch(`${basePath}/data/donated_books.json?_=${Date.now()}`);
      if (donatedRes.ok) {
        serverDonatedBooks = await donatedRes.json();
      }
    } catch (e) {
      console.warn('Could not fetch data/donated_books.json:', e);
    }

    // 3. Get LocalStorage Donated Books
    const localDonated = getDonatedBooks();

    // Combine all sources into cache
    booksCache = [...serverDonatedBooks, ...localDonated, ...baseBooks];
    return booksCache;
  }

  function clearCache() {
    booksCache = null;
  }

  async function getBookById(id) {
    const books = await getAllBooks();
    return books.find(b => String(b.id) === String(id)) || books[0];
  }

  async function searchBooks(query = '', category = 'all') {
    const books = await getAllBooks();
    const q = query.toLowerCase().trim();
    return books.filter(book => {
      const matchesCategory = category === 'all' || 
                              (book.category && book.category.toLowerCase() === category.toLowerCase()) || 
                              (book.genre && book.genre.toLowerCase() === category.toLowerCase());
      if (!q) return matchesCategory;
      const matchesQuery = book.title.toLowerCase().includes(q) ||
                           book.author.toLowerCase().includes(q) ||
                           (book.genre && book.genre.toLowerCase().includes(q)) ||
                           (book.description && book.description.toLowerCase().includes(q));
      return matchesCategory && matchesQuery;
    });
  }

  /* USER SESSION MANAGEMENT */
  function getCurrentUser() {
    const saved = localStorage.getItem(STORAGE_KEY_USER);
    if (saved) {
      try { return JSON.parse(saved); } catch (e) {}
    }
    return {
      username: 'Suhaira',
      displayName: 'Suhaira',
      grade: '7th Grade',
      avatar: '1',
      role: 'Student Reader & Creator'
    };
  }

  function setCurrentUser(userObj) {
    localStorage.setItem(STORAGE_KEY_USER, JSON.stringify(userObj));
  }

  function loginUser(username) {
    const user = {
      username: username.trim(),
      displayName: username.trim().charAt(0).toUpperCase() + username.trim().slice(1),
      grade: '7th Grade',
      avatar: String(Math.floor(Math.random() * 6) + 1),
      role: 'Student Reader'
    };
    setCurrentUser(user);
    return user;
  }

  /* FAVORITES MANAGEMENT */
  function getFavorites() {
    const saved = localStorage.getItem(STORAGE_KEY_FAVORITES);
    return saved ? JSON.parse(saved) : ['ancestors-of-ram', 'monkey-stories', 'tales-of-shiva'];
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

  /* READING PROGRESS */
  function saveReadingProgress(bookId, pageNum) {
    const progress = getReadingProgress();
    progress[bookId] = pageNum;
    localStorage.setItem(STORAGE_KEY_PROGRESS, JSON.stringify(progress));
  }

  function getReadingProgress(bookId = null) {
    const saved = localStorage.getItem(STORAGE_KEY_PROGRESS);
    const progress = saved ? JSON.parse(saved) : {};
    if (bookId) return progress[bookId] || 1;
    return progress;
  }

  function getFallbackBooks() {
    return [
      { id: 'ancestors-of-ram', title: 'Ancestors of Rama', author: 'Amar Chitra Katha', genre: 'Mythology', category: 'ack', cover: `${getBasePath()}/assets/images/ack/ancestors-of-rama.png` },
      { id: 'ayyappan', title: 'Ayyappan', author: 'Amar Chitra Katha', genre: 'Mythology', category: 'ack', cover: `${getBasePath()}/assets/images/ack/ancestors-of-rama.png` },
      { id: 'banda-bahadur', title: 'Banda Bahadur', author: 'Amar Chitra Katha', genre: 'History', category: 'ack', cover: `${getBasePath()}/assets/images/ack/ancestors-of-rama.png` },
      { id: 'tales-of-shiva', title: 'Tales of Shiva', author: 'Amar Chitra Katha', genre: 'Mythology', category: 'ack', cover: `${getBasePath()}/assets/images/ack/ancestors-of-rama.png` }
    ];
  }

  return {
    getAllBooks,
    getBookById,
    searchBooks,
    addDonatedBook,
    getDonatedBooks,
    setCurrentUser,
    getCurrentUser,
    loginUser,
    toggleFavorite,
    isFavorite,
    getFavorites,
    saveReadingProgress,
    getReadingProgress,
    clearCache,
    getBasePath
  };
})();
