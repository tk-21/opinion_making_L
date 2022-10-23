@extends('layouts.default')
@section('content')

    @php
        $status = $topic->status ? '完了' : '未完了';

        // カテゴリーが削除されていれば未選択にする
        $category_name = $topic->category_delete ? '未選択' : $topic->category_name;
    @endphp

    <section class="confirm">
        <div class="inner">
            <form class="confirm-form" action="{{ route('topics.destroy', ['topic' => $topic]) }}" method="post">
                @csrf
                @method('DELETE')

                <h2 class="confirm-ttl">トピック削除確認</h2>

                <p class="confirm-txt"><span class="marker">本当に削除してもよろしいですか？</span></p>

                <dl class="confirm-list">
                    <dt class="confirm-dttl">タイトル</dt>
                    <dd class="confirm-item">
                        {{ $topic->title }}
                    </dd>

                    <dt class="confirm-dttl">本文</dt>
                    <dd class="confirm-item">
                        {{ $topic->body }}
                    </dd>

                    <dt class="confirm-dttl">ポジション</dt>
                    <dd class="confirm-item">
                        {{ $topic->position }}
                    </dd>

                    <dt class="confirm-dttl">ステータス</dt>
                    <dd class="confirm-item">
                        {{ $status }}
                    </dd>

                    <dt class="confirm-dttl">カテゴリー</dt>
                    <dd class="confirm-item">
                        {{ $category->name }}
                    </dd>
                </dl>

                <button type="submit" class="register-btn">削除</button>

                <a class="back-btn _back" href="{{ route('topics.show', ['topic' => $topic]) }}">戻る</a>
            </form>
        </div>
    </section>

@endsection
