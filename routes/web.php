<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ObjectionController;
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


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/detail/{topic}', [TopicController::class, 'index'])->name('topics.index');
Route::post('/detail/{topic}', [ObjectionController::class, 'store'])->name('objections.store');

Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::get('/categories/delete/{id}', [CategoryController::class, 'confirmDelete'])->name('categories.confirmDelete');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

Route::get('/', [TopicController::class, 'index'])->name('topics.index');
Route::get('/topics/create', [TopicController::class, 'create'])->name('topics.create');
Route::post('/topics', [TopicController::class, 'store'])->name('topics.store');
Route::get('/topics/{id}', [TopicController::class, 'show'])->name('topics.show');
Route::get('/topics/edit/{id}', [TopicController::class, 'edit'])->name('topics.edit');
Route::put('/topics/{id}', [TopicController::class, 'update'])->name('topics.update');
Route::get('/topics/delete/{id}', [TopicController::class, 'confirmDelete'])->name('topics.confirmDelete');
Route::delete('/topics/{id}', [TopicController::class, 'destroy'])->name('topics.destroy');
Route::post('/topics/status/{id}', [TopicController::class, 'updateStatus'])->name('topics.updateStatus');
