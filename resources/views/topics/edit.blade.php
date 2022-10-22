@extends('layouts.default')
@section('content')
    {{--    $topic = escape($topic);
        $categories = escape($categories);

        // トピック作成、編集を兼ねているファイル
        // タイプによって表示内容を変える

        if ($type === 'create') {
        $header_ttl = 'トピック作成';
        $submit_btn = '登録';
        } elseif ($type === 'edit') {
        $header_ttl = 'トピック編集';
        $submit_btn = '更新';
        }--}}


    <section class="topic">
        <div class="inner">
            <form class="topic-form validate-form" action="" method="POST" novalidate>
                <input type="hidden" name="id" value="<?php echo $topic->id; ?>">

                <h2 class="topic-ttl"><?php echo $header_ttl; ?></h2>

                <dl class="topic-list">
                    <dt class="topic-dttl"><label for="title" onclick="">タイトル</label></dt>
                    <dd class="topic-item">
                        <input type="text" id="title" name="title" value="<?php echo $topic->title; ?>"
                               class="topic-input input validate-target" maxlength="100" autofocus required>
                        <p class="invalid-feedback"></p>
                    </dd>

                    <dt class="topic-dttl"><label for="body" onclick="">本文</label></dt>
                    <dd class="topic-item">
                        <textarea id="body" name="body" class="topic-textarea input validate-target" autofocus
                                  required><?php echo $topic->body; ?></textarea>
                        <p class="invalid-feedback"></p>
                    </dd>

                    <dt class="topic-dttl"><label for="position" onclick="">ポジション</label></dt>
                    <dd class="topic-item">
                        <textarea id="position" name="position" class="topic-textarea input validate-target" autofocus
                                  required><?php echo $topic->position; ?></textarea>
                        <p class="invalid-feedback"></p>
                    </dd>

                    <?php if ($categories) : ?>
                    <dt class="topic-dttl">カテゴリー</dt>
                    <dd class="topic-item">
                        <select class="topic-select" name="category_id">
                            <option value="">カテゴリーを選択</option>
                                <?php foreach ($categories as $category) : ?>
                            <option
                                value="<?php echo $category->id; ?>" <?php echo $topic->category_id === $category->id ? 'selected' : ''; ?>>
                                    <?php echo $category->name; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </dd>
                    <?php endif; ?>

                </dl>

                <button type="submit" class="register-btn"><?php echo $submit_btn; ?></button>

                <?php // トピック作成の場合はホームへ戻る、その他の場合は詳細画面に戻る
                ?>
                <?php if ($type === 'create') : ?>
                <a class="back-btn _home" href="<?php the_url('/'); ?>">ホームへ戻る</a>
                <?php else : ?>
                <a class="back-btn _back" href="<?php the_url(sprintf('detail?id=%d', $topic->id)); ?>">戻る</a>
                <?php endif; ?>

            </form>
        </div>
    </section>

@endsection
