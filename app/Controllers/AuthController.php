<?php

declare(strict_types=1);

namespace App\Controllers;

use Plugs\Auth\Auth;
use Plugs\Base\Controller\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * AuthController
 * 
 * @package App\Controllers
 */
class AuthController extends Controller
{
    private Auth $auth;

    public function onConstruct()
    {
        $this->auth = Auth::make();
    }

    /**
     * Show Sign up Form
     */
    public function showSignUpForm(): ResponseInterface
    {
        return $this->view('auth.signup');
    }

    /**
     * Handle Account Creation
     */
    public function createAccount(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);

            // Check if JSON decode was successful
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->json([
                    'success' => false,
                    'message' => 'Invalid JSON data'
                ], 400);
            }

            // Server-side validation
            $validation = $this->validateRegistration($data);
            if (!$validation['valid']) {
                return $this->json([
                    'success' => false,
                    'message' => 'Please fix the validation errors',
                    'errors' => $validation['errors']
                ], 422);
            }

            // Create user
            $userData = [
                'name' => trim($data['name']),
                'email' => trim($data['email']),
                'username' => trim($data['username']),
                'password' => $data['password'],
                'newsletter' => isset($data['newsletter']) && ($data['newsletter'] === true || $data['newsletter'] === 'on')
            ];

            $registered = $this->auth->register(
                $userData['email'],
                $userData['password'],
                [
                    'name' => $userData['name'],
                    'username' => $userData['username'],
                    'status' => 'active',
                    'role' => 'user',
                    'last_login_ip' => $_SERVER['REMOTE_ADDR']
                ]
            );

            if ($registered) {
                // Auto-login after registration
                $loginSuccess = $this->auth->login($userData['email'], $userData['password']);

                if (!$loginSuccess) {
                    return $this->json([
                        'success' => false,
                        'message' => 'Account created but login failed. Please try logging in manually.'
                    ], 500);
                }

                // Handle newsletter subscription if needed
                if ($userData['newsletter']) {
                    $this->subscribeToNewsletter($userData['email'], $userData['name']);
                }

                return $this->json([
                    'success' => true,
                    'message' => 'Account created successfully! Welcome to Nattination!',
                    'redirect' => url('/')
                ]);
            }

            return $this->json([
                'success' => false,
                'message' => 'Failed to create account. Please try again.'
            ], 500);

        } catch (\Exception $e) {
            $this->logError('Registration error: ' . $e->getMessage());

            return $this->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.'
            ], 500);
        }
    }

    /**
     * Validate registration data
     */
    private function validateRegistration(array $data): array
    {
        $errors = [];

        // Name validation
        $name = trim($data['name'] ?? '');
        if (empty($name)) {
            $errors['name'] = ['Full name is required'];
        } elseif (strlen($name) < 2) {
            $errors['name'] = ['Full name must be at least 2 characters'];
        } elseif (strlen($name) > 50) {
            $errors['name'] = ['Full name must not exceed 50 characters'];
        } elseif (!preg_match('/^[a-zA-Z\s\-\'\.]+$/', $name)) {
            $errors['name'] = ['Full name contains invalid characters'];
        }

        // Email validation
        $email = trim($data['email'] ?? '');
        if (empty($email)) {
            $errors['email'] = ['Email is required'];
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = ['Please enter a valid email address'];
        } else {
            // Check email availability
            if (!$this->isEmailAvailable($email)) {
                $errors['email'] = ['Email is already registered'];
            }
        }

        // Username validation (FIXED REGEX)
        $username = trim($data['username'] ?? '');
        if (empty($username)) {
            $errors['username'] = ['Username is required'];
        } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
            $errors['username'] = ['Username must be 3-20 characters (letters, numbers, underscore only)'];
        } else {
            // Check username availability
            if (!$this->isUsernameAvailable($username)) {
                $errors['username'] = ['Username is already taken'];
            }
        }

        // Password validation
        $password = $data['password'] ?? '';
        if (empty($password)) {
            $errors['password'] = ['Password is required'];
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            $errors['password'] = ['Password must be at least 8 characters with uppercase, lowercase, number, and special character'];
        }

        // Password confirmation
        $passwordConfirmation = $data['password_confirmation'] ?? '';
        if (empty($passwordConfirmation)) {
            $errors['password_confirmation'] = ['Password confirmation is required'];
        } elseif ($password !== $passwordConfirmation) {
            $errors['password_confirmation'] = ['Passwords do not match'];
        }

        // Terms agreement
        if (!isset($data['terms']) || ($data['terms'] !== true && $data['terms'] !== 'on')) {
            $errors['terms'] = ['You must accept the terms and conditions'];
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Check email availability
     */
    private function isEmailAvailable(string $email): bool
    {
        try {
            $model = $this->auth->getUserModel();
            $emailColumn = $this->auth->getConfig('email_column');

            if (!$emailColumn) {
                $this->logError('Email column not configured');
                return true; // Changed: If no email column, allow registration
            }

            $exists = $model::where($emailColumn, $email)->exists();
            $this->logError("Email check: $email - Exists: " . ($exists ? 'yes' : 'no'));

            return !$exists; // true means available
        } catch (\Exception $e) {
            $this->logError('Email availability check error: ' . $e->getMessage());
            return true; // Changed: On error, allow registration (fail open)
        }
    }

    /**
     * Check username availability
     */
    private function isUsernameAvailable(string $username): bool
    {
        try {
            $model = $this->auth->getUserModel();
            $exists = $model::where('username', $username)->exists();
            $this->logError("Username check: $username - Exists: " . ($exists ? 'yes' : 'no'));

            return !$exists; // true means available
        } catch (\Exception $e) {
            $this->logError('Username availability check error: ' . $e->getMessage());
            return true; // Changed: On error, allow registration (fail open)
        }
    }

    /**
     * Add email availability check endpoint
     */
    public function checkEmail(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->json(['available' => false, 'valid' => false]);
            }

            $email = trim($data['email'] ?? '');

            if (empty($email)) {
                return $this->json(['available' => false, 'valid' => false]);
            }

            $isValidEmail = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;

            return $this->json([
                'available' => $isValidEmail ? $this->isEmailAvailable($email) : false,
                'valid' => $isValidEmail
            ]);

        } catch (\Exception $e) {
            $this->logError('Email check error: ' . $e->getMessage());
            return $this->json(['available' => false, 'valid' => false]);
        }
    }

    /**
     * Add username availability check endpoint (FIXED)
     */
    public function checkUsername(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->json(['available' => false, 'valid' => false]);
            }

            $username = trim($data['username'] ?? '');

            if (empty($username)) {
                return $this->json(['available' => false, 'valid' => false]);
            }

            // FIXED: Proper username validation
            $isValidUsername = preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username) === 1;

            return $this->json([
                'available' => $isValidUsername ? $this->isUsernameAvailable($username) : false,
                'valid' => $isValidUsername
            ]);

        } catch (\Exception $e) {
            $this->logError('Username check error: ' . $e->getMessage());
            return $this->json(['available' => false, 'valid' => false]);
        }
    }

    /**
     * Newsletter subscription
     */
    private function subscribeToNewsletter(string $email, string $name): void
    {
        try {
            // Implement your newsletter subscription logic here
            // This could be Mailchimp, SendGrid, or your own system
            // Example: $newsletterService->subscribe($email, $name);
        } catch (\Exception $e) {
            // Log but don't fail registration
            $this->logError('Newsletter subscription failed: ' . $e->getMessage());
        }
    }

    /**
     * Log errors
     */
    private function logError(string $message): void
    {
        error_log('[AuthController] ' . $message);
    }

    /**
     * Show Login Form
     */
    public function showLoginForm(): ResponseInterface
    {
        return $this->view('auth.login');
    }

    /**
     * Handle Login
     */
    public function login(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->json([
                    'success' => false,
                    'message' => 'Invalid request data'
                ], 400);
            }

            $email = trim($data['email'] ?? '');
            $password = $data['password'] ?? '';
            $remember = isset($data['remember']) && ($data['remember'] === true || $data['remember'] === 'on');

            // Basic validation
            $errors = [];

            if (empty($email)) {
                $errors['email'] = ['Email is required'];
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = ['Please enter a valid email address'];
            }

            if (empty($password)) {
                $errors['password'] = ['Password is required'];
            }

            if (!empty($errors)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Please fix the validation errors',
                    'errors' => $errors
                ], 422);
            }

            $loggedIn = $this->auth->login($email, $password, $remember);

            if ($loggedIn) {
                return $this->json([
                    'success' => true,
                    'message' => 'Login successful! Welcome back.',
                    'redirect' => url('/')
                ]);
            }

            return $this->json([
                'success' => false,
                'message' => 'Invalid email or password. Please try again.'
            ], 401);

        } catch (\Exception $e) {
            $this->logError('Login error: ' . $e->getMessage());

            // Check if it's an email verification error
            if (strpos($e->getMessage(), 'verify your email') !== false) {
                return $this->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'needs_verification' => true
                ], 403);
            }

            return $this->json([
                'success' => false,
                'message' => 'An error occurred during login. Please try again.'
            ], 500);
        }
    }

    /**
     * Handle Logout
     */
    public function logout(): ResponseInterface
    {
        $this->auth->logout();
        return $this->redirect('login');
    }

    /**
     * Show Forgot Password Form
     */
    public function forgotPasswordForm(): ResponseInterface
    {
        return $this->view('auth.forgot-password');
    }
}