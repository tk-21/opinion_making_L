<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopicController extends Controller
{
//    トピック一覧画面表示
    public function index()
    {
        //
    }


//    トピック作成画面を表示
    public function create()
    {
        $user = UserModel::getSession();
        $categories = CategoryQuery::fetchByUserId($user);

        // バリデーションに引っかかって登録に失敗した場合の処理
        // セッションに保存しておいた値を取ってきて変数に格納する。セッション上のデータは削除する
        // 必ずデータを取得した時点で、データを削除しておく必要がある。そうしないと他の記事を選択したときに出てきてしまう。
        $topic = TopicModel::getSessionAndFlush();

        // データが取れてこなかった場合、初期化して表示
        if (empty($topic)) {
            $topic = new TopicModel;
        }

        \view\topic\index($topic, $categories, SHOW_CREATE);

    }


//    トピック登録処理
    public function store(Request $request)
    {
        $topic = new TopicModel;

        // モデルに値をセット
        $topic->title = get_param('title', null);
        $topic->body = get_param('body', null);
        $topic->position = get_param('position', null);
        $topic->category_id = get_param('category_id', null);

        try {
            $validation = new TopicValidation($topic);

            // バリデーションに引っかかった場合
            if (!$validation->checkCreate()) {
                Msg::push(Msg::ERROR, 'トピックの登録に失敗しました。');
                // エラー時の値の復元のための処理
                // バリデーションに引っかかって登録に失敗した場合、入力した値を保持しておくため、セッションに保存する
                TopicModel::setSession($topic);

                // 元の画面に戻す
                redirect(GO_REFERER);
            }

            // バリデーションが問題なかった場合、バリデーションが完了しているデータを取ってくる
            $valid_data = $validation->getValidData();

            // セッションに格納されているユーザー情報のオブジェクトを取ってくる
            $user = UserModel::getSession();

            // オブジェクトを渡してクエリを実行
            TopicQuery::insert($valid_data, $user) ? Msg::push(Msg::INFO, 'トピックを登録しました。') : Msg::push(Msg::ERROR, '登録に失敗しました。');

            redirect(GO_HOME);
        } catch (Exception $e) {
            // エラー内容を出力する
            Msg::push(Msg::ERROR, $e->getMessage());

        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


//    トピック編集画面を表示
    public function edit($id)
    {
        // バリデーションに引っかかって登録に失敗した場合の処理
        // セッションに保存しておいた値を取ってきて変数に格納する。セッション上のデータは削除する
        // 必ずデータを取得した時点でデータを削除しておく必要がある。そうしないと他の記事を選択したときに出てきてしまう。
        $topic = TopicModel::getSessionAndFlush();

        $user = UserModel::getSession();
        $categories = CategoryQuery::fetchByUserId($user);


        // データが取れてくれば、その値を画面表示し、処理を終了
        if (!empty($topic)) {
            \view\topic\index($topic, $categories, SHOW_EDIT);
            return;
        }

        // データが取れてこなかった場合、TopicModelのインスタンスを作成して初期化
        $topic = new TopicModel;

        // GETリクエストから取得したidをモデルに格納
        $topic->id = get_param('id', null, false);

        // バリデーションが失敗した場合は、画面遷移させない
        $validation = new TopicValidation($topic);

        if (!$validation->validateId()) {
            redirect(GO_REFERER);
        };

        $valid_data = $validation->getValidData();

        // idからトピックの内容を取ってくる
        $fetchedTopic = TopicQuery::fetchById($valid_data);

        // トピックが取れてこなかったら４０４ページへリダイレクト
        if (!$fetchedTopic) {
            redirect('404');
            return;
        }

        // トピックが取れてきたら、トピックを渡してviewのindexを表示
        \view\topic\index($fetchedTopic, $categories, SHOW_EDIT);

    }


//    トピック更新処理
    public function update(Request $request, $id)
    {
        // TopicModelのインスタンスを作成
        $topic = new TopicModel;

        // POSTで渡ってきた値をモデルに格納
        $topic->id = get_param('id', null);
        $topic->title = get_param('title', null);
        $topic->body = get_param('body', null);
        $topic->position = get_param('position', null);
        $topic->category_id = get_param('category_id', null);

        // 更新処理
        try {
            $validation = new TopicValidation($topic);

            // バリデーションに引っかかった場合
            if (!$validation->checkEdit()) {
                Msg::push(Msg::ERROR, 'トピックの更新に失敗しました。');
                // エラー時の値の復元のための処理
                // バリデーションに引っかかって登録に失敗した場合、入力した値を保持しておくため、セッションに保存する
                TopicModel::setSession($topic);

                // 元の画面に戻す
                redirect(GO_REFERER);
            }

            $valid_data = $validation->getValidData();

            // バリデーションに問題なかった場合、オブジェクトを渡してクエリを実行
            TopicQuery::update($valid_data) ? Msg::push(Msg::INFO, 'トピックを更新しました。') : Msg::push(Msg::ERROR, '更新に失敗しました。');

            redirect(sprintf('detail?id=%d', $topic->id));
        } catch (Exception $e) {
            // エラー内容を出力する
            Msg::push(Msg::ERROR, $e->getMessage());
        }

    }


//    トピック削除処理
    public function destroy($id)
    {
        $topic = new TopicModel;
        $topic->id = get_param('topic_id', null);

        $validation = new TopicValidation($topic);

        if (!$validation->validateId()) {
            redirect(GO_REFERER);
        };

        $valid_data = $validation->getValidData();

        TopicQuery::delete($valid_data) ? Msg::push(Msg::INFO, 'トピックを削除しました。') : Msg::push(Msg::ERROR, '削除に失敗しました。');

        redirect(GO_HOME);

    }
}
