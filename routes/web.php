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
use App\Controllers\HomeController;
use App\Controllers\ArticleController;


Route::get('/home', [HomeController::class, 'index']);
Route::get('/', [HomeController::class, 'index'])->name('home');

// Article Routes
Route::get('/articles', [ArticleController::class, 'index'])->name('articles');

Route::get('/articles/{slug}/{id}', [ArticleController::class, 'article'])->where(['id' => '[0-9]+', 'slug' => '[a-z0-9-]+'])->name('article');