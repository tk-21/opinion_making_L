<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login\LoginUserRequest;
use App\Http\Requests\Store\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use MongoDB\Driver\Session;

class AuthController extends Controller
{
    // ログイン画面を表示
    public function showLoginForm()
    {
        return view('auth.login');
    }


    public function login(LoginUserRequest $request)
    {
        $credentials = $request->validated();

        // Auth::attemptメソッドでログイン情報が正しいか検証
        if (Auth::attempt($credentials)) {
            // セッションを再生成する処理(セキュリティ対策)
            $request->session()->regenerate();

            // ミドルウェアに対応したリダイレクト
            return redirect()->intended('/')->with('info', Auth::user()->name . 'さん、ようこそ。');
        }

        // 一つ前のページ(ログイン画面)にリダイレクト
        // その際にwithErrorsを使ってエラーメッセージで手動で指定する
        // リダイレクト後のビュー内でold関数によって直前の入力内容を取得出来る項目をonlyInputで指定する
        return back()->withErrors([
            'name' => 'ユーザー名またはパスワードが正しくありません',
        ])->onlyInput('name');
    }


    public function logout(Request $request)
    {
        // ログアウト処理
        Auth::logout();
        // 現在使っているセッションを無効化(セキュリティ対策のため)
        $request->session()->invalidate();
        // セッションを無効化を再生成(セキュリティ対策のため)
        $request->session()->regenerateToken();

        return to_route('login')->with('info', 'ログアウトしました。');
    }


    public function showRegisterForm()
    {
        return view('auth.register');
    }


    public function register(StoreUserRequest $request)
    {
        $validated = $request->validated();
        //パスワードをハッシュ化
        $validated['password'] = Hash::make($validated['password']);
        //登録実行
        $user = User::create($validated);
        //登録したユーザーでログイン
        Auth::login($user);

        return to_route('index')->with('info', $user->name . 'さん、ようこそ。');
    }

}
