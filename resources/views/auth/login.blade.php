@extends('layouts.default')

@section('content')

    <section class="auth">
        <div class="auth-inner">
            <form class="auth-form validate-form" action="" method="POST" novalidate>
                @csrf
                <h2 class="auth-ttl">ログイン</h2>
                <dl class="auth-list">

                    <dt class="auth-dttl"><label for="name" onclick="">ユーザーネーム</label></dt>
                    <dd class="auth-item">
                        <input id="name" type="text" name="name" class="auth-input validate-target" autofocus required
                               minlength="4" maxlength="10" pattern="[a-zA-Z0-9]+">
                        <p class="invalid-feedback"></p>
                    </dd>

                    <dt class="auth-dttl"><label for="password" onclick="">パスワード</label></dt>
                    <dd class="auth-item">
                        <input id="password" type="password" name="password" class="auth-input validate-target"
                               autofocus required minlength="4" maxlength="10" pattern="[a-zA-Z0-9]+">
                        <p class="invalid-feedback"></p>
                    </dd>

                </dl>
                <button type="submit" class="register-btn auth-btn">ログイン</button>
            </form>

            <a class="back-btn _home" href="{{ route('showRequestForm') }}">パスワードを忘れた方はこちら</a>
            <a class="back-btn _home" href="{{ route('register') }}">アカウント登録</a>

        </div>
    </section>

@endsection
