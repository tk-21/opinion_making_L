@extends('layouts.default')
@section('content')
    <section class="request">
        <div class="edit-inner">
            <form class="request-form validate-form" action="" method="POST" novalidate>
                <input type="hidden" name="csrf_token" value="<? echo $csrf_token; ?>">

                <h2 class="request-ttl">パスワードリセット</h2>
                <dl class="request-list">

                    <dt class="request-dttl"><label for="email">登録しているメールアドレスを入力してください。</label></dt>
                    <dd class="request-item">
                        <input id="email" type="email" name="email" class="request-input validate-target" autofocus
                               required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$">
                        <p class="invalid-feedback" id="mail-feedback"></p>
                    </dd>

                </dl>
                <button type="submit" class="register-btn request-btn">パスワード再設定用メール送信</button>
            </form>

            <a class="back-btn _home" href="<?php the_url('login'); ?>">戻る</a>

        </div>
    </section>

@endsection
