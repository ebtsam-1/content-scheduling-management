<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontendPostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::group(['middleware' => 'auth:web'], function(){
    Route::get('/posts/create', [FrontendPostController::class, 'create']);
    Route::post('/posts', [PostController::class,'store'])->name('posts.store');
    Route::get('/dashboard', [FrontendPostController::class,'viewDashboard'])->name('dashboard');
    Route::get('/posts-analytics', [FrontendPostController::class,'analytics'])->name('analytics');
});


