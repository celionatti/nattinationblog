// Initialize when page loads
document.addEventListener("DOMContentLoaded", function () {
  // ========== THEME TOGGLE ==========
  const themeToggle = document.getElementById("themeToggle");
  const themeIcon = document.getElementById("themeIcon");
  const body = document.body;

  // Initialize theme from localStorage
  function initTheme() {
    const savedTheme = localStorage.getItem("site_theme");
    if (savedTheme === "light") {
      body.classList.remove("dark-mode");
      updateThemeIcon(false);
    } else {
      body.classList.add("dark-mode");
      updateThemeIcon(true);
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
    localStorage.setItem("site_theme", isDark ? "dark" : "light");
  });

  // Initialize on page load
  initTheme();

  // ========== VALIDATION FUNCTIONS ==========
  function validateName(name) {
    return name.length >= 2 && /^[a-zA-Z\s]+$/.test(name);
  }

  function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function validateUsername(username) {
    const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
    return usernameRegex.test(username);
  }

  function validatePassword(password) {
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number, 1 special character
    const passwordRegex =
      /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    return passwordRegex.test(password);
  }

  // ========== PASSWORD STRENGTH ==========
  const passwordInput = document.getElementById("password");
  const passwordStrength = document.getElementById("passwordStrength");
  const passwordText = document.getElementById("passwordText");

  passwordInput.addEventListener("input", function () {
    const password = this.value;
    let strength = 0;
    let text = "Password strength";

    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/\d/)) strength++;
    if (password.match(/[^a-zA-Z\d]/)) strength++;

    // Update strength meter
    passwordStrength.className = "strength-meter";
    if (strength === 0) {
      text = "Very weak";
    } else if (strength === 1) {
      passwordStrength.classList.add("strength-weak");
      text = "Weak";
    } else if (strength === 2) {
      passwordStrength.classList.add("strength-medium");
      text = "Medium";
    } else if (strength === 3) {
      passwordStrength.classList.add("strength-medium");
      text = "Good";
    } else {
      passwordStrength.classList.add("strength-strong");
      text = "Strong";
    }

    passwordText.textContent = text;
  });

  // ========== REAL-TIME VALIDATION ==========
  const inputs = {
    firstName: {
      element: document.getElementById("firstName"),
      error: document.getElementById("firstNameError"),
    },
    lastName: {
      element: document.getElementById("lastName"),
      error: document.getElementById("lastNameError"),
    },
    email: {
      element: document.getElementById("email"),
      error: document.getElementById("emailError"),
    },
    username: {
      element: document.getElementById("username"),
      error: document.getElementById("usernameError"),
    },
    password: {
      element: document.getElementById("password"),
      error: document.getElementById("passwordError"),
    },
    confirmPassword: {
      element: document.getElementById("confirmPassword"),
      error: document.getElementById("confirmPasswordError"),
    },
    terms: {
      element: document.getElementById("terms"),
      error: document.getElementById("termsError"),
    },
  };

  // Add event listeners for real-time validation
  inputs.firstName.element.addEventListener("blur", function () {
    const isValid = validateName(this.value);
    updateFieldValidation(
      this,
      inputs.firstName.error,
      isValid,
      "First name must be at least 2 characters"
    );
  });

  inputs.lastName.element.addEventListener("blur", function () {
    const isValid = validateName(this.value);
    updateFieldValidation(
      this,
      inputs.lastName.error,
      isValid,
      "Last name must be at least 2 characters"
    );
  });

  inputs.email.element.addEventListener("blur", function () {
    const isValid = validateEmail(this.value);
    updateFieldValidation(
      this,
      inputs.email.error,
      isValid,
      "Please enter a valid email address"
    );
  });

  inputs.username.element.addEventListener("blur", function () {
    const isValid = validateUsername(this.value);
    updateFieldValidation(
      this,
      inputs.username.error,
      isValid,
      "Username must be 3-20 characters (letters, numbers, _)"
    );
  });

  inputs.password.element.addEventListener("blur", function () {
    const isValid = validatePassword(this.value);
    updateFieldValidation(
      this,
      inputs.password.error,
      isValid,
      "Password must be at least 8 characters with uppercase, lowercase, number, and special character"
    );
  });

  inputs.confirmPassword.element.addEventListener("blur", function () {
    const isValid = this.value === inputs.password.element.value;
    updateFieldValidation(
      this,
      inputs.confirmPassword.error,
      isValid,
      "Passwords do not match"
    );
  });

  inputs.terms.element.addEventListener("change", function () {
    const isValid = this.checked;
    updateFieldValidation(
      this,
      inputs.terms.error,
      isValid,
      "You must accept the terms and conditions"
    );
  });

  function updateFieldValidation(field, errorElement, isValid, errorMessage) {
    if (field.value.trim() === "") {
      field.classList.remove("error", "success");
      errorElement.style.display = "none";
      return;
    }

    if (isValid) {
      field.classList.remove("error");
      field.classList.add("success");
      errorElement.style.display = "none";
    } else {
      field.classList.remove("success");
      field.classList.add("error");
      errorElement.querySelector("span").textContent = errorMessage;
      errorElement.style.display = "flex";
    }
  }

  // ========== FORM SUBMISSION ==========
  const registrationForm = document.getElementById("registrationForm");
  const submitButton = document.getElementById("submitButton");

  registrationForm.addEventListener("submit", function (e) {
    e.preventDefault();

    let isValid = true;

    // Validate all fields
    if (!validateName(inputs.firstName.element.value)) {
      updateFieldValidation(
        inputs.firstName.element,
        inputs.firstName.error,
        false,
        "First name must be at least 2 characters"
      );
      isValid = false;
    }

    if (!validateName(inputs.lastName.element.value)) {
      updateFieldValidation(
        inputs.lastName.element,
        inputs.lastName.error,
        false,
        "Last name must be at least 2 characters"
      );
      isValid = false;
    }

    if (!validateEmail(inputs.email.element.value)) {
      updateFieldValidation(
        inputs.email.element,
        inputs.email.error,
        false,
        "Please enter a valid email address"
      );
      isValid = false;
    }

    if (!validateUsername(inputs.username.element.value)) {
      updateFieldValidation(
        inputs.username.element,
        inputs.username.error,
        false,
        "Username must be 3-20 characters (letters, numbers, _)"
      );
      isValid = false;
    }

    if (!validatePassword(inputs.password.element.value)) {
      updateFieldValidation(
        inputs.password.element,
        inputs.password.error,
        false,
        "Password must be at least 8 characters with uppercase, lowercase, number, and special character"
      );
      isValid = false;
    }

    if (
      inputs.confirmPassword.element.value !== inputs.password.element.value
    ) {
      updateFieldValidation(
        inputs.confirmPassword.element,
        inputs.confirmPassword.error,
        false,
        "Passwords do not match"
      );
      isValid = false;
    }

    if (!inputs.terms.element.checked) {
      updateFieldValidation(
        inputs.terms.element,
        inputs.terms.error,
        false,
        "You must accept the terms and conditions"
      );
      isValid = false;
    }

    if (!isValid) {
      // Scroll to first error
      const firstError = document.querySelector(".error");
      if (firstError) {
        firstError.scrollIntoView({ behavior: "smooth", block: "center" });
      }
      return;
    }

    // Simulate registration success
    const originalText = submitButton.innerHTML;

    submitButton.innerHTML =
      '<i class="bi bi-check-lg me-2"></i> Creating Account...';
    submitButton.disabled = true;

    setTimeout(() => {
      // Show success message
      const successMessage = document.createElement("div");
      successMessage.className = "validation-success";
      successMessage.innerHTML = `
                        <i class="bi bi-check-circle-fill"></i>
                        <div>
                            <strong>Account created successfully!</strong>
                            <div>Welcome to BlogName. You can now sign in.</div>
                        </div>
                    `;

      registrationForm.parentNode.insertBefore(
        successMessage,
        registrationForm
      );

      // Reset form
      setTimeout(() => {
        registrationForm.reset();
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;

        // Remove success message
        successMessage.remove();

        // Reset all field styles
        Object.values(inputs).forEach((input) => {
          if (input.element) {
            input.element.classList.remove("error", "success");
            if (input.error) input.error.style.display = "none";
          }
        });

        passwordStrength.className = "strength-meter";
        passwordText.textContent = "Password strength";
      }, 3000);
    }, 2000);
  });

  // ========== FLOATING PARTICLES ==========
  const particlesContainer = document.getElementById("particles");
  const particleCount = 30;

  for (let i = 0; i < particleCount; i++) {
    const particle = document.createElement("div");
    particle.classList.add("particle");

    // Random properties
    const size = Math.random() * 5 + 2;
    const left = Math.random() * 100;
    const animationDuration = Math.random() * 20 + 10;
    const animationDelay = Math.random() * 20;
    const opacity = Math.random() * 0.5 + 0.1;

    particle.style.width = `${size}px`;
    particle.style.height = `${size}px`;
    particle.style.left = `${left}%`;
    particle.style.animationDuration = `${animationDuration}s`;
    particle.style.animationDelay = `${animationDelay}s`;
    particle.style.opacity = opacity;

    particlesContainer.appendChild(particle);
  }

  // ========== 3D CARD TILT EFFECT ==========
  const card = document.querySelector(".registration-card");

  card.addEventListener("mousemove", (e) => {
    const cardRect = card.getBoundingClientRect();
    const cardCenterX = cardRect.left + cardRect.width / 2;
    const cardCenterY = cardRect.top + cardRect.height / 2;

    const mouseX = e.clientX - cardCenterX;
    const mouseY = e.clientY - cardCenterY;

    const rotateX = (mouseY / cardRect.height) * 10;
    const rotateY = (mouseX / cardRect.width) * -10;

    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px)`;
  });

  card.addEventListener("mouseleave", () => {
    card.style.transform =
      "perspective(1000px) rotateX(0) rotateY(0) translateY(-10px)";
  });
});
