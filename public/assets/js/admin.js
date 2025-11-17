// ========== SIDEBAR TOGGLE ==========
const menuToggle = document.getElementById("menuToggle");
const sidebar = document.getElementById("sidebar");
const sidebarOverlay = document.getElementById("sidebarOverlay");
const mainWrapper = document.getElementById("mainWrapper");

menuToggle.addEventListener("click", () => {
  sidebar.classList.toggle("active");
  sidebarOverlay.classList.toggle("active");
});

sidebarOverlay.addEventListener("click", () => {
  sidebar.classList.remove("active");
  sidebarOverlay.classList.remove("active");
});

// Close sidebar when clicking a link on mobile
document.querySelectorAll(".sidebar-nav-link").forEach((link) => {
  link.addEventListener("click", () => {
    if (window.innerWidth <= 991) {
      sidebar.classList.remove("active");
      sidebarOverlay.classList.remove("active");
    }
  });
});

// ========== THEME TOGGLE ==========
const themeToggle = document.getElementById("themeToggle");
const themeIcon = document.getElementById("themeIcon");
const body = document.body;

function updateThemeIcon(isDark) {
  if (isDark) {
    themeIcon.classList.remove("bi-sun-fill");
    themeIcon.classList.add("bi-moon-stars-fill");
  } else {
    themeIcon.classList.remove("bi-moon-stars-fill");
    themeIcon.classList.add("bi-sun-fill");
  }
}

function initTheme() {
  const savedTheme = localStorage.getItem("admin_theme");
  if (savedTheme === "dark") {
    body.classList.add("dark-mode");
    updateThemeIcon(true);
  } else if (savedTheme === "light") {
    body.classList.remove("dark-mode");
    updateThemeIcon(false);
  } else {
    const prefersDark = window.matchMedia(
      "(prefers-color-scheme: dark)"
    ).matches;
    if (prefersDark) {
      body.classList.add("dark-mode");
      updateThemeIcon(true);
    }
  }
}

themeToggle.addEventListener("click", () => {
  body.classList.toggle("dark-mode");
  const isDark = body.classList.contains("dark-mode");
  updateThemeIcon(isDark);
  localStorage.setItem("admin_theme", isDark ? "dark" : "light");
});

initTheme();

// ========== RESPONSIVE HANDLING ==========
function handleResize() {
  if (window.innerWidth > 991) {
    sidebar.classList.remove("active");
    sidebarOverlay.classList.remove("active");
  }
}

window.addEventListener("resize", handleResize);
