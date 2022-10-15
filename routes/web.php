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
Route::get('/', [TopicController::class, 'index'])->name('topics.index');
Route::get('/topics/create', [TopicController::class, 'create'])->name('topics.create');
Route::post('/topics', [TopicController::class, 'store'])->name('topics.store');
Route::get('/topics/{id}', [TopicController::class, 'show'])->name('topics.show');
Route::get('/topics/edit/{id}', [TopicController::class, 'edit'])->name('topics.edit');
Route::put('/topics/{id}', [TopicController::class, 'update'])->name('topics.update');
Route::get('/topics/delete/{id}', [TopicController::class, 'confirmDelete'])->name('topics.confirmDelete');
Route::delete('/topics/{id}', [TopicController::class, 'destroy'])->name('topics.destroy');
Route::post('/topics/status/{id}', [TopicController::class, 'updateStatus'])->name('topics.updateStatus');

//カテゴリー
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::get('/categories/delete/{id}', [CategoryController::class, 'confirmDelete'])->name('categories.confirmDelete');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

//反論
Route::post('/objections', [ObjectionController::class, 'store'])->name('objections.store');
Route::get('/objections/{id}', [ObjectionController::class, 'edit'])->name('objections.edit');
Route::put('/objections/{id}', [ObjectionController::class, 'update'])->name('objections.update');
Route::delete('/objections/{id}', [ObjectionController::class, 'destroy'])->name('objections.destroy');

//意見
Route::get('/opinions/create', [OpinionController::class, 'create'])->name('opinions.create');
Route::post('/opinions', [OpinionController::class, 'store'])->name('opinions.store');
Route::get('/opinions/{id}', [OpinionController::class, 'edit'])->name('opinions.edit');
Route::put('/opinions/{id}', [OpinionController::class, 'update'])->name('opinions.update');

//パスワードリセット
Route::get('/request', [PasswordResetController::class, 'showRequestForm'])->name('request.showRequestForm');
Route::post('/request', [PasswordResetController::class, 'request']);
Route::get('/request/complete', [PasswordResetController::class, 'showEmailSent'])->name('request.complete');
Route::get('/reset', [PasswordResetController::class, 'showResetForm'])->name('reset.showResetForm');
Route::post('/reset', [PasswordResetController::class, 'reset']);
