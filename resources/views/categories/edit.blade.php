@extends('layouts.default')
@section('content')
    {{--    $category = escape($category);--}}



    <section class="edit">
        <div class="edit-inner">
            <form class="edit-form validate-form" action="" method="POST" novalidate>
                <input type="hidden" name="id" value="<?php echo $category->id; ?>">

                <dl class="edit-list">

                    <dt class="edit-dttl"><label for="name" onclick="">カテゴリー名の編集</label></dt>
                    <dd class="edit-item">
                        <textarea id="name" name="name" class="edit-name input validate-target" autofocus
                                  required><?php echo $category->name; ?></textarea>
                        <p class="invalid-feedback"></p>
                    </dd>

                </dl>

                <button type="submit" class="register-btn">更新</button>

                <a class="back-btn _back" href="<?php the_url(sprintf('category?id=%d', $category->id)); ?>">戻る</a>

            </form>
        </div>
    </section>

@endsection
