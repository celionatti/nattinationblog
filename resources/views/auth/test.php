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

        <form action="" method="post" id="registrationForm">
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
        // ========== CONFIGURATION ==========
        const API_URL = '/api/register';

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
            return trimmedName.length >= 5 && /^[a-zA-Z\s\-'\.]+$/.test(trimmedName);
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

        // ========== ALERT FUNCTIONS ==========
        function showAlert(message, type = 'error') {
            const alertContainer = document.getElementById('alertContainer');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
            const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill';

            alertContainer.innerHTML = `
                    <div class="alert-message ${alertClass}">
                        <i class="bi ${icon}"></i>
                        <div>${message}</div>
                    </div>
                `;

            // Scroll to alert
            alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            // Auto-remove after 5 seconds
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        function clearAlert() {
            const alertContainer = document.getElementById('alertContainer');
            alertContainer.innerHTML = '';
        }

        // ========== FORM SUBMISSION WITH AJAX ==========
        const registrationForm = document.getElementById('registrationForm');
        const submitButton = document.getElementById('submitButton');

        registrationForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearAlert();

            let isValid = true;

            // Validate all fields
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
                return;
            }

            // Prepare form data
            const formData = {
                full_name: inputs.fullName.element.value.trim(),
                email: inputs.email.element.value.trim(),
                username: inputs.username.element.value.trim(),
                password: inputs.password.element.value,
                password_confirmation: inputs.confirmPassword.element.value,
                terms: inputs.terms.element.checked,
                newsletter: document.getElementById('newsletter').checked
            };

            // Update button state
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="bi bi-hourglass-split"></i><span>Creating Account...</span>';
            submitButton.disabled = true;

            // Create XMLHttpRequest
            const xhr = new XMLHttpRequest();
            xhr.open('POST', API_URL, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('Accept', 'application/json');

            // Get CSRF token if available (for Laravel)
            const csrfToken = document.querySelector('meta[name="csrf_token"]');
            if (csrfToken) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken.getAttribute('content'));
            }

            xhr.onload = function () {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;

                if (xhr.status >= 200 && xhr.status < 300) {
                    // Success
                    const response = JSON.parse(xhr.responseText);

                    showAlert(response.message || 'Account created successfully! Redirecting...', 'success');

                    // Reset form
                    setTimeout(() => {
                        registrationForm.reset();

                        // Reset all field styles
                        Object.values(inputs).forEach(input => {
                            if (input.element) {
                                input.element.classList.remove('error', 'success');
                                if (input.error) input.error.style.display = 'none';
                            }
                        });

                        passwordStrength.className = 'strength-meter';
                        passwordText.textContent = 'Password strength';

                        // Reset password visibility
                        passwordInput.type = 'password';
                        confirmPasswordInput.type = 'password';
                        passwordToggle.querySelector('i').className = 'bi bi-eye';
                        confirmPasswordToggle.querySelector('i').className = 'bi bi-eye';

                        // Redirect if URL provided
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    }, 2000);

                } else if (xhr.status === 422) {
                    // Validation errors
                    const response = JSON.parse(xhr.responseText);

                    if (response.errors) {
                        // Handle Laravel validation errors
                        let errorMessages = [];

                        for (let field in response.errors) {
                            errorMessages.push(response.errors[field][0]);

                            // Highlight specific fields
                            if (field === 'first_name' && inputs.firstName.element) {
                                updateFieldValidation(inputs.firstName.element, inputs.firstName.error, false);
                            }
                            if (field === 'last_name' && inputs.lastName.element) {
                                updateFieldValidation(inputs.lastName.element, inputs.lastName.error, false);
                            }
                            if (field === 'email' && inputs.email.element) {
                                updateFieldValidation(inputs.email.element, inputs.email.error, false);
                            }
                            if (field === 'username' && inputs.username.element) {
                                updateFieldValidation(inputs.username.element, inputs.username.error, false);
                            }
                            if (field === 'password' && inputs.password.element) {
                                updateFieldValidation(inputs.password.element, inputs.password.error, false);
                            }
                        }

                        showAlert(errorMessages.join('<br>'), 'error');
                    } else {
                        showAlert(response.message || 'Validation failed. Please check your inputs.', 'error');
                    }

                } else {
                    // Other errors
                    const response = xhr.responseText ? JSON.parse(xhr.responseText) : {};
                    showAlert(response.message || 'An error occurred. Please try again.', 'error');
                }
            };

            xhr.onerror = function () {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                showAlert('Network error. Please check your connection and try again.', 'error');
            };

            xhr.ontimeout = function () {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                showAlert('Request timeout. Please try again.', 'error');
            };

            // Set timeout (30 seconds)
            xhr.timeout = 30000;

            // Send request
            xhr.send(JSON.stringify(formData));
        });
    });
</script>
@endpush