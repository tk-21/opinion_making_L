<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::isLogin()) {
            redirect(GO_HOME);
        }

        // ログイン画面を表示
        \view\auth\index(true);
    }


    public function login()
    {
        // 値の取得
        $name = get_param('name', '');
        $password = get_param('password', '');

        // バリデーション
        $validation = new UserValidation($name, $password);

        if (
            !($validation->validateName()
                * $validation->validatePassword())
        ) {
            redirect(GO_REFERER);
        }

        $valid_name = $validation->getValidName();
        $valid_password = $validation->getValidPassword();

        // POSTで渡ってきたユーザーネームとパスワードでログインに成功した場合、
        if (Auth::login($valid_name, $valid_password)) {
            // 登録されたユーザーオブジェクトの情報を取ってくる
            $user = UserModel::getSession();
            // オブジェクトに格納されている情報を使って、セッションのINFOにメッセージを入れる
            Msg::push(Msg::INFO, "{$user->name}さん、ようこそ。");
            // パスが空だったらトップページに移動
            redirect(GO_HOME);
        } else {
            // Auth::loginによって何がエラーかというのはpushされるので、ここでエラーメッセージは出さなくてよい

            // refererは一つ前のリクエストのパスを表す
            // 認証が失敗したときは、一つ前のリクエスト（GETメソッドでのログインページへのパス）に戻る
            redirect(GO_REFERER);
        }
    }


    public function logout()
    {
        if (Auth::logout()) {
            Msg::push(Msg::INFO, 'ログアウトしました。');
            redirect('login');
        } else {
            Msg::push(Msg::ERROR, 'ログアウトに失敗しました。');
        }
    }


    public function showRegisterForm()
    {
        if (Auth::isLogin()) {
            redirect(GO_HOME);
        }

        // 登録画面を表示
        \view\auth\index(false);
    }


    public function register()
    {
        // 値の取得
        $name = get_param('name', '');
        $password = get_param('password', '');
        $email = get_param('email', '');

        // バリデーション
        $validation = new UserValidation($name, $password, $email);

        if (
            !($validation->validateName()
                * $validation->validatePassword()
                * $validation->validateEmail())
        ) {
            redirect(GO_REFERER);
        }

        $valid_name = $validation->getValidName();
        $valid_password = $validation->getValidPassword();
        $valid_email = $validation->getValidEmail();

        // 登録処理
        if (Auth::regist($valid_name, $valid_password, $valid_email)) {
            Msg::push(Msg::INFO, "{$name}さん、ようこそ。");
            redirect(GO_HOME);
        } else {
            redirect(GO_REFERER);
        }
    }

}
