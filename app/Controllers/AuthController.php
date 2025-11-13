<?php

declare(strict_types=1);

namespace App\Controllers;

use Plugs\Base\Controller\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * ArticleController
 * 
 * @package App\Controllers
 */

class AuthController extends Controller
{
    /**
     * Show Sign up Form
     */
    public function showSignUpForm(): ResponseInterface
    {
        return $this->view('pages.auth.signup');
    }

    /**
     * Show Login Form
     */
    public function showLoginForm(): ResponseInterface
    {
        return $this->view('pages.auth.login');
    }

    /**
     * Handle Login
     */
    public function login(ServerRequestInterface $request): ResponseInterface
    {
        // Authentication logic here

        return $this->redirect('home');
    }

    /**
     * Handle Logout
     */
    public function logout(): ResponseInterface
    {
        // Logout logic here

        return $this->redirect('login');
    }

    /**
     * Show Forgot Password Form
     */
    public function forgotPasswordForm(): ResponseInterface
    {
        return $this->view('pages.auth.forgot-password');
    }
}