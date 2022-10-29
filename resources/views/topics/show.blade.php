@extends('layouts.default')
@section('content')

    @php
        $is_edit = $opinion ? '編集' : '作成';
        $route_name = $opinion ? 'opinions.edit' : 'opinions.create';
        $param = $opinion ? compact('opinion') : compact('topic');
    @endphp

    <section class="detail">
        <div class="detail-inner">

            <ul class="detail-list">
                <li class="detail-item">
                    <dl class="detail-topic">
                        <dt class="detail-topic-ttl">タイトル</dt>
                        <dd class="detail-topic-data">{{ $topic->title }}</dd>
                        <dt class="detail-topic-ttl">内容</dt>
                        <dd class="detail-topic-data">{{ $topic->body }}</dd>
                        <dt class="detail-topic-ttl">ポジション</dt>
                        <dd class="detail-topic-data">{{ $topic->position }}</dd>
                        <dt class="detail-topic-ttl">ステータス</dt>
                        <dd class="detail-topic-data">{{ $topic->status ? '完了' : '未完了' }}</dd>
                        <dt class="detail-topic-ttl">カテゴリー</dt>
                        <dd class="detail-topic-data">{{ $topic->category->name ?? '未選択' }}</dd>
                    </dl>

                    <a class="edit-btn" href="{{ route('topics.edit', ['topic' => $topic]) }}">編集</a>
                    <a class="delete-btn" href="{{ route('topics.confirmDelete', ['topic' => $topic]) }}">削除</a>
                </li>

                <li class="detail-item">
                    <div class="objection">
                        <p class="objection-ttl">意見に対する反論</p>

                        <form class="objection-form validate-form" action="{{ route('objections.store') }}"
                              method="post">
                            @csrf
                            <input type="hidden" id="topic_id" name="topic_id" value="{{ $topic->id }}">
                            <textarea id="objection" class="objection-textarea input validate-target" name="body"
                                      required>{{ old('body') }}</textarea>
                            <button type="submit" id="objection_register" class="register-btn">登録</button>
                        </form>

                        <ol class="objection-list">
                            @php $objection_num = 1; @endphp
                            @foreach ($objections as $objection)
                                <li class="objection-item">
                                    <div class="objection-txt-wrapper">
                                        <p class="objection-txt">
                                            {{ sprintf('%d . %s', $objection_num, $objection->body) }}
                                        </p>
                                        @php $objection_num++; @endphp
                                    </div>

                                    <div class="objection-btn">
                                        <a class="objection-edit"
                                           href="{{ route('objections.edit', ['objection' => $objection]) }}"><img
                                                src="/img/edit.svg" alt="編集"></a>

                                        <button type="submit" class="objection-delete"
                                                data-id="{{ $objection->id }}" data-type="objection"><img
                                                src="/img/delete.svg" alt="削除"></button>
                                    </div>
                                </li>
                            @endforeach
                        </ol>

                    </div>
                </li>

                <li class="detail-item">
                    <div class="objection">
                        <p class="objection-ttl">反論への反論</p>

                        <form class="objection-form validate-form" action="{{ route('counterObjections.store') }}"
                              method="post">
                            @csrf
                            <input type="hidden" name="topic_id" value="{{ $topic->id }}">
                            <textarea class="objection-textarea input validate-target" name="body"
                                      required>{{ old('body') }}</textarea>
                            <button type="submit" class="register-btn">登録</button>
                        </form>

                        <ol class="objection-list">
                            @php $counterObjection_num = 1; @endphp
                            @foreach ($counterObjections as $counterObjection)
                                <li class="objection-item">
                                    <div class="objection-txt-wrapper">
                                        <p class="objection-txt">
                                            {{ sprintf('%d . %s', $counterObjection_num, $counterObjection->body) }}
                                        </p>
                                        @php $counterObjection_num++; @endphp
                                    </div>

                                    <div class="objection-btn">
                                        <a class="objection-edit"
                                           href="{{ route('counterObjections.edit', ['counterObjection' => $counterObjection]) }}"><img
                                                src="/img/edit.svg" alt="編集"></a>

                                        <button type="submit" class="objection-delete"
                                                data-id="{{ $counterObjection->id }}"
                                                data-type="counterObjection">
                                            <img src="/img/delete.svg" alt="削除"></button>
                                    </div>
                                </li>
                            @endforeach
                        </ol>

                    </div>
                </li>
            </ul>

            <dl class="detail-opinion">
                <dt class="detail-opinion-ttl">最終的な意見：</dt>
                <dd class="detail-opinion-data">{{ $opinion->opinion ?? '' }}</dd>
                <dt class="detail-opinion-ttl">その理由：</dt>
                <dd class="detail-opinion-data">{{ $opinion->reason ?? '' }}</dd>
                <a class="edit-btn" href="{{ route($route_name, $param) }}">{{ $is_edit }}</a>
            </dl>

            <a class="back-btn _home" href="{{ route('index') }}">トピック一覧に戻る</a>

        </div>
    </section>

@endsection
