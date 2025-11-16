@extends('layouts.auth')

@section('title', 'Nattination Blog - Sign Up')

@push('styles')
<style>
    /* Alert Styles */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        animation: slideIn 0.3s ease-out;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert i {
        font-size: 16px;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Validation states */
    .form-control.success {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .form-control.error {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
</style>
@endpush

@section('content')

<!-- ========== LOGIN CONTAINER ========== -->
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <a href="{{ url('/') }}" class="login-logo">Natti<span>Nation</span></a>
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Sign in to continue to your account</p>
        </div>

        <div id="alertContainer"></div>

        <form action="{{ url('/login') }}" method="post" id="loginForm">
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
                <label class="form-label" for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" class="form-control has-password-toggle" name="password" id="password"
                        placeholder="Enter your password" required autocomplete="current-password">
                    <button type="button" class="password-toggle" id="passwordToggle"
                        aria-label="Toggle password visibility">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div class="error-message" id="passwordError" style="display: none;">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Please enter your password</span>
                </div>
            </div>

            <div class="form-options">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>
                <a href="{{ url('forgot-password') }}" class="forgot-password" tabindex="0">Forgot password?</a>
            </div>

            <button type="submit" class="btn-login" id="submitButton">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Sign In</span>
            </button>
        </form>

        <!-- <div class="social-login">
            <div class="social-divider">
                <span>Or continue with</span>
            </div>
            <div class="social-buttons">
                <button class="btn-social btn-google" type="button" aria-label="Sign in with Google">
                    <i class="bi bi-google"></i>
                </button>
                <button class="btn-social btn-facebook" type="button" aria-label="Sign in with Facebook">
                    <i class="bi bi-facebook"></i>
                </button>
                <button class="btn-social btn-twitter" type="button" aria-label="Sign in with Twitter">
                    <i class="bi bi-twitter"></i>
                </button>
            </div>
        </div> -->

        <div class="register-link">
            Don't have an account? <a href="{{ url('signup') }}" tabindex="0">Create one here</a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ========== PASSWORD TOGGLE ==========
        const passwordToggle = document.getElementById('passwordToggle');
        const passwordInput = document.getElementById('password');

        passwordToggle.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            const icon = passwordToggle.querySelector('i');
            icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
        });

        // ========== VALIDATION FUNCTIONS ==========
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // ========== REAL-TIME VALIDATION ==========
        const inputs = {
            email: { element: document.getElementById('email'), error: document.getElementById('emailError'), errorText: document.getElementById('emailErrorText') },
            password: { element: document.getElementById('password'), error: document.getElementById('passwordError'), errorText: document.getElementById('passwordErrorText') }
        };

        inputs.email.element.addEventListener('blur', function () {
            const isValid = validateEmail(this.value);
            updateFieldValidation(this, inputs.email.error, isValid, 'Please enter a valid email address');
        });

        inputs.password.element.addEventListener('blur', function () {
            const isValid = this.value.trim().length >= 1;
            updateFieldValidation(this, inputs.password.error, isValid, 'Please enter your password');
        });

        Object.values(inputs).forEach(input => {
            input.element.addEventListener('input', function () {
                if (this.classList.contains('error')) {
                    this.classList.remove('error');
                    input.error.style.display = 'none';
                }
            });
        });

        function updateFieldValidation(field, errorElement, isValid, errorMessage) {
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
                errorElement.querySelector('span').textContent = errorMessage;
                errorElement.style.display = 'flex';
            }
        }

        // ========== FORM SUBMISSION WITH AJAX ==========
        const loginForm = document.getElementById('loginForm');
        const submitButton = document.getElementById('submitButton');

        loginForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            let isValid = true;

            // Validate all fields before submission
            if (!validateEmail(inputs.email.element.value)) {
                updateFieldValidation(inputs.email.element, inputs.email.error, false, 'Please enter a valid email address');
                isValid = false;
            }

            if (inputs.password.element.value.trim() === '') {
                updateFieldValidation(inputs.password.element, inputs.password.error, false, 'Please enter your password');
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
            submitButton.innerHTML = '<i class="bi bi-arrow-clockwise" style="animation: spin 1s linear infinite;"></i><span>Signing In...</span>';
            submitButton.disabled = true;

            // Add spin animation
            const style = document.createElement('style');
            style.textContent = '@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }';
            document.head.appendChild(style);

            try {
                const formData = new FormData(loginForm);
                const data = Object.fromEntries(formData.entries());

                const response = await fetch(loginForm.action, {
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
                            const input = inputs[field];
                            if (input) {
                                showFieldError(input.element, input.error, input.errorText, result.errors[field][0]);
                            }
                        });
                    }

                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }

            } catch (error) {
                console.error('Login error:', error);
                showAlert('error', 'An error occurred. Please try again.');
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        });

        function showFieldError(field, errorElement, errorTextElement, message) {
            field.classList.remove('success');
            field.classList.add('error');
            errorTextElement.textContent = message;
            errorElement.style.display = 'flex';
        }

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

        // ========== ENTER KEY SUPPORT ==========
        loginForm.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const submitEvent = new Event('submit', { cancelable: true });
                this.dispatchEvent(submitEvent);
            }
        });

        // ========== ACCESSIBILITY IMPROVEMENTS ==========
        function setAriaDescribedBy() {
            Object.values(inputs).forEach(input => {
                const errorId = input.error.id;
                if (!input.element.getAttribute('aria-describedby')) {
                    input.element.setAttribute('aria-describedby', errorId);
                }
            });
        }

        setAriaDescribedBy();
    });
</script>
@endpush