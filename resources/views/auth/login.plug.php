@extends('layouts.auth')

@section('title', 'Nattination Blog - Sign Up')

@section('content')

<!-- ========== LOGIN CONTAINER ========== -->
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <a href="{{ url('/') }}" class="login-logo">Natti<span>Nation</span></a>
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Sign in to continue to your account</p>
        </div>

        <form id="loginForm">
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
                <label class="form-label" for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" class="form-control has-password-toggle" id="password"
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
                    <input class="form-check-input" type="checkbox" id="remember">
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
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');

        emailInput.addEventListener('blur', function () {
            if (this.value.trim() === '') {
                this.classList.remove('error');
                emailError.style.display = 'none';
                return;
            }

            const isValid = validateEmail(this.value);
            if (!isValid) {
                this.classList.add('error');
                emailError.style.display = 'flex';
            } else {
                this.classList.remove('error');
                emailError.style.display = 'none';
            }
        });

        emailInput.addEventListener('input', function () {
            if (this.classList.contains('error')) {
                this.classList.remove('error');
                emailError.style.display = 'none';
            }
        });

        passwordInput.addEventListener('input', function () {
            if (this.classList.contains('error')) {
                this.classList.remove('error');
                passwordError.style.display = 'none';
            }
        });

        // ========== FORM SUBMISSION ==========
        const loginForm = document.getElementById('loginForm');
        const submitButton = document.getElementById('submitButton');

        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();

            let isValid = true;

            // Validate email
            if (!validateEmail(emailInput.value)) {
                emailInput.classList.add('error');
                emailError.style.display = 'flex';
                isValid = false;
            } else {
                emailInput.classList.remove('error');
                emailError.style.display = 'none';
            }

            // Validate password
            if (passwordInput.value.trim() === '') {
                passwordInput.classList.add('error');
                passwordError.style.display = 'flex';
                isValid = false;
            } else {
                passwordInput.classList.remove('error');
                passwordError.style.display = 'none';
            }

            if (!isValid) {
                return;
            }

            // Simulate login success
            const originalText = submitButton.innerHTML;

            submitButton.innerHTML = '<i class="bi bi-arrow-clockwise" style="animation: spin 1s linear infinite;"></i><span>Signing In...</span>';
            submitButton.disabled = true;

            // Add spin animation
            const style = document.createElement('style');
            style.textContent = '@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }';
            document.head.appendChild(style);

            setTimeout(() => {
                // Show success message
                const successMessage = document.createElement('div');
                successMessage.className = 'validation-success';
                successMessage.innerHTML = `
                        <i class="bi bi-check-circle-fill"></i>
                        <div>
                            <strong>Login successful!</strong>
                            <div>Redirecting to your dashboard...</div>
                        </div>
                    `;

                loginForm.parentNode.insertBefore(successMessage, loginForm);

                // Reset form after delay
                setTimeout(() => {
                    loginForm.reset();
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                    successMessage.remove();

                    emailInput.classList.remove('error');
                    passwordInput.classList.remove('error');
                    emailError.style.display = 'none';
                    passwordError.style.display = 'none';

                    passwordInput.type = 'password';
                    passwordToggle.querySelector('i').className = 'bi bi-eye';
                }, 3000);
            }, 2000);
        });

        // ========== 3D CARD TILT EFFECT (Desktop only) ==========
        
    });
</script>
@endpush