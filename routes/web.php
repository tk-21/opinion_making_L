<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ObjectionController;
use App\Http\Controllers\OpinionController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//ログイン、ログアウト
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


//ユーザー登録
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


//トピックス
Route::prefix('/topics')
    ->name('topics.')
    ->group(function () {
        Route::get('/', [TopicController::class, 'index'])->name('index');
        Route::get('/create', [TopicController::class, 'create'])->name('create');
        Route::post('/', [TopicController::class, 'store'])->name('store');
        Route::get('/{id}', [TopicController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [TopicController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TopicController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [TopicController::class, 'confirmDelete'])->name('confirmDelete');
        Route::delete('/{id}', [TopicController::class, 'destroy'])->name('destroy');
        Route::post('/status/{id}', [TopicController::class, 'updateStatus'])->name('updateStatus');
    });


//カテゴリー
Route::prefix('/categories')
    ->name('categories.')
    ->group(function () {
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{id}', [CategoryController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [CategoryController::class, 'confirmDelete'])->name('confirmDelete');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });


//反論
Route::prefix('/objections')
    ->name('objections.')
    ->group(function () {
        Route::post('/', [ObjectionController::class, 'store'])->name('store');
        Route::get('/{id}', [ObjectionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ObjectionController::class, 'update'])->name('update');
        Route::delete('/{id}', [ObjectionController::class, 'destroy'])->name('destroy');
    });


//意見
Route::prefix('/opinions')
    ->name('opinions.')
    ->group(function () {
        Route::get('/create', [OpinionController::class, 'create'])->name('create');
        Route::post('/', [OpinionController::class, 'store'])->name('store');
        Route::get('/{id}', [OpinionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [OpinionController::class, 'update'])->name('update');
    });


//パスワードリセット
Route::get('/request', [PasswordResetController::class, 'showRequestForm'])->name('request.showRequestForm');
Route::post('/request', [PasswordResetController::class, 'request']);
Route::get('/request/complete', [PasswordResetController::class, 'showEmailSent'])->name('request.complete');
Route::get('/reset', [PasswordResetController::class, 'showResetForm'])->name('reset.showResetForm');
Route::post('/reset', [PasswordResetController::class, 'reset']);
