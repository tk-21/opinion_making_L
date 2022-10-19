@extends('layouts.default')

@section('content')

    <article class="home" id="home">
        <div class="inner">
            <ul class="home-list">
                <li class="home-topic">
                    <h2 class="home-ttl">トピック一覧</h2>

                    @if ($topics)

                        <ul class="home-topic-list">
                            @foreach ($topics as $topic)
                                @php
                                    // complete_flgが１のときは完了、０のときは未完了を表示させる
                                    $label = $topic->status ? '完了' : '未完了';

                                    // ラベルのデザインを切り替える
                                    $label_style = $topic->status ? 'complete' : 'incomplete';

                                    // 日時表示をフォーマットするためオブジェクトを作成
                                    $created_at = new DateTime($topic->created_at);
                                @endphp

                                <div class="home-topic-wrapper">
                                    <label>
                                        完了チェック
                                        <input type="checkbox" class="home-topic-status" name="complete_flg"
                                               data-id="{{ $topic->id }}"
                                               @if ($topic->status) checked @endif>
                                        <span class="dummy-checkbox"></span>
                                    </label>

                                    <li class="home-topic-item">
                                        <a href="{{ route('topics.show', ['topic' => $topic->id]) }}">
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
                            <p class="paging-txt">全件数：{{ $topics->total() }}件</p>
                            {{--                                <?php $category_id = get_param('id', null, false) ?>--}}
                            <ul class="paging-list">
                                {{--                                    現在のページが２以上のときだけ「戻る」にリンクを付ける--}}
                                <li class="paging-item">
                                    @if ($topics->currentPage() >= 2)
                                        <a href="<?php the_url(sprintf('%s?id=%s&page=%d', $path, $category_id, ($current_page - 1))); ?>">&laquo;</a>
                                    @else
                                        <span class="paging-pre">&laquo;</span>
                                    @endif
                                </li>

                                {{--                                    １〜最大ページまでループさせ、$rangeで表示範囲を５件に絞る。現在のページ番号にはリンクを付けない。--}}
                                {{--                                    <?php for ($i = 1;--}}
                                {{--                                               $i <= $max_page;--}}
                                {{--                                               $i++) : ?>--}}
                                {{--                                    <?php if ($i >= $current_page - $range && $i <= $current_page + $range) : ?>--}}
                                @foreach($topics->getUrlRange($topics->currentPage()-3, $topics->currentPage()+3) as $page=>$link)

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

                    @else

                        <p class="home-txt _top">まだトピックがありません。</p>
                        <p class="home-txt _top">トピックを作成してみましょう！</p>

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
                                <a href="{{ route('categories.show', ['category' => $category->id]) }}">
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
