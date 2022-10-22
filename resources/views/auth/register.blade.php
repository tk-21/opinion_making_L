@extends('layouts.default')

@section('content')

    <section class="auth">
        <div class="auth-inner">
            <form class="auth-form validate-form" action="{{ route('register') }}" method="POST" novalidate>
                @csrf
                <h2 class="auth-ttl">アカウント登録</h2>
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

                    <dt class="auth-dttl"><label for="email" onclick="">メールアドレス</label></dt>
                    <dd class="auth-item">
                        <input id="email" type="email" name="email" class="auth-input validate-target" autofocus
                               required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$">
                        <p class="invalid-feedback" id="mail-feedback"></p>
                    </dd>

                </dl>
                <button type="submit" class="register-btn auth-btn">登録</button>
            </form>

            <a class="back-btn _home" href="{{ route('login') }}">ログイン画面へ</a>

        </div>
    </section>

@endsection
