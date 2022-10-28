@extends('layouts.default')
@section('content')

    @php
        $label = $type == 'objection' ? '反論の編集' : '反論への反論の編集';
    @endphp

    <section class="edit">
        <div class="edit-inner">
            <form class="edit-form validate-form" action="{{ route('objections.update', ['objection' => $objection]) }}"
                  method="POST" novalidate>
                @csrf
                @method('PUT')

                <dl class="edit-list">

                    <dt class="edit-dttl"><label for="body" onclick="">{{ $label }}</label></dt>
                    <dd class="edit-item">
                        <textarea id="body" name="body" class="edit-body input validate-target" autofocus
                                  required>{{ old('body', $objection->body) }}</textarea>
                        <p class="invalid-feedback"></p>
                    </dd>

                </dl>

                <button type="submit" class="register-btn">更新</button>

                <a class="back-btn _back"
                   href="{{ route('topics.show', ['topic' => $objection->topic_id]) }}">戻る</a>

            </form>
        </div>
    </section>
@endsection
