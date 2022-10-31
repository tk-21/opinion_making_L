@extends('layouts.default')
@section('content')
    <section class="auth">
        <div class="auth-inner">
            <form class="auth-form validate-form" action="" method="POST" novalidate>

                <input type="hidden" name="password_reset_token" value="<? echo $passwordResetToken; ?>">
                <input type="hidden" name="csrf_token" value="<? echo $csrf_token; ?>">

                <h2 class="auth-ttl">パスワードの変更</h2>

                <p class="auth-txt">新しいパスワードを入力してください。</p>

                <dl class="auth-list">
                    <dt class="auth-dttl"><label for="password">新しいパスワード</label></dt>
                    <dd class="auth-item">
                        <input id="password" type="password" name="password" class="auth-input validate-target"
                               autofocus required minlength="4" maxlength="10" pattern="[a-zA-Z0-9]+">
                        <p class="invalid-feedback"></p>
                    </dd>

                    <dt class="auth-dttl"><label for="password_confirmation">新しいパスワードを再入力</label></dt>
                    <dd class="auth-item">
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               class="auth-input validate-target" autofocus required minlength="4" maxlength="10"
                               pattern="[a-zA-Z0-9]+">
                        <p class="invalid-feedback"></p>
                    </dd>

                </dl>
                <button type="submit" class="register-btn auth-btn">変更</button>
            </form>


        </div>
    </section>

@endsection
