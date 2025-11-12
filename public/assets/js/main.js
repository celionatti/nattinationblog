// Initialize cookie consent when page loads
document.addEventListener("DOMContentLoaded", function () {
  checkCookieConsent();
});

// ========== PAGE LOADER ==========
window.addEventListener("load", function () {
  const pageLoader = document.getElementById("pageLoader");
  const mainContent = document.querySelector(".main-content");
  const body = document.body;
  const loaderProgressBar = document.getElementById("loaderProgressBar");
  const loaderText = document.getElementById("loaderText");

  // Animate the progress bar
  let progress = 0;
  const progressInterval = setInterval(() => {
    progress += Math.random() * 15;
    if (progress > 100) progress = 100;
    loaderProgressBar.style.width = `${progress}%`;

    if (progress >= 100) {
      clearInterval(progressInterval);
    }
  }, 200);

  // Animate the loading text
  const text = loaderText.textContent;
  loaderText.innerHTML = "";
  for (let i = 0; i < text.length; i++) {
    const span = document.createElement("span");
    span.textContent = text[i];
    loaderText.appendChild(span);
  }

  // Minimum loading time to ensure smooth animation
  setTimeout(() => {
    pageLoader.classList.add("hidden");
    mainContent.classList.add("visible");
    body.classList.remove("loading");

    // Remove loader from DOM after transition
    setTimeout(() => {
      pageLoader.style.display = "none";
    }, 500);
  }, 1200); // Slightly longer to show the new animation
});

// ========== SEARCH TOGGLE FUNCTIONALITY ==========
const searchToggle = document.getElementById("searchToggle");
const searchInputContainer = document.getElementById("searchInputContainer");
const searchClose = document.getElementById("searchClose");
const searchInput = document.querySelector(".search-input");

searchToggle.addEventListener("click", function () {
  searchInputContainer.classList.toggle("active");
  if (searchInputContainer.classList.contains("active")) {
    searchInput.focus();
  }
});

searchClose.addEventListener("click", function () {
  searchInputContainer.classList.remove("active");
  searchInput.value = "";
});

// Close search when clicking outside (for desktop)
document.addEventListener("click", function (event) {
  if (window.innerWidth > 576) {
    if (
      !searchInputContainer.contains(event.target) &&
      !searchToggle.contains(event.target)
    ) {
      searchInputContainer.classList.remove("active");
    }
  }
});

// Close search on escape key
document.addEventListener("keydown", function (event) {
  if (event.key === "Escape") {
    searchInputContainer.classList.remove("active");
  }
});

// ========== THEME TOGGLE & COOKIE MANAGEMENT ==========
const themeToggle = document.getElementById("themeToggle");
const themeIcon = document.getElementById("themeIcon");
const body = document.body;

// ========== COOKIE CONSENT FUNCTIONS ==========
const cookieConsent = document.getElementById("cookieConsent");

// Check if user has already made a choice
function checkCookieConsent() {
  const consentGiven = getCookie("cookie_consent");
  if (!consentGiven) {
    // Show banner after a short delay
    setTimeout(() => {
      cookieConsent.classList.add("show");
    }, 1000);
  }
}

// Accept all cookies
function acceptCookies() {
  setCookie("cookie_consent", "accepted", 365);
  setCookie("analytics_cookies", "true", 365);
  setCookie("preference_cookies", "true", 365);
  setCookie("marketing_cookies", "true", 365);

  hideCookieBanner();
  console.log("All cookies accepted");
}

// Decline all cookies (only essential)
function declineCookies() {
  setCookie("cookie_consent", "declined", 365);
  setCookie("analytics_cookies", "false", 365);
  setCookie("preference_cookies", "false", 365);
  setCookie("marketing_cookies", "false", 365);

  hideCookieBanner();
  console.log("Cookies declined - only essential cookies will be used");
}

// Open cookie settings (for future implementation)
function openCookieSettings() {
  // You can implement a modal with individual cookie toggles here
  alert(
    "Cookie settings would open here. This feature can be implemented with a modal."
  );
  console.log("Open cookie settings modal");
}

// Hide the cookie banner
function hideCookieBanner() {
  cookieConsent.classList.remove("show");
  setTimeout(() => {
    cookieConsent.classList.add("hidden");
  }, 400); // Match the CSS transition duration
}

// Cookie utility functions
function setCookie(name, value, days) {
  const date = new Date();
  date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
  const expires = "expires=" + date.toUTCString();
  const secure = window.location.protocol === "https:" ? "; Secure" : "";
  document.cookie = `${name}=${value}; ${expires}; path=/; SameSite=Lax${secure}`;
}

function getCookie(name) {
  const nameEQ = name + "=";
  const ca = document.cookie.split(";");
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) === " ") c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}

