@extends('layouts.default')
@section('content')

    <section class="opinion">
        <div class="inner">
            <form class="opinion-form validate-form" action="{{ route('opinions.store') }}"
                  method="POST" novalidate>
                @csrf
                <input type="hidden" id="topic_id" name="topic_id" value="{{ $topic->id }}">

                <h2 class="opinion-ttl">最終意見の言語化</h2>

                <dl class="opinion-list">
                    <dt class="opinion-dttl"><label for="opinion" onclick="">自分の意見</label></dt>
                    <dd class="opinion-item">
                        <input type="text" id="opinion" name="opinion" value="{{ old('opinion') }}"
                               class="opinion-input input validate-target" autofocus required>
                        <p class="invalid-feedback"></p>
                    </dd>

                    <dt class="opinion-dttl"><label for="reason" onclick="">その理由</label></dt>
                    <dd class="opinion-item">
                        <textarea id="reason" name="reason" class="opinion-textarea input validate-target" autofocus
                                  required>{{ old('reason') }}</textarea>
                        <p class="invalid-feedback"></p>
                    </dd>
                </dl>

                <button type="submit" class="register-btn">登録</button>

                <a class="back-btn _back" href="{{ route('topics.show', ['topic' => $topic->id]) }}">戻る</a>

            </form>
        </div>
    </section>

@endsection
