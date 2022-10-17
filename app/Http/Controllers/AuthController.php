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
        return view('auth.index');
//        \view\auth\index(true);
    }


    public function login(LoginUserRequest $request)
    {
        $credentials = $request->validated();

        // Auth::attemptメソッドでログイン情報が正しいか検証
        if (Auth::attempt($credentials)) {
            // セッションを再生成する処理(セキュリティ対策)
            $request->session()->regenerate();

            // ミドルウェアに対応したリダイレクト(後述)
            // 下記はredirect('/admin/blogs')に類似
            return redirect()->intended('/admin/blogs');

            // 一つ前のページ(ログイン画面)にリダイレクト
            // その際にwithErrorsを使ってエラーメッセージで手動で指定する
            // リダイレクト後のビュー内でold関数によって直前の入力内容を取得出来る項目をonlyInputで指定する
            return back()->withErrors([
                'email' => 'メールアドレスまたはパスワードが正しくありません',
            ])->onlyInput('email');
        }


        // 値の取得
//        $name = get_param('name', '');
//        $password = get_param('password', '');
//
//        // バリデーション
//        $validation = new UserValidation($name, $password);
//
//        if (
//            !($validation->validateName()
//                * $validation->validatePassword())
//        ) {
//            redirect(GO_REFERER);
//        }
//
//        $valid_name = $validation->getValidName();
//        $valid_password = $validation->getValidPassword();
//
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


    public function logout()
    {
//        if (Auth::logout()) {
//            Msg::push(Msg::INFO, 'ログアウトしました。');
//            redirect('login');
//        } else {
//            Msg::push(Msg::ERROR, 'ログアウトに失敗しました。');
//        }

        // ログアウト処理
        Auth::logout();
        // 現在使っているセッションを無効化(セキュリティ対策のため)
        $request->session()->invalidate();
        // セッションを無効化を再生成(セキュリティ対策のため)
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');

    }


    public function showRegisterForm()
    {
        if (Auth::isLogin()) {
            redirect(GO_HOME);
        }

        // 登録画面を表示
        \view\auth\index(false);
    }


    public function register(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return to_route('login')->with('success', '登録が完了しました');

        // 登録処理
//        if (Auth::regist($valid_name, $valid_password, $valid_email)) {
//            Msg::push(Msg::INFO, "{$name}さん、ようこそ。");
//            redirect(GO_HOME);
//        } else {
//            redirect(GO_REFERER);
//        }
    }

}
