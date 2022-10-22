@extends('layouts.default')
@section('content')

    <section class="topic">
        <div class="inner">
            <form class="topic-form validate-form" action="" method="POST" novalidate>
                @csrf
                <input type="hidden" name="id" value="{{ $topic->id }}">

                <h2 class="topic-ttl">トピック編集</h2>

                <dl class="topic-list">
                    <dt class="topic-dttl"><label for="title" onclick="">タイトル</label></dt>
                    <dd class="topic-item">
                        <input type="text" id="title" name="title" value="{{ old('title', $topic->title) }}"
                               class="topic-input input validate-target" maxlength="100" autofocus required>
                        <p class="invalid-feedback"></p>
                    </dd>

                    <dt class="topic-dttl"><label for="body" onclick="">本文</label></dt>
                    <dd class="topic-item">
                        <textarea id="body" name="body" class="topic-textarea input validate-target" autofocus
                                  required>{{ old('body', $topic->body) }}</textarea>
                        <p class="invalid-feedback"></p>
                    </dd>

                    <dt class="topic-dttl"><label for="position" onclick="">ポジション</label></dt>
                    <dd class="topic-item">
                        <textarea id="position" name="position" class="topic-textarea input validate-target" autofocus
                                  required>{{ old('position', $topic->position) }}</textarea>
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
                                        @if($category->id === old('category_id', $topic->category_id)) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </dd>
                    @endif

                </dl>

                <button type="submit" class="register-btn">更新</button>

                <a class="back-btn _back" href="{{ route('topics.show') }}">戻る</a>

            </form>
        </div>
    </section>

@endsection
