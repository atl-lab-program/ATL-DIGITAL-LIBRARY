/* ==========================================================================
   ATL DIGITAL LIBRARY - GLOWING NEON CURSOR & PARTICLE TRAIL ENGINE
   High-energy aesthetic cursor follower for 7th & 8th grade hero readers
   ========================================================================== */

(function () {
  document.addEventListener('DOMContentLoaded', () => {
    // Create cursor elements
    const cursorDot = document.createElement('div');
    cursorDot.className = 'cursor-dot';
    document.body.appendChild(cursorDot);

    const cursorGlow = document.createElement('div');
    cursorGlow.className = 'cursor-glow';
    document.body.appendChild(cursorGlow);

    // Particle canvas for sparkling cursor trail
    const canvas = document.createElement('canvas');
    canvas.className = 'cursor-trail-canvas';
    document.body.appendChild(canvas);
    const ctx = canvas.getContext('2d');

    let mouseX = window.innerWidth / 2;
    let mouseY = window.innerHeight / 2;
    let glowX = mouseX;
    let glowY = mouseY;
    let particles = [];

    function resizeCanvas() {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    // Mouse Movement Listener
    window.addEventListener('mousemove', (e) => {
      mouseX = e.clientX;
      mouseY = e.clientY;

      cursorDot.style.left = `${mouseX}px`;
      cursorDot.style.top = `${mouseY}px`;

      // Spawn glowing particles on movement
      if (Math.random() < 0.6) {
        particles.push({
          x: mouseX + (Math.random() - 0.5) * 8,
          y: mouseY + (Math.random() - 0.5) * 8,
          size: Math.random() * 4 + 2,
          color: ['#00F5D4', '#7B2CBF', '#FF007F', '#FFD700', '#3A86FF'][Math.floor(Math.random() * 5)],
          vx: (Math.random() - 0.5) * 1.5,
          vy: (Math.random() - 0.5) * 1.5,
          life: 25,
          maxLife: 25
        });
      }
    });

    // Smooth Cursor Glow Follower Loop
    function animateCursor() {
      glowX += (mouseX - glowX) * 0.18;
      glowY += (mouseY - glowY) * 0.18;
      cursorGlow.style.left = `${glowX}px`;
      cursorGlow.style.top = `${glowY}px`;

      // Render Particle Canvas
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      for (let i = particles.length - 1; i >= 0; i--) {
        const p = particles[i];
        p.x += p.vx;
        p.y += p.vy;
        p.life--;

        ctx.fillStyle = p.color;
        ctx.globalAlpha = p.life / p.maxLife;
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
        ctx.fill();

        if (p.life <= 0) {
          particles.splice(i, 1);
        }
      }

      requestAnimationFrame(animateCursor);
    }
    animateCursor();

    // Hover scale effect on interactive elements
    const interactiveElements = 'a, button, input, select, textarea, .book-card, .cartoon-card, .filter-tab, .avatar-opt';
    document.addEventListener('mouseover', (e) => {
      if (e.target.closest(interactiveElements)) {
        cursorDot.classList.add('cursor-hover');
        cursorGlow.classList.add('cursor-hover-glow');
      }
    });
    document.addEventListener('mouseout', (e) => {
      if (e.target.closest(interactiveElements)) {
        cursorDot.classList.remove('cursor-hover');
        cursorGlow.classList.remove('cursor-hover-glow');
      }
    });
  });
})();
