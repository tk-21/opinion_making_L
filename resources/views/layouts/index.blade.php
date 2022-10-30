@extends('layouts.default')

@section('content')

    <article class="home" id="home">
        <div class="inner">
            <ul class="home-list">
                <li class="home-topic">

                    @yield('title')

                    @if ($topics->isNotEmpty())

                        <ul class="home-topic-list">
                            @foreach ($topics as $topic)
                                @php
                                    // 日時表示をフォーマットするためオブジェクトを作成
                                    $created_at = new DateTime($topic->created_at);
                                @endphp

                                <div class="home-topic-wrapper">
                                    <label>
                                        完了チェック
                                        <input type="checkbox" class="home-topic-status" name="status"
                                               data-id="{{ $topic->id }}"
                                               @if ($topic->status) checked @endif>
                                        <span class="dummy-checkbox"></span>
                                    </label>

                                    <li class="home-topic-item">
                                        <a href="{{ route('topics.show', ['topic' => $topic]) }}">
                                            <p class="home-topic-label _{{ $topic->status ? 'complete' : 'incomplete' }}">{{ $topic->status ? '完了' : '未完了' }}</p>
                                            <div class="home-topic-body">
                                                <time
                                                    datetime="{{ $topic->created_at }}">{{ $created_at->format('Y.m.d') }}</time>
                                                <p class="home-topic-ttl">{{ $topic->title }}</p>
                                            </div>
                                        </a>

                                    </li>
                                </div>
                            @endforeach
                        </ul>

                        {{--                    ページャー--}}
                        @include('layouts.pager')

                    @else

                        <p class="home-txt _top">まだトピックがありません。</p>
                        <p class="home-txt _top">トピックを作成してみましょう！</p>

                    @endif

                </li>

                <li class="home-category">
                    <p class="home-category-ttl">カテゴリー</p>

                    <form class="home-category-form validate-form" action="{{ route('categories.store') }}"
                          method="post">
                        @csrf
                        <textarea class="home-category-textarea input validate-target" name="name"
                                  required>{{ old('name') }}</textarea>
                        <button type="submit" class="category-btn">作成</button>
                    </form>

                    <ul class="home-category-list">
                        @foreach ($categories as $category)
                            <li class="home-category-item">
                                <a href="{{ route('categories.show', ['category' => $category]) }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>

        </div>
    </article>
@endsection
