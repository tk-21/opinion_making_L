@php use Illuminate\Support\Facades\Auth; @endphp
    <!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>opinion_making</title>
    <meta name="description" content="自分自身の意見をつくるトレーニングができるアプリです。"/>
    <!--OGPの設定-->
    <meta property="og:title" content="意見作成トレーニングアプリ"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content=""/>
    <meta property="og:image" content="{OGP画像のURL}"/>
    <meta property="og:site_name" content="意見作成トレーニングアプリ"/>
    <meta property="og:description" content="自分の頭で考え、自分自身の意見をつくるトレーニングができます。"/>
    <!--アイコンの設定-->
    <link rel="icon" href="{アイコンのパス}"/>
    <link rel="apple-touch-icon" href="{アイコンのパス}"/>
    <!--その他設定-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- フォントのcss -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&family=Roboto&display=swap" rel="stylesheet">
    <!-- style.css -->
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}"/>
    <!-- プラグインのcss -->
</head>

<body>
<!-- 背景ライン -->
<div class="bg">
    <ul class="bg-line">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>
<div class="wrapper">
    <header class="header">
        <div class="header-left">
            <h1 class="header-logo">
                <a href="{{ route('topics.index') }}">
                    <img src="/img/title.png" alt="思考トレーニングアプリ">
                </a>
            </h1>
            @auth
                <p class="header-txt">ユーザー名： {{ Auth::user()->name }}</p>
            @endauth
        </div>

        @auth
            <nav class="gnav">
                <ul class="gnav-list">
                    <li class="gnav-item"><a href="{{ route('topics.create') }}" class="create-btn">トピック作成</a></li>
                    <li class="gnav-item">
                        <form action="{{ route('logout')}}" method="POST">
                            @csrf
                            <button type="submit" class="logout-btn">
                                ログアウト
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        @endauth
    </header>

    <main>
        {{--        フラッシュメッセージ--}}
        {{--        成功時--}}
        @if(session()->has('info'))
            <p class="msg msg-info">{{ session('info') }}</p>
        @endif

        {{--        失敗時--}}
        @if($errors->any())
            @foreach($errors->all() as $error)
                <p class="msg msg-error">{{ $error }}</p>
            @endforeach
        @endif

        @yield('content')

    </main>


    <footer class="footer">
        <div class="inner">
            <p class="footer-txt">© 2022 TAKUYA OKUBO</p>
        </div>
    </footer>
</div>

<!-- jQuery本体 -->
<script src="{{ asset('/js/jquery-3.6.0.min.js') }}"></script>
<!-- jQueryプラグイン -->
<!-- js -->
{{--<script src="{{ asset('/js/form-validate.js') }}"></script>--}}
<script src="{{ asset('/js/ajax.js') }}"></script>
</body>

</html>

