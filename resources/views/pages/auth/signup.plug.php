@extends('layouts.auth')

@section('title', 'Nattination Blog - Sign Up')

@section('content')

<!-- ========== REGISTRATION CONTAINER ========== -->
<div class="registration-container">
    <div class="registration-card">
        <div class="registration-header">
            <a href="{{ url('/') }}" class="registration-logo">Natti<span>Nation</span></a>
            <h1 class="registration-title">Create Account</h1>
            <p class="registration-subtitle">Join our community of readers and writers</p>
        </div>

        <form id="registrationForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="firstName">First Name</label>
                        <div class="input-wrapper">
                            <input type="text" class="form-control has-icon" id="firstName"
                                placeholder="Enter your first name" required autocomplete="given-name">
                            <i class="bi bi-person input-icon"></i>
                        </div>
                        <div class="error-message" id="firstNameError" style="display: none;">
                            <i class="bi bi-exclamation-circle"></i>
                            <span>First name must be at least 2 characters</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="lastName">Last Name</label>
                        <div class="input-wrapper">
                            <input type="text" class="form-control has-icon" id="lastName"
                                placeholder="Enter your last name" required autocomplete="family-name">
                            <i class="bi bi-person input-icon"></i>
                        </div>
                        <div class="error-message" id="lastNameError" style="display: none;">
                            <i class="bi bi-exclamation-circle"></i>
                            <span>Last name must be at least 2 characters</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <div class="input-wrapper">
                    <input type="email" class="form-control has-icon" id="email" placeholder="Enter your email" required
                        autocomplete="email">
                    <i class="bi bi-envelope input-icon"></i>
                </div>
                <div class="error-message" id="emailError" style="display: none;">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Please enter a valid email address</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <div class="input-wrapper">
                    <input type="text" class="form-control has-icon" id="username" placeholder="Choose a username"
                        required autocomplete="username">
                    <i class="bi bi-at input-icon"></i>
                </div>
                <div class="error-message" id="usernameError" style="display: none;">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Username must be 3-20 characters (letters, numbers, _)</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" class="form-control has-password-toggle" id="password"
                        placeholder="Create a strong password" required autocomplete="new-password">
                    <button type="button" class="password-toggle" id="passwordToggle"
                        aria-label="Toggle password visibility">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div class="password-strength">
                    <div class="strength-meter" id="passwordStrength"></div>
                </div>
                <div class="strength-text" id="passwordText">Password strength</div>
                <div class="error-message" id="passwordError" style="display: none;">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Password must be at least 8 characters with uppercase, lowercase, number, and special
                        character</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="confirmPassword">Confirm Password</label>
                <div class="input-wrapper">
                    <input type="password" class="form-control has-password-toggle" id="confirmPassword"
                        placeholder="Repeat your password" required autocomplete="new-password">
                    <button type="button" class="password-toggle" id="confirmPasswordToggle"
                        aria-label="Toggle password visibility">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div class="error-message" id="confirmPasswordError" style="display: none;">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Passwords do not match</span>
                </div>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label" for="terms">
                    I agree to the <a href="{{ url('terms-and-conditions') }}" tabindex="0">Terms of Service</a> and <a href="{{ url('privacy-policy') }}" tabindex="0">Privacy
                        Policy</a>
                </label>
            </div>
            <div class="error-message" id="termsError" style="display: none; margin-top: -1rem; margin-bottom: 1rem;">
                <i class="bi bi-exclamation-circle"></i>
                <span>You must accept the terms and conditions</span>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="newsletter">
                <label class="form-check-label" for="newsletter">
                    Subscribe to our newsletter for updates
                </label>
            </div>

            <button type="submit" class="btn-register" id="submitButton">
                <i class="bi bi-person-plus"></i>
                <span>Create Account</span>
            </button>
        </form>

        <!-- <div class="social-registration">
            <div class="social-divider">
                <span>Or register with</span>
            </div>
            <div class="social-buttons">
                <button class="btn-social btn-google" type="button" aria-label="Register with Google">
                    <i class="bi bi-google"></i>
                </button>
                <button class="btn-social btn-facebook" type="button" aria-label="Register with Facebook">
                    <i class="bi bi-facebook"></i>
                </button>
                <button class="btn-social btn-twitter" type="button" aria-label="Register with Twitter">
                    <i class="bi bi-twitter"></i>
                </button>
            </div>
        </div> -->

        <div class="login-link">
            Already have an account? <a href="{{ url('login') }}" tabindex="0">Sign in here</a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Initialize when page loads
    document.addEventListener("DOMContentLoaded", function () {
        // ========== THEME TOGGLE ==========
        const themeToggle = document.getElementById("themeToggle");
        const themeIcon = document.getElementById("themeIcon");
        const body = document.body;

        // Check for saved theme preference
        const savedTheme = localStorage.getItem("site_theme") || "dark";

        function setTheme(theme) {
            if (theme === "light") {
                body.classList.remove("dark-mode");
                themeIcon.classList.remove("bi-sun-fill");
                themeIcon.classList.add("bi-moon-stars-fill");
            } else {
                body.classList.add("dark-mode");
                themeIcon.classList.remove("bi-moon-stars-fill");
                themeIcon.classList.add("bi-sun-fill");
            }
            localStorage.setItem("site_theme", theme);
        }

        // Initialize theme
        setTheme(savedTheme);

        // Toggle theme on button click
        themeToggle.addEventListener("click", () => {
            const isDark = body.classList.contains("dark-mode");
            setTheme(isDark ? "light" : "dark");
        });

        // ========== PASSWORD TOGGLE ==========
        const passwordToggle = document.getElementById("passwordToggle");
        const confirmPasswordToggle = document.getElementById(
            "confirmPasswordToggle"
        );
        const passwordInput = document.getElementById("password");
        const confirmPasswordInput = document.getElementById("confirmPassword");

        function togglePasswordVisibility(input, toggle) {
            const isPassword = input.type === "password";
            input.type = isPassword ? "text" : "password";
            const icon = toggle.querySelector("i");
            icon.className = isPassword ? "bi bi-eye-slash" : "bi bi-eye";
        }

        passwordToggle.addEventListener("click", () => {
            togglePasswordVisibility(passwordInput, passwordToggle);
        });

        confirmPasswordToggle.addEventListener("click", () => {
            togglePasswordVisibility(confirmPasswordInput, confirmPasswordToggle);
        });

        // ========== VALIDATION FUNCTIONS ==========
        function validateName(name) {
            return name.trim().length >= 2 && /^[a-zA-Z\s]+$/.test(name);
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
                element: passwordInput,
                error: document.getElementById("passwordError"),
            },
            confirmPassword: {
                element: confirmPasswordInput,
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
            updateFieldValidation(this, inputs.firstName.error, isValid);
        });

        inputs.lastName.element.addEventListener("blur", function () {
            const isValid = validateName(this.value);
            updateFieldValidation(this, inputs.lastName.error, isValid);
        });

        inputs.email.element.addEventListener("blur", function () {
            const isValid = validateEmail(this.value);
            updateFieldValidation(this, inputs.email.error, isValid);
        });

        inputs.username.element.addEventListener("blur", function () {
            const isValid = validateUsername(this.value);
            updateFieldValidation(this, inputs.username.error, isValid);
        });

        inputs.password.element.addEventListener("blur", function () {
            const isValid = validatePassword(this.value);
            updateFieldValidation(this, inputs.password.error, isValid);
        });

        inputs.confirmPassword.element.addEventListener("blur", function () {
            const isValid =
                this.value === inputs.password.element.value && this.value.length > 0;
            updateFieldValidation(this, inputs.confirmPassword.error, isValid);
        });

        inputs.terms.element.addEventListener("change", function () {
            const isValid = this.checked;
            updateFieldValidation(this, inputs.terms.error, isValid);
        });

        // Clear error on input
        Object.values(inputs).forEach((input) => {
            if (input.element.type !== "checkbox") {
                input.element.addEventListener("input", function () {
                    if (this.classList.contains("error")) {
                        this.classList.remove("error");
                        input.error.style.display = "none";
                    }
                });
            }
        });

        function updateFieldValidation(field, errorElement, isValid) {
            if (field.type === "checkbox") {
                if (isValid) {
                    field.classList.remove("error");
                    errorElement.style.display = "none";
                } else {
                    field.classList.add("error");
                    errorElement.style.display = "flex";
                }
                return;
            }

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
                    false
                );
                isValid = false;
            }

            if (!validateName(inputs.lastName.element.value)) {
                updateFieldValidation(
                    inputs.lastName.element,
                    inputs.lastName.error,
                    false
                );
                isValid = false;
            }

            if (!validateEmail(inputs.email.element.value)) {
                updateFieldValidation(inputs.email.element, inputs.email.error, false);
                isValid = false;
            }

            if (!validateUsername(inputs.username.element.value)) {
                updateFieldValidation(
                    inputs.username.element,
                    inputs.username.error,
                    false
                );
                isValid = false;
            }

            if (!validatePassword(inputs.password.element.value)) {
                updateFieldValidation(
                    inputs.password.element,
                    inputs.password.error,
                    false
                );
                isValid = false;
            }

            if (
                inputs.confirmPassword.element.value !== inputs.password.element.value
            ) {
                updateFieldValidation(
                    inputs.confirmPassword.element,
                    inputs.confirmPassword.error,
                    false
                );
                isValid = false;
            }

            if (!inputs.terms.element.checked) {
                updateFieldValidation(inputs.terms.element, inputs.terms.error, false);
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
                '<i class="bi bi-check-lg"></i><span>Creating Account...</span>';
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

                // Reset form after delay
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

                    // Reset password visibility
                    passwordInput.type = "password";
                    confirmPasswordInput.type = "password";
                    passwordToggle.querySelector("i").className = "bi bi-eye";
                    confirmPasswordToggle.querySelector("i").className = "bi bi-eye";
                }, 3000);
            }, 2000);
        });

        // ========== 3D CARD TILT EFFECT (Desktop only) ==========
        const card = document.querySelector(".registration-card");
        const isTouchDevice =
            "ontouchstart" in window || navigator.maxTouchPoints > 0;

        if (!isTouchDevice && window.innerWidth > 768) {
            card.addEventListener("mousemove", (e) => {
                const cardRect = card.getBoundingClientRect();
                const cardCenterX = cardRect.left + cardRect.width / 2;
                const cardCenterY = cardRect.top + cardRect.height / 2;

                const mouseX = e.clientX - cardCenterX;
                const mouseY = e.clientY - cardCenterY;

                const rotateX = (mouseY / cardRect.height) * 5;
                const rotateY = (mouseX / cardRect.width) * -5;

                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-5px)`;
            });

            card.addEventListener("mouseleave", () => {
                card.style.transform =
                    "perspective(1000px) rotateX(0) rotateY(0) translateY(0)";
            });
        }
    });

</script>
@endpush