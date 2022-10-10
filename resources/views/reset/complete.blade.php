@extends('layouts.default')
@section('content')
    <section class="sent">
        <div class="inner">
            <ul class="sent-list">
                <li class="sent-item">パスワード再設定用メールを送信しました。</li>
                <li class="sent-item">メールに記載されているURLからパスワードリセットの手続きを行ってください。</li>
            </ul>

            <a class="back-btn _home" href="<?php the_url('login'); ?>">ログイン画面に戻る</a>

        </div>
    </section>

@endsection
