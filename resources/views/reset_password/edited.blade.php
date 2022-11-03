@extends('layouts.default')
@section('content')
    <section class="sent">
        <div class="inner">
            <ul class="sent-list">
                <li class="sent-item">パスワードリセットが完了しました。</li>
            </ul>

            <a class="back-btn _home" href="{{ route('login') }}">ログイン画面に戻る</a>

        </div>
    </section>

@endsection
