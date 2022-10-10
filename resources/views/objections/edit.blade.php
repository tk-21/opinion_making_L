@extends('layouts.default')
@section('content')

    {{--
        $objection = escape($objection);

        $label = $type == 'objection' ? '反論の編集' : '反論への反論の編集';
    --}}

    <section class="edit">
        <div class="edit-inner">
            <form class="edit-form validate-form" action="" method="POST" novalidate>
                <input type="hidden" name="id" value="<?php echo $objection->id; ?>">
                <input type="hidden" name="topic_id" value="<?php echo $objection->topic_id; ?>">
                <input type="hidden" name="type" value="<?php echo $type; ?>">

                <dl class="edit-list">

                    <dt class="edit-dttl"><label for="body" onclick=""><?php echo $label; ?></label></dt>
                    <dd class="edit-item">
                        <textarea id="body" name="body" class="edit-body input validate-target" autofocus
                                  required><?php echo $objection->body; ?></textarea>
                        <p class="invalid-feedback"></p>
                    </dd>

                </dl>

                <button type="submit" class="register-btn">更新</button>

                <a class="back-btn _back"
                   href="<?php the_url(sprintf('detail?id=%d', $objection->topic_id)); ?>">戻る</a>

            </form>
        </div>
    </section>
@endsection
