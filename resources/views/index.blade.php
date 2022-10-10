@extends('layouts.default')

@section('content')
    @php
        $topics = escape($topics);
        $categories = escape($categories);

        $title = $is_home ? 'トピック一覧' : sprintf('カテゴリー名：%s', $fetchedCategory->name);
        $path = $is_home ? 'home' : 'category';
    @endphp

    <article class="home" id="home">
        <div class="inner">
            <ul class="home-list">
                <li class="home-topic">
                    <h2 class="home-ttl">{{ $title }}</h2>

                    @if ($path === 'category')
                            <?php $category_id = get_param('id', null, false) ?>

                        <a class="category-edit-btn"
                           href="<?php the_url(sprintf('category_edit?id=%s', $category_id)); ?>">カテゴリー名編集</a>
                        <a class="category-delete-btn"
                           href="<?php the_url(sprintf('category_delete?id=%s', $category_id)); ?>">削除</a>

                    @endif


                    @if ($topics)

                        <ul class="home-topic-list">
                            @foreach ($topics as $topic)
                                @php
                                    // complete_flgが１のときは完了、０のときは未完了を表示させる
                                    $label = $topic->complete_flg ? '完了' : '未完了';

                                    // ラベルのデザインを切り替える
                                    $label_style = $topic->complete_flg ? 'complete' : 'incomplete';

                                    // 日時表示をフォーマットするためオブジェクトを作成
                                    $created_at = new DateTime($topic->created_at);
                                @endphp

                                <div class="home-topic-wrapper">
                                    <label>
                                        完了チェック
                                        <input type="checkbox" class="home-topic-status" name="complete_flg"
                                               data-id="{{ $topic->id }}"
                                               <?php if ($topic->complete_flg) : ?>checked <?php endif; ?>>
                                        <span class="dummy-checkbox"></span>
                                    </label>

                                    <li class="home-topic-item">
                                        <a href="<?php the_url(sprintf('detail?id=%s', $topic->id)); ?>">
                                            <p class="home-topic-label _{{ $label_style }}">{{ $label }}</p>
                                            <div class="home-topic-body">
                                                <time
                                                    datetime="{{ $topic->created_at }}">{{ $created_at->format('Y.m.d') }}</time>
                                                <p class="home-topic-ttl">{{ $topic->title }}</p>
                                            </div>
                                        </a>

                                    </li>
                                </div>
                            @endforeach
                        </ul>

                        <div class="paging">
                            <p class="paging-txt">全件数：<?php echo $topic_num; ?>件</p>
                                <?php $category_id = get_param('id', null, false) ?>
                            <ul class="paging-list">
                                    <?php // 現在のページが２以上のときだけ「戻る」にリンクを付ける
                                    ?>
                                <li class="paging-item">
                                        <?php if ($current_page >= 2) : ?>
                                    <a href="<?php the_url(sprintf('%s?id=%s&page=%d', $path, $category_id, ($current_page - 1))); ?>">&laquo;</a>
                                    <?php else : ?>
                                    <span class="paging-pre">&laquo;</span>
                                    <?php endif; ?>
                                </li>

                                    <?php // １〜最大ページまでループさせ、$rangeで表示範囲を５件に絞る。現在のページ番号にはリンクを付けない。
                                    ?>
                                    <?php for ($i = 1;
                                               $i <= $max_page;
                                               $i++) : ?>
                                    <?php if ($i >= $current_page - $range && $i <= $current_page + $range) : ?>
                                <li class="paging-item">
                                        <?php if ($i == $current_page) : ?>
                                    <span class="paging-now"><?php echo $i; ?></span>
                                    <?php else : ?>
                                    <a href="<?php the_url(sprintf('%s?id=%s&page=%d', $path, $category_id, $i)); ?>"
                                       class="paging-num"><?php echo $i; ?></a>
                                    <?php endif; ?>
                                </li>
                                <?php endif; ?>
                                <?php endfor; ?>

                                    <?php // 現在ページが最大ページ数を超えたら「進む」にリンクを付けない
                                    ?>
                                <li class="paging-item">
                                        <?php if ($current_page < $max_page) : ?>
                                    <a href="<?php the_url(sprintf('%s?id=%s&page=%d', $path, $category_id, ($current_page + 1))); ?>">&raquo;</a>
                                    <?php else : ?>
                                    <span class="paging-next">&raquo;</span>
                                    <?php endif; ?>
                                </li>
                            </ul>
                        </div>

                        @if ($path === 'category')
                            <a class="back-btn _home" href="<?php the_url('/'); ?>">すべてのトピックを表示</a>
                        @endif

                    @else

                        @if ($path === 'category')
                            <p class="home-txt _bottom">このカテゴリーに分類されているトピックがありません。</p>
                            <a class="back-btn _home" href="<?php the_url('/'); ?>">ホームへ戻る</a>
                            <?php else : ?>
                            <p class="home-txt _top">まだトピックがありません。</p>
                            <p class="home-txt _top">トピックを作成してみましょう！</p>
                        @endif

                    @endif

                </li>

                <li class="home-category">
                    <p class="home-category-ttl">カテゴリー</p>

                    <form class="home-category-form validate-form" action="" method="post">
                        <textarea class="home-category-textarea input validate-target" name="name" required></textarea>
                        <button type="submit" class="category-btn">作成</button>
                    </form>

                    <ul class="home-category-list">
                        @foreach ($categories as $category)
                            <li class="home-category-item">
                                <a href="<?php the_url(sprintf('category?id=%s', $category->id)); ?>">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>

        </div>
    </article>
@endsection
