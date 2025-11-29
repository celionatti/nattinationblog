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
use App\Controllers\AdminEventController;
use App\Controllers\AdminArticleController;
use App\Controllers\AdminSettingController;
use App\Controllers\AdminCategoryController;
use App\Controllers\AdminEventTypeController;

// Route::get('/home', [HomeController::class, 'index']);
// Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Articles and HomePage Routes(Open Routes)
Route::group(['prefix' => '/', 'middleware' => []], function () {
    Route::get('', [HomeController::class, 'index']);
    Route::get('home', [HomeController::class, 'index']);

    // Article Routes
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/{slug}/{id}', [ArticleController::class, 'article'])
        ->where(['id' => '[0-9]+', 'slug' => '[a-z0-9-]+'])
        ->name('articles.show');
});

Route::group(['prefix' => '/', 'middleware' => ['guest']], function () {
    // Authentication Routes
    Route::get('/signup', [AuthController::class, 'showSignUpForm'])->name('signup');
    Route::post('/signup', [AuthController::class, 'createAccount'])->name('create-account');
    Route::post('/auth/check-username', [AuthController::class, 'checkUsername'])->name('check-username');
    Route::post('/auth/check-email', [AuthController::class, 'checkEmail'])->name('check-email');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [AuthController::class, 'forgotPasswordForm'])->name('forgot-password');
});

// Move this outside the guest group
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware(['auth']); // Add auth middleware

// Admin Routes
Route::group(['prefix' => '/admin', 'middleware' => ['admin']], function () {
    // Admin specific routes can be defined here
    Route::get('/', [AdminController::class, 'adminDashboard'])->name('admin');

    Route::get('/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');

    /*
    * -------------------------------------------------------------------------------
    * Admin Categories
    * -------------------------------------------------------------------------------
    */
    Route::get('/categories', [AdminCategoryController::class, 'manage'])->name('admin.categories.index');

    Route::get('/categories/create', [AdminCategoryController::class, 'newCategory'])->name('admin.categories.show');

    Route::post('/categories/create', [AdminCategoryController::class, 'store'])->name('admin.categories.store');

    Route::get('/categories/edit/{id}', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit');

    Route::put('/categories/update/{id}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');

    Route::post('/categories/bulk-action', [AdminCategoryController::class, 'bulkAction'])->name('admin.categories.bulk');

    Route::delete('/categories/delete/{id}', [AdminCategoryController::class, 'delete'])->name('admin.categories.delete');

    /*
    * -------------------------------------------------------------------------------
    * Admin Articles
    * -------------------------------------------------------------------------------
    */
    Route::get('/articles', [AdminArticleController::class, 'manage'])->name('admin.articles.index');

    Route::post('/articles/bulk-action', [AdminArticleController::class, 'bulkAction'])->name('admin.articles.bulk');

    Route::delete('/articles/delete/{id}', [AdminArticleController::class, 'destroy'])->name('admin.articles.destroy');

    Route::patch('/articles/toggle-status/{id}', [AdminArticleController::class, 'toggleStatus'])->name('admin.articles.toggle-status');

    Route::get('/articles/new-article', [AdminArticleController::class, 'newArticle'])->name('admin.articles.create');

    Route::post('/articles/create', [AdminArticleController::class, 'store'])->name('admin.articles.store');

    Route::get('/articles/edit/{id}', [AdminArticleController::class, 'editArticle'])->name('admin.articles.edit');

    Route::put('/articles/edit/{id}', [AdminArticleController::class, 'update'])->name('admin.articles.update');

    Route::get('/articles/show/{id}', [AdminArticleController::class, 'showArticle'])->name('admin.articles.show');

    /*
    * -------------------------------------------------------------------------------
    * Admin Event Types
    * -------------------------------------------------------------------------------
    */
    Route::get('/event-types', [AdminEventTypeController::class, 'manage'])->name('admin.event-type.index');

    Route::get('/event-types/create', [AdminEventTypeController::class, 'newType'])->name('admin.event-type.show');

    Route::post('/event-types/create', [AdminEventTypeController::class, 'store'])->name('admin.event-type.store');

    Route::get('/event-types/edit/{id}', [AdminEventTypeController::class, 'edit'])->name('admin.event-type.edit');

    Route::put('/event-types/update/{id}', [AdminEventTypeController::class, 'update'])->name('admin.event-type.update');

    Route::delete('/event-types/delete/{id}', [AdminEventTypeController::class, 'delete'])->name('admin.event-type.delete');


    /*
    * -------------------------------------------------------------------------------
    * Admin Events
    * -------------------------------------------------------------------------------
    */
    Route::get('/events', [AdminEventController::class, 'manage'])->name('admin.events.index');

    Route::get('/events/create', [AdminEventController::class, 'create'])->name('admin.events.create');

    Route::post('/events/create', [AdminEventController::class, 'store'])->name('admin.events.store');

    Route::get('/events/detail/{id}/{slug}', [AdminEventController::class, 'show'])->name('admin.events.show');

    Route::get('/events/edit/{id}', [AdminEventController::class, 'edit'])->name('admin.events.edit');

    Route::put('/events/edit/{id}', [AdminEventController::class, 'update'])->name('admin.events.update');

    Route::delete('/events/delete/{id}', [AdminEventController::class, 'destroy'])->name('admin.events.destroy');

    /*
    * -------------------------------------------------------------------------------
    * Admin Settings
    * -------------------------------------------------------------------------------
    */
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
