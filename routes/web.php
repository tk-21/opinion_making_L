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

Route::controller(AuthController::class)
    ->group(function () {
//ログイン
        Route::get('/login', 'showLoginForm')->name('loginForm');
        Route::post('/login', 'login')->name('login');
//ユーザー登録
        Route::get('/register', 'showRegisterForm')->name('registerForm');
        Route::post('/register', 'register')->name('register');
    });


//パスワードリセット
Route::controller(PasswordResetController::class)
    ->group(function () {
        Route::get('/request', 'showRequestForm')->name('showRequestForm');
        Route::post('/request', 'request')->name('request');
        Route::get('/request/complete', 'showEmailSent')->name('request.complete');
        Route::get('/reset', 'showResetForm')->name('showResetForm');
        Route::post('/reset', 'reset')->name('reset');
    });


//以下はログイン状態のときのみ表示
Route::middleware('auth')
    ->group(function () {

//ホーム画面（トピックス一覧）
        Route::get('/', [TopicController::class, 'index'])->name('index');

//ログアウト
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


//トピックス
        Route::prefix('/topics')
            ->controller(TopicController::class)
            ->name('topics.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{id}', 'show')->name('show');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::get('/delete/{id}', 'confirmDelete')->name('confirmDelete');
                Route::delete('/{id}', 'destroy')->name('destroy');
                Route::post('/status/{id}', 'updateStatus')->name('updateStatus');
            });


//カテゴリー
        Route::prefix('/categories')
            ->controller(CategoryController::class)
            ->name('categories.')
            ->group(function () {
                Route::post('/', 'store')->name('store');
                Route::get('/{id}', 'show')->name('show');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::get('/delete/{id}', 'confirmDelete')->name('confirmDelete');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });


//反論
        Route::prefix('/objections')
            ->controller(ObjectionController::class)
            ->name('objections.')
            ->group(function () {
                Route::post('/', 'store')->name('store');
                Route::get('/{id}', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });


//意見
        Route::prefix('/opinions')
            ->controller(OpinionController::class)
            ->name('opinions.')
            ->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{id}', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
            });

    });
