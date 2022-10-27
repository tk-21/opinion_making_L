@extends('layouts.default')
@section('content')

    <section class="edit">
        <div class="edit-inner">
            <form class="edit-form validate-form" action="{{ route('categories.update',['category' => $category]) }}"
                  method="POST" novalidate>
                @csrf
                @method('PUT')

                <dl class="edit-list">

                    <dt class="edit-dttl"><label for="name" onclick="">カテゴリー名の編集</label></dt>
                    <dd class="edit-item">
                        <textarea id="name" name="name" class="edit-name input validate-target" autofocus
                                  required>{{ old('name', $category->name) }}</textarea>
                        <p class="invalid-feedback"></p>
                    </dd>

                </dl>

                <button type="submit" class="register-btn">更新</button>

                <a class="back-btn _back" href="{{ route('categories.show',['category' => $category]) }}">戻る</a>

            </form>
        </div>
    </section>

@endsection
