<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login\LoginUserRequest;
use App\Http\Requests\Store\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            return redirect()->intended('/');

            // 一つ前のページ(ログイン画面)にリダイレクト
            // その際にwithErrorsを使ってエラーメッセージで手動で指定する
            // リダイレクト後のビュー内でold関数によって直前の入力内容を取得出来る項目をonlyInputで指定する
            return back()->withErrors([
                'name' => 'ユーザー名またはパスワードが正しくありません',
            ])->onlyInput('name');
        }


//        // POSTで渡ってきたユーザーネームとパスワードでログインに成功した場合、
//        if (Auth::login($valid_name, $valid_password)) {
//            // 登録されたユーザーオブジェクトの情報を取ってくる
//            $user = UserModel::getSession();
//            // オブジェクトに格納されている情報を使って、セッションのINFOにメッセージを入れる
//            Msg::push(Msg::INFO, "{$user->name}さん、ようこそ。");
//            // パスが空だったらトップページに移動
//            redirect(GO_HOME);
//        } else {
//            // Auth::loginによって何がエラーかというのはpushされるので、ここでエラーメッセージは出さなくてよい
//
//            // refererは一つ前のリクエストのパスを表す
//            // 認証が失敗したときは、一つ前のリクエスト（GETメソッドでのログインページへのパス）に戻る
//            redirect(GO_REFERER);
//        }
    }


    public function logout(Request $request)
    {
        // ログアウト処理
        Auth::logout();
        // 現在使っているセッションを無効化(セキュリティ対策のため)
        $request->session()->invalidate();
        // セッションを無効化を再生成(セキュリティ対策のため)
        $request->session()->regenerateToken();

        return to_route('login')->with('success', 'ログアウトしました。');
    }


    public function showRegisterForm()
    {
        return view('auth.register');
    }


    public function register(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        $name = Auth::user();

        return to_route('topics.index')->with('success', $name . 'さん、ようこそ。');

        // 登録処理
//        if (Auth::regist($valid_name, $valid_password, $valid_email)) {
//            Msg::push(Msg::INFO, "{$name}さん、ようこそ。");
//            redirect(GO_HOME);
//        } else {
//            redirect(GO_REFERER);
//        }
    }

}
