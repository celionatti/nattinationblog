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

use Plugs\Facades\Route;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Controllers\ArticleController;
use App\Controllers\AdminSettingController;


Route::get('/home', [HomeController::class, 'index']);
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::group([], function () {
    // Additional grouped routes can be defined here
});

// Authentication Routes
Route::get('/signup', [AuthController::class, 'showSignUpForm'])->name('signup');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'forgotPasswordForm'])->name('forgot-password');

// Article Routes
Route::get('/articles', [ArticleController::class, 'index'])->name('articles');

Route::get('/articles/{slug}/{id}', [ArticleController::class, 'article'])->where(['id' => '[0-9]+', 'slug' => '[a-z0-9-]+'])->name('article');

// Admin Routes
Route::group(['prefix' => '/admin'], function () {
    // Admin specific routes can be defined here
    Route::get('/', [AdminController::class, 'adminDashboard'])->name('admin');

    Route::get('/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');

    // Admin Settings Route
    Route::get('/settings', [AdminSettingController::class, 'manage'])->name('admin.settings');
});

// Additional routes can be added below as needed
// For example, API routes, user profile routes, etc.

// API Routes
Route::group(['prefix' => '/api/settings'], function () {
    Route::get('/', [AdminSettingController::class, 'getSettings']);

    Route::post('/', [AdminSettingController::class, 'saveSettings']);

    Route::get('/all', [AdminSettingController::class, 'getAllSettings']);
    Route::post('/reset', [AdminSettingController::class, 'resetToDefaults']);

    Route::get('/key/{key}', [AdminSettingController::class, 'getSettingByKey']);
    Route::put('/key/{key}', [AdminSettingController::class, 'updateSettingByKey']);
});