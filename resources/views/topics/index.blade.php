@extends('layouts.default')
@section('content')
    {{--    $topic = escape($topic);
        $objections = escape($objections);
        $counterObjections = escape($counterObjections);
        $opinion = escape($opinion);

        $complete_flg = $topic->complete_flg ? '完了' : '未完了';

        // カテゴリーが削除されていれば未選択にする
        $category_name = $topic->category_delete ? '未選択' : $topic->category_name;
        $is_edit = $opinion ? 'edit' : 'create';--}}


    <section class="detail">
        <div class="detail-inner">

            <ul class="detail-list">
                <li class="detail-item">
                    <dl class="detail-topic">
                        <dt class="detail-topic-ttl">タイトル</dt>
                        <dd class="detail-topic-data"><?php echo $topic->title; ?></dd>
                        <dt class="detail-topic-ttl">内容</dt>
                        <dd class="detail-topic-data"><?php echo $topic->body; ?></dd>
                        <dt class="detail-topic-ttl">ポジション</dt>
                        <dd class="detail-topic-data"><?php echo $topic->position; ?></dd>
                        <dt class="detail-topic-ttl">ステータス</dt>
                        <dd class="detail-topic-data"><?php echo $complete_flg; ?></dd>
                        <dt class="detail-topic-ttl">カテゴリー</dt>
                        <dd class="detail-topic-data"><?php echo $category_name; ?></dd>
                    </dl>

                    <a class="edit-btn" href="<?php the_url(sprintf('topic_edit?id=%s', $topic->id)); ?>">編集</a>
                    <a class="delete-btn" href="<?php the_url(sprintf('topic_delete?id=%s', $topic->id)); ?>">削除</a>
                </li>

                <li class="detail-item">
                    <div class="objection">
                        <p class="objection-ttl">意見に対する反論</p>

                        <form class="objection-form validate-form" action="" method="post">
                            <input type="hidden" id="topic_id" name="topic_id" value="<?php echo $topic->id; ?>">
                            <input type="hidden" id="form_type" name="form_type" value="create_objection">
                            <textarea id="objection" class="objection-textarea input validate-target" name="body"
                                      required></textarea>
                            <button type="submit" id="objection_register" class="register-btn">登録</button>
                        </form>

                        <ol class="objection-list">
                            <?php $objection_num = 1; ?>
                            <?php foreach ($objections as $objection) : ?>
                            <li class="objection-item">
                                <div class="objection-txt-wrapper">
                                    <p class="objection-txt">
                                            <?php echo sprintf('%d . %s', $objection_num, $objection->body); ?>
                                    </p>
                                        <?php $objection_num++; ?>
                                </div>

                                <div class="objection-btn">
                                    <a class="objection-edit"
                                       href="<?php the_url(sprintf('objection_edit?type=%s&id=%d', 'objection', $objection->id)); ?>"><img
                                            src="../public/img/edit.svg" alt="編集"></a>

                                    <button type="submit" class="objection-delete"
                                            data-id="<?php echo $objection->id; ?>" data-type="objection"><img
                                            src="../public/img/delete.svg" alt="削除"></button>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ol>

                    </div>
                </li>

                <li class="detail-item">
                    <div class="objection">
                        <p class="objection-ttl">反論への反論</p>

                        <form class="objection-form validate-form" action="" method="post">
                            <input type="hidden" name="topic_id" value="<?php echo $topic->id; ?>">
                            <input type="hidden" name="form_type" value="create_counterObjection">
                            <textarea class="objection-textarea input validate-target" name="body" required></textarea>
                            <button type="submit" class="register-btn">登録</button>
                        </form>

                        <ol class="objection-list">
                            <?php $counterObjection_num = 1; ?>
                            <?php foreach ($counterObjections as $counterObjection) : ?>
                            <li class="objection-item">
                                <div class="objection-txt-wrapper">
                                    <p class="objection-txt">
                                            <?php echo sprintf('%d . %s', $counterObjection_num, $counterObjection->body); ?>
                                    </p>
                                        <?php $counterObjection_num++; ?>
                                </div>

                                <div class="objection-btn">
                                    <a class="objection-edit"
                                       href="<?php the_url(sprintf('objection_edit?type=%s&id=%d', 'counterObjection', $counterObjection->id)); ?>"><img
                                            src="../public/img/edit.svg" alt="編集"></a>

                                    <button type="submit" class="objection-delete"
                                            data-id="<?php echo $counterObjection->id; ?>" data-type="counterObjection">
                                        <img src="../public/img/delete.svg" alt="削除"></button>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ol>

                    </div>
                </li>
            </ul>

            <dl class="detail-opinion">
                <dt class="detail-opinion-ttl">最終的な意見：</dt>
                <dd class="detail-opinion-data"><?php echo $opinion->opinion; ?></dd>
                <dt class="detail-opinion-ttl">その理由：</dt>
                <dd class="detail-opinion-data"><?php echo $opinion->reason; ?></dd>
                <a class="edit-btn" href="<?php the_url(sprintf('opinion_%s?id=%d', $is_edit, $topic->id)); ?>">編集</a>
            </dl>

            <a class="back-btn _home" href="<?php the_url('/'); ?>">トピック一覧に戻る</a>

        </div>
    </section>

@endsection
