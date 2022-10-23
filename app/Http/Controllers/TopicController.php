<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreTopicRequest;
use App\Http\Requests\Update\UpdateTopicRequest;
use App\Models\Category;
use App\Models\CounterObjection;
use App\Models\Objection;
use App\Models\Opinion;
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
        return view('topics.create', compact('user', 'categories'));
    }


//    トピック登録処理
    public function store(StoreTopicRequest $request)
    {
        $validated = $request->validated();
        Topic::create($validated);
        return to_route('index')->with('info', 'トピックを登録しました。');
    }


//    個別のトピック内容を表示
    public function show($id)
    {
        $topic = Topic::findOrFail($id);
        $objections = Objection::where('topic_id', $topic->id)->orderBy('created_at', 'asc')->get();
        $counterObjections = CounterObjection::where('topic_id', $topic->id)->orderBy('created_at', 'asc')->get();
        $opinion = Opinion::where('topic_id', $topic->id)->first();

        return view('topics.index', compact('topic', 'objections', 'counterObjections', 'opinion'));
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
    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        $topic->delete();

        return to_route('index')->with('info', 'トピックを削除しました。');
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
    public function confirmDelete(Topic $topic)
    {
        return view('topics.delete', ['topic' => $topic]);
    }

}


