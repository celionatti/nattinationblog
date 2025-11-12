@extends('layouts.auth')

@section('title', 'Nattination Blog - Sign Up')

@section('content')

<!-- ========== REGISTRATION CONTAINER ========== -->
<div class="registration-container">
    <div class="registration-card">
        <div class="registration-header">
            <a href="/" class="registration-logo">Natti<span>Nation</span></a>
            <h1 class="registration-title">Create Account</h1>
            <p class="registration-subtitle">Join our community of readers and writers</p>
        </div>

        <form id="registrationForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="firstName">First Name</label>
                        <input type="text" class="form-control" id="firstName" placeholder="Enter your first name"
                            required>
                        <i class="bi bi-person input-icon"></i>
                        <div class="error-message" id="firstNameError" style="display: none;">
                            <i class="bi bi-exclamation-circle"></i>
                            <span>First name must be at least 2 characters</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="lastName">Last Name</label>
                        <input type="text" class="form-control" id="lastName" placeholder="Enter your last name"
                            required>
                        <i class="bi bi-person input-icon"></i>
                        <div class="error-message" id="lastNameError" style="display: none;">
                            <i class="bi bi-exclamation-circle"></i>
                            <span>Last name must be at least 2 characters</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                <i class="bi bi-envelope input-icon"></i>
                <div class="error-message" id="emailError" style="display: none;">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Please enter a valid email address</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input type="text" class="form-control" id="username" placeholder="Choose a username" required>
                <i class="bi bi-at input-icon"></i>
                <div class="error-message" id="usernameError" style="display: none;">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Username must be 3-20 characters (letters, numbers, _)</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Create a strong password"
                    required>
                <i class="bi bi-lock input-icon"></i>
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
                <input type="password" class="form-control" id="confirmPassword" placeholder="Repeat your password"
                    required>
                <i class="bi bi-lock-fill input-icon"></i>
                <div class="error-message" id="confirmPasswordError" style="display: none;">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Passwords do not match</span>
                </div>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label" for="terms">
                    I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
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
                <i class="bi bi-person-plus me-2"></i> Create Account
            </button>
        </form>

        <div class="social-registration">
            <div class="social-divider">
                <span>Or register with</span>
            </div>
            <div class="social-buttons">
                <button class="btn-social btn-google">
                    <i class="bi bi-google"></i>
                </button>
                <button class="btn-social btn-facebook">
                    <i class="bi bi-facebook"></i>
                </button>
                <button class="btn-social btn-twitter">
                    <i class="bi bi-twitter"></i>
                </button>
            </div>
        </div>

        <div class="login-link">
            Already have an account? <a href="#">Sign in here</a>
        </div>
    </div>
</div>

@endsection