@extends('layouts.default')
@section('content')
    {{--    $category = escape($category);--}}


    <section class="confirm">
        <div class="edit-inner">
            <form class="confirm-form" action="" method="post">
                <input type="hidden" name="category_id" value="<?php echo $category->id; ?>">

                <h2 class="confirm-ttl">カテゴリー削除確認</h2>

                <p class="confirm-txt"><span class="marker">本当に削除してもよろしいですか？</span></p>

                <dl class="confirm-list">
                    <dt class="confirm-dttl">カテゴリー名</dt>
                    <dd class="confirm-item">
                        <?php echo $category->name; ?>
                    </dd>
                </dl>

                <button type="submit" class="register-btn">削除</button>

                <a class="back-btn _back" href="<?php the_url(sprintf('category?id=%d', $category->id)); ?>">戻る</a>
            </form>
        </div>
    </section>

@endsection
