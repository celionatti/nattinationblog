<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Routes File - Web Routes
|--------------------------------------------------------------------------
|
| This file is where you can define all of the routes that are handled
| by your application. Just tell the router the URIs it should respond
| to and give it the controller to call when that URI is requested.
| Also - 
| Using the Route facade for clean, static route definitions.
*/

use App\Controllers\AdminArticleController;
use Plugs\Facades\Route;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Controllers\ArticleController;
use App\Controllers\AdminSettingController;


// Route::get('/home', [HomeController::class, 'index']);
// Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Articles and HomePage Routes(Open Routes)
Route::group(['prefix' => '/', 'middleware' => []], function () {
    Route::get('', [HomeController::class, 'index']);
    Route::get('home', [HomeController::class, 'index']);

    // Article Routes
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles');

    Route::get('/articles/{slug}/{id}', [ArticleController::class, 'article'])->where(['id' => '[0-9]+', 'slug' => '[a-z0-9-]+'])->name('article');
});

Route::group(['prefix' => '/', 'middleware' => ['guest']], function () {
    // Authentication Routes
    Route::get('/signup', [AuthController::class, 'showSignUpForm'])->name('signup');
    Route::post('/signup', [AuthController::class, 'createAccount'])->name('create-account');
    Route::post('/auth/check-username', [AuthController::class, 'checkUsername'])->name('check-username');
    Route::post('/auth/check-email', [AuthController::class, 'checkEmail'])->name('check-email');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/forgot-password', [AuthController::class, 'forgotPasswordForm'])->name('forgot-password');
});

// Admin Routes
Route::group(['prefix' => '/admin', 'middleware' => ['admin']], function () {
    // Admin specific routes can be defined here
    Route::get('/', [AdminController::class, 'adminDashboard'])->name('admin');

    Route::get('/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');

    // Admin Articles
    Route::get('/articles', [AdminArticleController::class, 'manage']);
    Route::get('/articles/new-article', [AdminArticleController::class, 'newArticle']);

    Route::post('/articles/create', [AdminArticleController::class, 'createArticle']);
    Route::post('/articles/save-draft', [AdminArticleController::class, 'saveDraft']);
    Route::post('/articles/upload-image', [AdminArticleController::class, 'uploadFeaturedImage']);

    // Admin Settings Route
    Route::get('/settings', [AdminSettingController::class, 'manage'])->name('admin.settings');
});

// Additional routes can be added below as needed
// For example, API routes, user profile routes, etc.

// API Routes
Route::group(['prefix' => '/api'], function () {
    // Settings endpoints
    Route::get('/settings', [AdminSettingController::class, 'getSettings']);
    Route::post('/settings', [AdminSettingController::class, 'saveSettings']);
    Route::get('/settings/all', [AdminSettingController::class, 'getAllSettings']);
    Route::post('/settings/reset', [AdminSettingController::class, 'resetToDefaults']);
    Route::get('/settings/{key}', [AdminSettingController::class, 'getSettingByKey']);
    Route::put('/settings/{key}', [AdminSettingController::class, 'updateSetting']);
});