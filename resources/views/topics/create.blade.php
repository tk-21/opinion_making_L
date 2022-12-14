@extends('layouts.default')
@section('content')

    <section class="topic">
        <div class="inner">
            <form class="topic-form validate-form" action="{{ route('topics.store') }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <h2 class="topic-ttl">トピック作成</h2>

                <dl class="topic-list">
                    <dt class="topic-dttl"><label for="title" onclick="">タイトル</label></dt>
                    <dd class="topic-item">
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                               class="topic-input input validate-target" maxlength="100" autofocus required>
                        <p class="invalid-feedback"></p>
                    </dd>

                    <dt class="topic-dttl"><label for="body" onclick="">本文</label></dt>
                    <dd class="topic-item">
                        <textarea id="body" name="body" class="topic-textarea input validate-target" autofocus
                                  required>{{ old('body') }}</textarea>
                        <p class="invalid-feedback"></p>
                    </dd>

                    <dt class="topic-dttl"><label for="position" onclick="">ポジション</label></dt>
                    <dd class="topic-item">
                        <textarea id="position" name="position" class="topic-textarea input validate-target" autofocus
                                  required>{{ old('position') }}</textarea>
                        <p class="invalid-feedback"></p>
                    </dd>

                    @if ($categories)
                        <dt class="topic-dttl">カテゴリー</dt>
                        <dd class="topic-item">
                            <select class="topic-select" name="category_id">
                                <option value="">カテゴリーを選択</option>
                                @foreach ($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        @if(old('category_id') == $category->id) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </dd>
                    @endif

                </dl>

                <button type="submit" class="register-btn">登録</button>

                <a class="back-btn _home" href="{{ route('index') }}">ホームへ戻る</a>

            </form>
        </div>
    </section>

@endsection
