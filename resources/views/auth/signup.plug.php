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

        <div id="alertContainer"></div>

        <form action="{{ url('/signup') }}" method="post" id="registrationForm">
            @csrf
            <div class="form-group">
                <label class="form-label" for="fullName">Full Name</label>
                <div class="input-wrapper">
                    <input type="text" class="form-control has-icon" name="name" id="fullName"
                        placeholder="Enter your full name" required autocomplete="given-name">
                    <i class="bi bi-person input-icon"></i>
                </div>
                <div class="error-message" id="fullNameError" style="display: none;">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Full name must be at least 2 characters</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <div class="input-wrapper">
                    <input type="email" class="form-control has-icon" name="email" id="email"
                        placeholder="Enter your email" required autocomplete="email">
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
                    <input type="text" class="form-control has-icon" name="username" id="username"
                        placeholder="Choose a username" required autocomplete="username">
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
                    <input type="password" class="form-control has-password-toggle" name="password" id="password"
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
                    <input type="password" class="form-control has-password-toggle" name="password_confirmation"
                        id="confirmPassword" placeholder="Repeat your password" required autocomplete="new-password">
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
                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                <label class="form-check-label" for="terms">
                    I agree to the <a href="{{ url('terms-and-conditions') }}" tabindex="0">Terms of Service</a> and <a
                        href="{{ url('privacy-policy') }}" tabindex="0">Privacy
                        Policy</a>
                </label>
            </div>
            <div class="error-message" id="termsError" style="display: none; margin-top: -1rem; margin-bottom: 1rem;">
                <i class="bi bi-exclamation-circle"></i>
                <span>You must accept the terms and conditions</span>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="newsletter" id="newsletter">
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
    document.addEventListener('DOMContentLoaded', function () {
        // ========== PASSWORD TOGGLE ==========
        const passwordToggle = document.getElementById('passwordToggle');
        const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');

        function togglePasswordVisibility(input, toggle) {
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            const icon = toggle.querySelector('i');
            icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
        }

        passwordToggle.addEventListener('click', () => {
            togglePasswordVisibility(passwordInput, passwordToggle);
        });

        confirmPasswordToggle.addEventListener('click', () => {
            togglePasswordVisibility(confirmPasswordInput, confirmPasswordToggle);
        });

        // ========== VALIDATION FUNCTIONS ==========
        function validateName(name) {
            const trimmedName = name.trim();
            return trimmedName.length >= 2 && trimmedName.length <= 50 && /^[a-zA-Z\s\-'\.]+$/.test(trimmedName);
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
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            return passwordRegex.test(password);
        }

        // ========== PASSWORD STRENGTH ==========
        const passwordStrength = document.getElementById('passwordStrength');
        const passwordText = document.getElementById('passwordText');

        passwordInput.addEventListener('input', function () {
            const password = this.value;
            let strength = 0;
            let text = 'Password strength';

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;

            passwordStrength.className = 'strength-meter';
            if (strength === 0) {
                text = 'Very weak';
            } else if (strength === 1) {
                passwordStrength.classList.add('strength-weak');
                text = 'Weak';
            } else if (strength === 2) {
                passwordStrength.classList.add('strength-medium');
                text = 'Medium';
            } else if (strength === 3) {
                passwordStrength.classList.add('strength-medium');
                text = 'Good';
            } else {
                passwordStrength.classList.add('strength-strong');
                text = 'Strong';
            }

            passwordText.textContent = text;
        });

        // ========== REAL-TIME VALIDATION ==========
        const inputs = {
            fullName: { element: document.getElementById('fullName'), error: document.getElementById('fullNameError') },
            email: { element: document.getElementById('email'), error: document.getElementById('emailError') },
            username: { element: document.getElementById('username'), error: document.getElementById('usernameError') },
            password: { element: passwordInput, error: document.getElementById('passwordError') },
            confirmPassword: { element: confirmPasswordInput, error: document.getElementById('confirmPasswordError') },
            terms: { element: document.getElementById('terms'), error: document.getElementById('termsError') }
        };

        inputs.fullName.element.addEventListener('blur', function () {
            const isValid = validateName(this.value);
            updateFieldValidation(this, inputs.fullName.error, isValid);
        });

        inputs.email.element.addEventListener('blur', function () {
            const isValid = validateEmail(this.value);
            updateFieldValidation(this, inputs.email.error, isValid);
        });

        inputs.username.element.addEventListener('blur', function () {
            const isValid = validateUsername(this.value);
            updateFieldValidation(this, inputs.username.error, isValid);
        });

        inputs.password.element.addEventListener('blur', function () {
            const isValid = validatePassword(this.value);
            updateFieldValidation(this, inputs.password.error, isValid);
        });

        inputs.confirmPassword.element.addEventListener('blur', function () {
            const isValid = this.value === inputs.password.element.value && this.value.length > 0;
            updateFieldValidation(this, inputs.confirmPassword.error, isValid);
        });

        inputs.terms.element.addEventListener('change', function () {
            const isValid = this.checked;
            updateFieldValidation(this, inputs.terms.error, isValid);
        });

        Object.values(inputs).forEach(input => {
            if (input.element.type !== 'checkbox') {
                input.element.addEventListener('input', function () {
                    if (this.classList.contains('error')) {
                        this.classList.remove('error');
                        input.error.style.display = 'none';
                    }
                });
            }
        });

        function updateFieldValidation(field, errorElement, isValid) {
            if (field.type === 'checkbox') {
                if (isValid) {
                    field.classList.remove('error');
                    errorElement.style.display = 'none';
                } else {
                    field.classList.add('error');
                    errorElement.style.display = 'flex';
                }
                return;
            }

            if (field.value.trim() === '') {
                field.classList.remove('error', 'success');
                errorElement.style.display = 'none';
                return;
            }

            if (isValid) {
                field.classList.remove('error');
                field.classList.add('success');
                errorElement.style.display = 'none';
            } else {
                field.classList.remove('success');
                field.classList.add('error');
                errorElement.style.display = 'flex';
            }
        }

        // ========== REAL-TIME AVAILABILITY CHECKS ==========
        let usernameCheckTimeout;
        let emailCheckTimeout;

        inputs.username.element.addEventListener('input', function () {
            clearTimeout(usernameCheckTimeout);
            const username = this.value.trim();

            if (username.length >= 3 && validateUsername(username)) {
                usernameCheckTimeout = setTimeout(() => {
                    checkUsernameAvailability(username);
                }, 500);
            }
        });

        inputs.email.element.addEventListener('input', function () {
            clearTimeout(emailCheckTimeout);
            const email = this.value.trim();

            if (email.length > 5 && validateEmail(email)) {
                emailCheckTimeout = setTimeout(() => {
                    checkEmailAvailability(email);
                }, 500);
            }
        });

        async function checkUsernameAvailability(username) {
            try {
                const response = await fetch('/auth/check-username', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ username: username })
                });

                const data = await response.json();

                if (!data.available) {
                    showFieldError(inputs.username.element, inputs.username.error, 'Username is already taken');
                } else if (data.valid === false) {
                    showFieldError(inputs.username.element, inputs.username.error, 'Username format is invalid');
                } else {
                    hideFieldError(inputs.username.element, inputs.username.error);
                }
            } catch (error) {
                console.error('Username check failed:', error);
            }
        }

        async function checkEmailAvailability(email) {
            try {
                const response = await fetch('/auth/check-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ email: email })
                });

                const data = await response.json();

                if (!data.available) {
                    showFieldError(inputs.email.element, inputs.email.error, 'Email is already registered');
                } else {
                    hideFieldError(inputs.email.element, inputs.email.error);
                }
            } catch (error) {
                console.error('Email check failed:', error);
            }
        }

        function showFieldError(field, errorElement, message) {
            field.classList.remove('success');
            field.classList.add('error');
            errorElement.querySelector('span').textContent = message;
            errorElement.style.display = 'flex';
        }

        function hideFieldError(field, errorElement) {
            if (validateField(field)) {
                field.classList.remove('error');
                field.classList.add('success');
                errorElement.style.display = 'none';
            }
        }

        function validateField(field) {
            const value = field.value.trim();

            switch (field.id) {
                case 'fullName':
                    return validateName(value);
                case 'email':
                    return validateEmail(value);
                case 'username':
                    return validateUsername(value);
                case 'password':
                    return validatePassword(value);
                default:
                    return true;
            }
        }

        // ========== FORM SUBMISSION WITH AJAX ==========
        const registrationForm = document.getElementById('registrationForm');
        const submitButton = document.getElementById('submitButton');

        registrationForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            let isValid = true;

            // Validate all fields before submission
            if (!validateName(inputs.fullName.element.value)) {
                updateFieldValidation(inputs.fullName.element, inputs.fullName.error, false);
                isValid = false;
            }

            if (!validateEmail(inputs.email.element.value)) {
                updateFieldValidation(inputs.email.element, inputs.email.error, false);
                isValid = false;
            }

            if (!validateUsername(inputs.username.element.value)) {
                updateFieldValidation(inputs.username.element, inputs.username.error, false);
                isValid = false;
            }

            if (!validatePassword(inputs.password.element.value)) {
                updateFieldValidation(inputs.password.element, inputs.password.error, false);
                isValid = false;
            }

            if (inputs.confirmPassword.element.value !== inputs.password.element.value) {
                updateFieldValidation(inputs.confirmPassword.element, inputs.confirmPassword.error, false);
                isValid = false;
            }

            if (!inputs.terms.element.checked) {
                updateFieldValidation(inputs.terms.element, inputs.terms.error, false);
                isValid = false;
            }

            if (!isValid) {
                const firstError = document.querySelector('.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                showAlert('error', 'Please fix the errors in the form before submitting.');
                return false;
            }

            // Update button state
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="bi bi-hourglass-split"></i><span>Creating Account...</span>';
            submitButton.disabled = true;

            try {
                const formData = new FormData(registrationForm);
                const data = Object.fromEntries(formData.entries());

                const response = await fetch(registrationForm.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('success', result.message);
                    if (result.redirect) {
                        setTimeout(() => {
                            window.location.href = result.redirect;
                        }, 1500);
                    }
                } else {
                    showAlert('error', result.message);

                    // Handle specific field errors from server
                    if (result.errors) {
                        Object.keys(result.errors).forEach(field => {
                            const fieldName = field.replace('_', '');
                            const input = inputs[fieldName];
                            if (input) {
                                showFieldError(input.element, input.error, result.errors[field][0]);
                            }
                        });
                    }

                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }

            } catch (error) {
                console.error('Registration error:', error);
                showAlert('error', 'An error occurred. Please try again.');
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        });

        // ========== ALERT SYSTEM ==========
        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-error';

            const alertHTML = `
            <div class="alert ${alertClass}">
                <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;

            alertContainer.innerHTML = alertHTML;

            // Auto-remove after 5 seconds for success, 8 seconds for errors
            const removeTime = type === 'success' ? 5000 : 8000;
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, removeTime);
        }

        // ========== ENHANCED PASSWORD CONFIRMATION VALIDATION ==========
        inputs.password.element.addEventListener('input', function () {
            if (inputs.confirmPassword.element.value) {
                const isValid = inputs.confirmPassword.element.value === this.value;
                updateFieldValidation(inputs.confirmPassword.element, inputs.confirmPassword.error, isValid);
            }
        });

        // ========== ENTER KEY SUPPORT ==========
        registrationForm.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const submitEvent = new Event('submit', { cancelable: true });
                this.dispatchEvent(submitEvent);
            }
        });

        // ========== ACCESSIBILITY IMPROVEMENTS ==========
        function setAriaDescribedBy() {
            Object.values(inputs).forEach(input => {
                if (input.element.type !== 'checkbox' && input.error) {
                    const errorId = input.error.id;
                    if (!input.element.getAttribute('aria-describedby')) {
                        input.element.setAttribute('aria-describedby', errorId);
                    }
                }
            });
        }

        setAriaDescribedBy();

        // ========== FORM RESET HANDLING ==========
        function resetFormState() {
            Object.values(inputs).forEach(input => {
                if (input.element.type !== 'checkbox') {
                    input.element.classList.remove('error', 'success');
                    input.error.style.display = 'none';
                } else {
                    input.element.classList.remove('error');
                    input.error.style.display = 'none';
                }
            });

            passwordStrength.className = 'strength-meter';
            passwordText.textContent = 'Password strength';
            document.getElementById('alertContainer').innerHTML = '';
        }

        // Expose reset function for potential use
        window.resetRegistrationForm = resetFormState;

        // ========== PERFORMANCE OPTIMIZATIONS ==========
        let isSubmitting = false;

        registrationForm.addEventListener('submit', function (e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
        });

        // Clean up timeouts when leaving page
        window.addEventListener('beforeunload', function () {
            clearTimeout(usernameCheckTimeout);
            clearTimeout(emailCheckTimeout);
        });
    });
</script>
@endpush