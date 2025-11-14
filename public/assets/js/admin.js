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

// ========== SELECT ALL CHECKBOXES ==========
const selectAllCheckbox = document.getElementById("selectAll");
const tableCheckboxes = document.querySelectorAll(
  ".data-table tbody .table-checkbox"
);
const bulkActionsContainer = document.getElementById("bulkActionsContainer");
const bulkActionsButton = document.getElementById("bulkActionsButton");
const bulkActionsMenu = document.getElementById("bulkActionsMenu");
const selectedCount = document.getElementById("selectedCount");
const bulkActionsCount = document.getElementById("bulkActionsCount");

function updateSelectedCount() {
  const checkedCount = Array.from(tableCheckboxes).filter(
    (cb) => cb.checked
  ).length;
  selectedCount.textContent = `(${checkedCount})`;
  bulkActionsCount.textContent = `${checkedCount} item${
    checkedCount !== 1 ? "s" : ""
  } selected`;

  // Show/hide bulk actions container
  if (checkedCount > 0) {
    bulkActionsContainer.classList.add("show");
  } else {
    bulkActionsContainer.classList.remove("show");
    bulkActionsMenu.style.display = "none";
  }
}

selectAllCheckbox.addEventListener("change", function () {
  tableCheckboxes.forEach((checkbox) => {
    checkbox.checked = this.checked;
  });
  updateSelectedCount();
});

tableCheckboxes.forEach((checkbox) => {
  checkbox.addEventListener("change", function () {
    const allChecked = Array.from(tableCheckboxes).every((cb) => cb.checked);
    const someChecked = Array.from(tableCheckboxes).some((cb) => cb.checked);
    selectAllCheckbox.checked = allChecked;
    selectAllCheckbox.indeterminate = someChecked && !allChecked;
    updateSelectedCount();
  });
});

// ========== BULK ACTIONS MENU ==========
bulkActionsButton.addEventListener("click", function (e) {
  e.stopPropagation();
  bulkActionsMenu.style.display =
    bulkActionsMenu.style.display === "block" ? "none" : "block";
});

// Close bulk actions menu when clicking outside
document.addEventListener("click", function () {
  bulkActionsMenu.style.display = "none";
});

// Handle bulk action selection
document.querySelectorAll(".bulk-action-item").forEach((item) => {
  item.addEventListener("click", function () {
    const action = this.getAttribute("data-action");
    const checkedItems = Array.from(tableCheckboxes).filter((cb) => cb.checked);

    if (checkedItems.length === 0) {
      alert("Please select at least one item.");
      return;
    }

    let actionText;
    switch (action) {
      case "publish":
        actionText = "publish";
        break;
      case "draft":
        actionText = "move to draft";
        break;
      case "archive":
        actionText = "archive";
        break;
      case "duplicate":
        actionText = "duplicate";
        break;
      case "delete":
        actionText = "delete";
        break;
      default:
        actionText = action;
    }

    if (
      confirm(
        `Are you sure you want to ${actionText} ${checkedItems.length} item${
          checkedItems.length !== 1 ? "s" : ""
        }?`
      )
    ) {
      console.log(`Performing ${action} on ${checkedItems.length} items`);
      // Here you would typically make an API call to perform the action

      // Close the menu
      bulkActionsMenu.style.display = "none";

      // Show a success message
      alert(
        `Successfully ${actionText}ed ${checkedItems.length} item${
          checkedItems.length !== 1 ? "s" : ""
        }`
      );

      // Reset checkboxes
      tableCheckboxes.forEach((cb) => (cb.checked = false));
      selectAllCheckbox.checked = false;
      selectAllCheckbox.indeterminate = false;
      updateSelectedCount();
    }
  });
});

// ========== PAGINATION ==========
document.querySelectorAll(".pagination button").forEach((button, index) => {
  if (!button.disabled && button.textContent.trim() !== "...") {
    button.addEventListener("click", function () {
      document.querySelectorAll(".pagination button").forEach((btn) => {
        btn.classList.remove("active");
      });
      if (!this.querySelector("i")) {
        this.classList.add("active");
      }
      console.log("Navigate to page:", this.textContent);
    });
  }
});

// ========== ACTION BUTTONS ==========
document.querySelectorAll(".action-btn.edit").forEach((btn) => {
  btn.addEventListener("click", function (e) {
    e.stopPropagation();
    console.log("Edit post");
  });
});

document.querySelectorAll(".action-btn.delete").forEach((btn) => {
  btn.addEventListener("click", function (e) {
    e.stopPropagation();
    if (confirm("Are you sure you want to delete this post?")) {
      console.log("Delete post");
    }
  });
});

// ========== TABLE ROW CLICK ==========
document.querySelectorAll(".data-table tbody tr").forEach((row) => {
  row.addEventListener("click", function (e) {
    if (
      !e.target.closest(".table-checkbox") &&
      !e.target.closest(".action-btn")
    ) {
      console.log("View post details");
    }
  });
});

// ========== RESPONSIVE HANDLING ==========
function handleResize() {
  if (window.innerWidth > 991) {
    sidebar.classList.remove("active");
    sidebarOverlay.classList.remove("active");
  }
}

window.addEventListener("resize", handleResize);
