<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
//    トピック一覧画面表示
    public function index()
    {
        $user = Auth::user();
        $topics = Topic::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
        $categories = Category::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return view('index', compact('topics', 'categories'));
    }


//    トピック作成画面を表示
    public function create()
    {
        $user = Auth::user();
        $categories = Category::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return view('topics.create', compact('categories'));
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


//    個別のトピック内容を表示
    public function show($id)
    {
        $topic = new TopicModel;

        // $_GET['id']から値を取ってくる
        // getで値を取るときは第３引数をfalseに
        $topic->id = get_param('id', null, false);

        // idに該当するトピックを１件取ってくる
        $fetchedTopic = TopicQuery::fetchById($topic);

        // トピックが取れてこなかった場合、または削除されている場合は４０４ページにリダイレクト
        if (empty($fetchedTopic) || isset($fetchedTopic->deleted_at)) {
            Msg::push(Msg::ERROR, 'トピックが見つかりません。');
            redirect('404');
        }

        // topic_idが格納されたtopicオブジェクトを渡し、そのtopic_idに紐付く反論、意見を取ってくる
        $objections = ObjectionQuery::fetchByTopicId($topic);
        $counterObjections = CounterObjectionQuery::fetchByTopicId($topic);
        $opinion = OpinionQuery::fetchByTopicId($topic);

        // 取れてきたものをviewに渡して表示
        \view\detail\index($fetchedTopic, $objections, $counterObjections, $opinion);
    }


//    トピック編集画面を表示
    public function edit(Topic $topic)
    {
        $user = Auth::user();
        $categories = Category::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return view('topics.edit', compact('topic', 'categories'));
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


//    完了、未完了を切り替える
    public function updateStatus()
    {
        $topic = new TopicModel;

        $topic->id = get_param('topic_id', null);
        $topic_status = get_param('topic_status', null);

        // 反転させる
        $topic->complete_flg = ($topic_status == '完了') ? '0' : '1';

        $is_success = TopicQuery::updateStatus($topic);

        echo $is_success;
    }


    // 削除確認画面を表示する
    public function confirmDelete()
    {
        $topic = new TopicModel;
        $topic->id = get_param('id', null, false);

        $validation = new TopicValidation($topic);

        if (!$validation->validateId()) {
            redirect(GO_REFERER);
        };

        $valid_data = $validation->getValidData();

        // idからトピックの内容を取ってくる
        $fetchedTopic = TopicQuery::fetchById($valid_data);

        // 削除確認画面を表示
        \view\topic_delete\index($fetchedTopic);
    }

}


