<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CounterObjectionController;
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
        Route::get('/reset_password', 'showResetForm')->name('showResetForm');
        Route::post('/reset_password', 'reset')->name('reset_password');
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
                Route::get('/{topic}', 'show')->name('show');
                Route::get('/edit/{topic}', 'edit')->name('edit');
                Route::put('/{topic}', 'update')->name('update');
                Route::get('/delete/{topic}', 'confirmDelete')->name('confirmDelete');
                Route::delete('/{topic}', 'destroy')->name('destroy');
                Route::post('/status', 'updateStatus')->name('updateStatus');
            });


//カテゴリー
        Route::prefix('/categories')
            ->controller(CategoryController::class)
            ->name('categories.')
            ->group(function () {
                Route::post('/', 'store')->name('store');
                Route::get('/{category}', 'show')->name('show');
                Route::get('/edit/{category}', 'edit')->name('edit');
                Route::put('/{category}', 'update')->name('update');
                Route::get('/delete/{category}', 'confirmDelete')->name('confirmDelete');
                Route::delete('/{category}', 'destroy')->name('destroy');
            });


//反論
        Route::prefix('/objections')
            ->controller(ObjectionController::class)
            ->name('objections.')
            ->group(function () {
                Route::post('/', 'store')->name('store');
                Route::get('/{objection}', 'edit')->name('edit');
                Route::put('/{objection}', 'update')->name('update');
                Route::delete('/delete', 'destroy')->name('destroy');
            });


//反論への反論
        Route::prefix('/counter_objections')
            ->controller(CounterObjectionController::class)
            ->name('counter_objections.')
            ->group(function () {
                Route::post('/', 'store')->name('store');
                Route::get('/{counterObjection}', 'edit')->name('edit');
                Route::put('/{counterObjection}', 'update')->name('update');
                Route::delete('/{counterObjection}', 'destroy')->name('destroy');
            });


//意見
        Route::prefix('/opinions')
            ->controller(OpinionController::class)
            ->name('opinions.')
            ->group(function () {
                Route::get('/create/{topic}', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{opinion}', 'edit')->name('edit');
                Route::put('/{opinion}', 'update')->name('update');
            });


//パスワードリセット
        Route::prefix('/password_reset')
            ->name('password_reset.')
            ->group(function () {
                Route::prefix('/email')
                    ->name('email.')
                    ->group(function () {
                        Route::get('/', [PasswordController::class, 'emailFormResetPassword'])->name('form');
                        Route::post('/', [PasswordController::class, 'sendEmailResetPassword'])->name('send');
                        Route::get('/send_complete', [PasswordController::class, 'sendComplete'])->name('send_complete');
                    });
                Route::get('/edit', [PasswordController::class, 'edit'])->name('edit');
                Route::post('/update', [PasswordController::class, 'update'])->name('update');
                Route::get('/edited', [PasswordController::class, 'edited'])->name('edited');
            });

    });
