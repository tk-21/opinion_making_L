<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreTopicRequest;
use App\Http\Requests\Update\UpdateTopicRequest;
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
    public function store(StoreTopicRequest $request)
    {
        $validated = $request->validated();
        Topic::create($validated);
        return to_route('index')->with('info', 'トピックを登録しました。');
    }


//    個別のトピック内容を表示
    public
    function show($id)
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
    public function update(UpdateTopicRequest $request, $id)
    {
        $topic = Topic::findOrFail($id);
        $updateData = $request->validated();
        $topic->update($updateData);

        return to_route('topics.show', $topic->id)->with('info', 'トピックを更新しました。');
    }


//    トピック削除処理
    public
    function destroy($id)
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
    public
    function updateStatus()
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
    public
    function confirmDelete()
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


