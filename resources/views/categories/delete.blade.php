@extends('layouts.default')
@section('content')

    <section class="confirm">
        <div class="edit-inner">
            <form class="confirm-form" action="{{ route('categories.destroy', ['category' => $category]) }}"
                  method="post">
                @csrf
                @method('DELETE')

                <h2 class="confirm-ttl">カテゴリー削除確認</h2>

                <p class="confirm-txt"><span class="marker">本当に削除してもよろしいですか？</span></p>

                <dl class="confirm-list">
                    <dt class="confirm-dttl">カテゴリー名</dt>
                    <dd class="confirm-item">
                        {{ $category->name }}
                    </dd>
                </dl>

                <button type="submit" class="register-btn">削除</button>

                <a class="back-btn _back" href="{{ route('categories.show', ['category' => $category]) }}">戻る</a>
            </form>
        </div>
    </section>

@endsection
