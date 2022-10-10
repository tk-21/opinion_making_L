@extends('layouts.default')
@section('content')
    {{--
        $opinion = escape($opinion);
        $topic = escape($topic);

        // 意見作成、編集を兼ねているファイル
        // 意見作成か、意見編集かによって表示内容を変える
        $header_ttl = $is_create ? '最終意見の言語化' : '意見の編集';
        $submit_btn = $is_create ? '登録' : '更新';
    --}}

    <section class="opinion">
        <div class="inner">
            <form class="opinion-form validate-form" action="" method="POST" novalidate>
                <input type="hidden" name="id" value="<?php echo $opinion->id; ?>">

                <h2 class="opinion-ttl"><?php echo $header_ttl; ?></h2>

                <dl class="opinion-list">
                    <dt class="opinion-dttl"><label for="opinion" onclick="">自分の意見</label></dt>
                    <dd class="opinion-item">
                        <input type="text" id="opinion" name="opinion" value="<?php echo $opinion->opinion; ?>"
                               class="opinion-input input validate-target" autofocus required>
                        <p class="invalid-feedback"></p>
                    </dd>

                    <dt class="opinion-dttl"><label for="reason" onclick="">その理由</label></dt>
                    <dd class="opinion-item">
                        <textarea id="reason" name="reason" class="opinion-textarea input validate-target" autofocus
                                  required><?php echo $opinion->reason; ?></textarea>
                        <p class="invalid-feedback"></p>
                    </dd>
                </dl>

                <button type="submit" class="register-btn"><?php echo $submit_btn; ?></button>

                <a class="back-btn _back" href="<?php the_url(sprintf('detail?id=%d', $topic->id)); ?>">戻る</a>

            </form>
        </div>
    </section>

@endsection
