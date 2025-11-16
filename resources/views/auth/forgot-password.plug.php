@extends('layouts.auth')

@section('title', 'Nattination Blog - Forgot Password')

@section('content')

<!-- ========== FORGOT PASSWORD CONTAINER ========== -->
<div class="forgot-password-container">
    <div class="forgot-password-card">
        <div class="forgot-password-header">
            <a href="{{ url('/') }}" class="forgot-password-logo">Natti<span>Nation</span></a>
        </div>

        <!-- Steps Indicator -->
        <div class="steps-container" id="stepsContainer">
            <div class="step active" id="step1">
                <div class="step-circle">1</div>
                <div class="step-label">Enter Email</div>
            </div>
            <div class="step-divider"></div>
            <div class="step" id="step2">
                <div class="step-circle">2</div>
                <div class="step-label">Verify Code</div>
            </div>
            <div class="step-divider"></div>
            <div class="step" id="step3">
                <div class="step-circle">3</div>
                <div class="step-label">Reset</div>
            </div>
        </div>

        <!-- Step 1: Email Form -->
        <div id="emailStep">
            <div class="icon-container">
                <i class="bi bi-lock-fill"></i>
            </div>
            <h1 class="forgot-password-title">Forgot Password?</h1>
            <p class="forgot-password-subtitle">
                No worries! Enter your email address and we'll send you a code to reset your password.
            </p>

            <div class="info-box">
                <i class="bi bi-info-circle-fill"></i>
                <div class="info-box-content">
                    Make sure to check your spam folder if you don't see the email in your inbox.
                </div>
            </div>

            <form id="emailForm">
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" class="form-control has-icon" id="email" placeholder="Enter your email"
                            required autocomplete="email">
                        <i class="bi bi-envelope input-icon"></i>
                    </div>
                    <div class="error-message" id="emailError" style="display: none;">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>Please enter a valid email address</span>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="emailButton">
                    <i class="bi bi-send-fill"></i>
                    <span>Send Reset Code</span>
                </button>
            </form>

            <div class="back-to-login">
                <a href="{{ url('login') }}" tabindex="0">
                    <i class="bi bi-arrow-left"></i>
                    Back to Sign In
                </a>
            </div>
        </div>

        <!-- Step 2: Verification Code -->
        <div id="codeStep" class="hidden">
            <div class="icon-container">
                <i class="bi bi-shield-check"></i>
            </div>
            <h1 class="forgot-password-title">Verify Your Email</h1>
            <p class="forgot-password-subtitle">
                We've sent a 6-digit verification code to <strong id="userEmail"></strong>
            </p>

            <div class="info-box">
                <i class="bi bi-info-circle-fill"></i>
                <div class="info-box-content">
                    The code will expire in 10 minutes. Didn't receive it? <a href="#" id="resendCode"
                        style="color: var(--accent); text-decoration: underline;">Resend code</a>
                </div>
            </div>

            <form id="codeForm">
                <div class="form-group">
                    <label class="form-label" for="verificationCode">Verification Code</label>
                    <div class="input-wrapper">
                        <input type="text" class="form-control has-icon" id="verificationCode"
                            placeholder="Enter 6-digit code" required maxlength="6" pattern="[0-9]{6}">
                        <i class="bi bi-key input-icon"></i>
                    </div>
                    <div class="error-message" id="codeError" style="display: none;">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>Please enter a valid 6-digit code</span>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="codeButton">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Verify Code</span>
                </button>
            </form>

            <div class="back-to-login">
                <a href="#" id="backToEmail">
                    <i class="bi bi-arrow-left"></i>
                    Use different email
                </a>
            </div>
        </div>

        <!-- Step 3: Reset Password -->
        <div id="resetStep" class="hidden">
            <div class="icon-container">
                <i class="bi bi-key-fill"></i>
            </div>
            <h1 class="forgot-password-title">Reset Password</h1>
            <p class="forgot-password-subtitle">
                Create a new strong password for your account.
            </p>

            <form id="resetForm">
                <div class="form-group">
                    <label class="form-label" for="newPassword">New Password</label>
                    <div class="input-wrapper">
                        <input type="password" class="form-control has-icon" id="newPassword"
                            placeholder="Create new password" required autocomplete="new-password">
                        <i class="bi bi-lock input-icon"></i>
                    </div>
                    <div class="error-message" id="newPasswordError" style="display: none;">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>Password must be at least 8 characters with uppercase, lowercase, number, and special
                            character</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="confirmNewPassword">Confirm New Password</label>
                    <div class="input-wrapper">
                        <input type="password" class="form-control has-icon" id="confirmNewPassword"
                            placeholder="Confirm new password" required autocomplete="new-password">
                        <i class="bi bi-lock-fill input-icon"></i>
                    </div>
                    <div class="error-message" id="confirmNewPasswordError" style="display: none;">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>Passwords do not match</span>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="resetButton">
                    <i class="bi bi-arrow-repeat"></i>
                    <span>Reset Password</span>
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // ========== THEME TOGGLE ==========
        const themeToggle = document.getElementById("themeToggle");
        const themeIcon = document.getElementById("themeIcon");
        const body = document.body;

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

        setTheme(savedTheme);

        themeToggle.addEventListener("click", () => {
            const isDark = body.classList.contains("dark-mode");
            setTheme(isDark ? "light" : "dark");
        });

        // ========== STEP MANAGEMENT ==========
        const emailStep = document.getElementById("emailStep");
        const codeStep = document.getElementById("codeStep");
        const resetStep = document.getElementById("resetStep");

        const step1 = document.getElementById("step1");
        const step2 = document.getElementById("step2");
        const step3 = document.getElementById("step3");

        function showStep(stepNumber) {
            // Hide all steps
            emailStep.classList.add("hidden");
            codeStep.classList.add("hidden");
            resetStep.classList.add("hidden");

            // Remove active from all step indicators
            step1.classList.remove("active", "completed");
            step2.classList.remove("active", "completed");
            step3.classList.remove("active", "completed");

            // Show appropriate step
            if (stepNumber === 1) {
                emailStep.classList.remove("hidden");
                step1.classList.add("active");
            } else if (stepNumber === 2) {
                codeStep.classList.remove("hidden");
                step1.classList.add("completed");
                step2.classList.add("active");
            } else if (stepNumber === 3) {
                resetStep.classList.remove("hidden");
                step1.classList.add("completed");
                step2.classList.add("completed");
                step3.classList.add("active");
            }
        }

        // ========== VALIDATION FUNCTIONS ==========
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function validatePassword(password) {
            const passwordRegex =
                /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            return passwordRegex.test(password);
        }

        function validateCode(code) {
            return /^\d{6}$/.test(code);
        }

        function updateFieldValidation(field, errorElement, isValid, errorMessage) {
            if (field.value.trim() === "") {
                field.classList.remove("error");
                errorElement.style.display = "none";
                return;
            }

            if (isValid) {
                field.classList.remove("error");
                errorElement.style.display = "none";
            } else {
                field.classList.add("error");
                if (errorMessage) {
                    errorElement.querySelector("span").textContent = errorMessage;
                }
                errorElement.style.display = "flex";
            }
        }

        // ========== STEP 1: EMAIL FORM ==========
        const emailForm = document.getElementById("emailForm");
        const emailInput = document.getElementById("email");
        const emailError = document.getElementById("emailError");
        const emailButton = document.getElementById("emailButton");
        const userEmailDisplay = document.getElementById("userEmail");

        emailInput.addEventListener("blur", function () {
            if (this.value.trim() === "") {
                this.classList.remove("error");
                emailError.style.display = "none";
                return;
            }
            const isValid = validateEmail(this.value);
            updateFieldValidation(this, emailError, isValid);
        });

        emailInput.addEventListener("input", function () {
            if (this.classList.contains("error")) {
                this.classList.remove("error");
                emailError.style.display = "none";
            }
        });

        emailForm.addEventListener("submit", function (e) {
            e.preventDefault();

            if (!validateEmail(emailInput.value)) {
                updateFieldValidation(emailInput, emailError, false);
                return;
            }

            const originalText = emailButton.innerHTML;
            const style = document.createElement("style");
            style.id = "spin-animation";
            if (!document.getElementById("spin-animation")) {
                style.textContent =
                    "@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }";
                document.head.appendChild(style);
            }

            emailButton.innerHTML =
                '<i class="bi bi-arrow-clockwise" style="animation: spin 1s linear infinite;"></i><span>Sending Code...</span>';
            emailButton.disabled = true;

            // Simulate sending email
            setTimeout(() => {
                emailButton.innerHTML = originalText;
                emailButton.disabled = false;

                // Store email and move to next step
                userEmailDisplay.textContent = emailInput.value;
                showStep(2);

                // Show success message
                const successMessage = document.createElement("div");
                successMessage.className = "validation-success";
                successMessage.innerHTML = `
                        <i class="bi bi-check-circle-fill"></i>
                        <div>
                            <strong>Code sent successfully!</strong>
                            <div>Please check your email for the verification code.</div>
                        </div>
                    `;
                codeStep.insertBefore(successMessage, codeStep.firstChild);

                setTimeout(() => {
                    successMessage.remove();
                }, 5000);
            }, 2000);
        });

        // ========== STEP 2: VERIFICATION CODE ==========
        const codeForm = document.getElementById("codeForm");
        const codeInput = document.getElementById("verificationCode");
        const codeError = document.getElementById("codeError");
        const codeButton = document.getElementById("codeButton");
        const resendCodeLink = document.getElementById("resendCode");
        const backToEmailLink = document.getElementById("backToEmail");

        // Only allow numbers in code input
        codeInput.addEventListener("input", function () {
            this.value = this.value.replace(/[^0-9]/g, "");
            if (this.classList.contains("error")) {
                this.classList.remove("error");
                codeError.style.display = "none";
            }
        });

        codeInput.addEventListener("blur", function () {
            if (this.value.trim() === "") {
                this.classList.remove("error");
                codeError.style.display = "none";
                return;
            }
            const isValid = validateCode(this.value);
            updateFieldValidation(this, codeError, isValid);
        });

        codeForm.addEventListener("submit", function (e) {
            e.preventDefault();

            if (!validateCode(codeInput.value)) {
                updateFieldValidation(codeInput, codeError, false);
                return;
            }

            const originalText = codeButton.innerHTML;

            codeButton.innerHTML =
                '<i class="bi bi-arrow-clockwise" style="animation: spin 1s linear infinite;"></i><span>Verifying...</span>';
            codeButton.disabled = true;

            // Simulate verification
            setTimeout(() => {
                codeButton.innerHTML = originalText;
                codeButton.disabled = false;

                // Move to password reset step
                showStep(3);
            }, 1500);
        });

        // Resend code
        resendCodeLink.addEventListener("click", function (e) {
            e.preventDefault();

            const originalText = this.textContent;
            this.textContent = "Sending...";
            this.style.pointerEvents = "none";

            setTimeout(() => {
                this.textContent = "Code sent!";

                setTimeout(() => {
                    this.textContent = originalText;
                    this.style.pointerEvents = "auto";
                }, 2000);
            }, 1500);
        });

        // Back to email
        backToEmailLink.addEventListener("click", function (e) {
            e.preventDefault();
            showStep(1);
            codeInput.value = "";
            codeInput.classList.remove("error");
            codeError.style.display = "none";
        });

        // ========== STEP 3: RESET PASSWORD ==========
        const resetForm = document.getElementById("resetForm");
        const newPasswordInput = document.getElementById("newPassword");
        const confirmNewPasswordInput = document.getElementById("confirmNewPassword");
        const newPasswordError = document.getElementById("newPasswordError");
        const confirmNewPasswordError = document.getElementById("confirmNewPasswordError");
        const resetButton = document.getElementById("resetButton");

        newPasswordInput.addEventListener("blur", function () {
            if (this.value.trim() === "") {
                this.classList.remove("error");
                newPasswordError.style.display = "none";
                return;
            }
            const isValid = validatePassword(this.value);
            updateFieldValidation(this, newPasswordError, isValid);
        });

        newPasswordInput.addEventListener("input", function () {
            if (this.classList.contains("error")) {
                this.classList.remove("error");
                newPasswordError.style.display = "none";
            }
        });

        confirmNewPasswordInput.addEventListener("blur", function () {
            if (this.value.trim() === "") {
                this.classList.remove("error");
                confirmNewPasswordError.style.display = "none";
                return;
            }
            const isValid = this.value === newPasswordInput.value;
            updateFieldValidation(this, confirmNewPasswordError, isValid);
        });

        confirmNewPasswordInput.addEventListener("input", function () {
            if (this.classList.contains("error")) {
                this.classList.remove("error");
                confirmNewPasswordError.style.display = "none";
            }
        });

        resetForm.addEventListener("submit", function (e) {
            e.preventDefault();

            let isValid = true;

            if (!validatePassword(newPasswordInput.value)) {
                updateFieldValidation(newPasswordInput, newPasswordError, false);
                isValid = false;
            }

            if (confirmNewPasswordInput.value !== newPasswordInput.value) {
                updateFieldValidation(confirmNewPasswordInput, confirmNewPasswordError, false);
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            const originalText = resetButton.innerHTML;

            resetButton.innerHTML =
                '<i class="bi bi-arrow-clockwise" style="animation: spin 1s linear infinite;"></i><span>Resetting...</span>';
            resetButton.disabled = true;

            // Simulate password reset
            setTimeout(() => {
                // Show success message
                const successMessage = document.createElement("div");
                successMessage.className = "validation-success";
                successMessage.innerHTML = `
                        <i class="bi bi-check-circle-fill"></i>
                        <div>
                            <strong>Password reset successful!</strong>
                            <div>You can now sign in with your new password.</div>
                        </div>
                    `;

                resetStep.insertBefore(successMessage, resetForm);

                // Hide form and show redirect message
                resetForm.style.display = "none";

                // Add redirect button
                const redirectButton = document.createElement("button");
                redirectButton.className = "btn-submit";
                redirectButton.innerHTML = '<i class="bi bi-box-arrow-in-right"></i><span>Go to Sign In</span>';
                redirectButton.style.marginTop = "1rem";
                redirectButton.addEventListener("click", function () {
                    // Redirect to login page
                    window.location.href = "#login";
                });

                resetStep.appendChild(redirectButton);

                resetButton.innerHTML = originalText;
                resetButton.disabled = false;
            }, 2000);
        });

        // ========== 3D CARD TILT EFFECT (Desktop only) ==========
        const card = document.querySelector(".forgot-password-card");
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