// Initialize theme from cookie or system preference
function initTheme() {
  const savedTheme = getCookie("site_theme");
  const pageLoader = document.getElementById("pageLoader");

  if (savedTheme === "dark") {
    body.classList.add("dark-mode");
    if (pageLoader) pageLoader.classList.add("dark");
    updateThemeIcon(true);
  } else if (savedTheme === "light") {
    body.classList.remove("dark-mode");
    if (pageLoader) pageLoader.classList.remove("dark");
    updateThemeIcon(false);
  } else {
    // No saved preference, check system preference
    const prefersDark = window.matchMedia(
      "(prefers-color-scheme: dark)"
    ).matches;
    if (prefersDark) {
      body.classList.add("dark-mode");
      if (pageLoader) pageLoader.classList.add("dark");
      updateThemeIcon(true);
      setCookie("site_theme", "dark", 365);
    } else {
      setCookie("site_theme", "light", 365);
    }
  }
}

function updateThemeIcon(isDark) {
  if (isDark) {
    themeIcon.classList.remove("bi-sun-fill");
    themeIcon.classList.add("bi-moon-stars-fill");
  } else {
    themeIcon.classList.remove("bi-moon-stars-fill");
    themeIcon.classList.add("bi-sun-fill");
  }
}

// Toggle theme on button click
themeToggle.addEventListener("click", () => {
  body.classList.toggle("dark-mode");
  const isDark = body.classList.contains("dark-mode");
  updateThemeIcon(isDark);
  setCookie("site_theme", isDark ? "dark" : "light", 365);
});

// Initialize on page load
initTheme();

// Listen for system theme changes
window
  .matchMedia("(prefers-color-scheme: dark)")
  .addEventListener("change", (e) => {
    const savedTheme = getCookie("site_theme");
    // Only update if user hasn't set a preference
    if (!savedTheme) {
      if (e.matches) {
        body.classList.add("dark-mode");
        updateThemeIcon(true);
      } else {
        body.classList.remove("dark-mode");
        updateThemeIcon(false);
      }
    }
  });

// ========== SCROLL ANIMATIONS ==========
const animateOnScroll = () => {
  const elements = document.querySelectorAll(".animate-on-scroll");

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          // Stagger animation slightly for each element
          setTimeout(() => {
            entry.target.classList.add("visible");
          }, index * 100);
          observer.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.1,
      rootMargin: "0px 0px -50px 0px",
    }
  );

  elements.forEach((element) => {
    observer.observe(element);
  });

  // Respect reduced motion preference
  if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    elements.forEach((element) => {
      element.classList.add("visible");
    });
  }
};

// Initialize animations when DOM is ready
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", animateOnScroll);
} else {
  animateOnScroll();
}

// ========== NAVBAR SCROLL EFFECT ==========
let lastScroll = 0;
const navbar = document.querySelector(".main-navbar");

window.addEventListener("scroll", () => {
  const currentScroll = window.pageYOffset;

  if (currentScroll > 100) {
    navbar.style.boxShadow = "0 2px 10px var(--card-shadow)";
  } else {
    navbar.style.boxShadow = "none";
  }

  lastScroll = currentScroll;
});

// ========== ACTIVE NAV LINK ==========
const navLinks = document.querySelectorAll(".main-navbar .nav-link");
const currentPage = window.location.pathname;

navLinks.forEach((link) => {
  if (
    link.getAttribute("href") === currentPage ||
    (currentPage === "/" && link.getAttribute("href") === "/")
  ) {
    link.classList.add("active");
  }

  link.addEventListener("click", function () {
    navLinks.forEach((l) => l.classList.remove("active"));
    this.classList.add("active");
  });
});

// ========== SMOOTH SCROLL FOR ANCHOR LINKS ==========
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) {
      target.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  });
});

// ========== CARD CLICK HANDLING ==========
document
  .querySelectorAll(".hero-card, .content-card, .article-card")
  .forEach((card) => {
    card.addEventListener("click", function () {
      // Get the article ID from data attribute
      const articleId = this.getAttribute("data-article-id");
      const articleSlug = this.getAttribute("data-article-slug") || "";

      // Navigate to the single article page with the ID
      // window.location.href = `article.html?id=${articleId}`;

      window.location.href = `/articles/${articleSlug}/${articleId}`;

      // Alternative: If using a different URL structure
      // window.location.href = `/article/${articleId}`;
      // window.location.href = `single-article.html?id=${articleId}`;
    });
  });

// ========== LAZY LOADING FALLBACK ==========
if ("loading" in HTMLImageElement.prototype) {
  // Browser supports native lazy loading
  console.log("Native lazy loading supported");
} else {
  // Fallback for browsers that don't support lazy loading
  const images = document.querySelectorAll('img[loading="lazy"]');
  const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target;
        img.src = img.src;
        observer.unobserve(img);
      }
    });
  });

  images.forEach((img) => imageObserver.observe(img));
}

// ========== SCROLL PROGRESS BAR ==========
const scrollProgress = document.getElementById("scrollProgress");

window.addEventListener("scroll", () => {
  const windowHeight =
    document.documentElement.scrollHeight - window.innerHeight;
  const scrolled = (window.scrollY / windowHeight) * 100;
  scrollProgress.style.width = scrolled + "%";
});

// ========== SCROLL TO TOP BUTTON ==========
const scrollToTopBtn = document.getElementById("scrollToTop");

window.addEventListener("scroll", () => {
  if (window.scrollY > 300) {
    scrollToTopBtn.classList.add("visible");
  } else {
    scrollToTopBtn.classList.remove("visible");
  }
});

scrollToTopBtn.addEventListener("click", () => {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
});
