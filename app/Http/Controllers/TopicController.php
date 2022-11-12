<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreTopicRequest;
use App\Http\Requests\Update\UpdateTopicRequest;
use App\Models\Category;
use App\Models\CounterObjection;
use App\Models\Objection;
use App\Models\Opinion;
use App\Models\Topic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class TopicController extends Controller
{
//    トピック一覧画面表示
    public function index()
    {
        $user_id = Auth::id();
        $topics = Topic::where('user_id', $user_id)->orderBy('created_at', 'desc')->paginate(5);
        $categories = Category::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
        return view('index', compact('topics', 'categories'));
    }


//    トピック作成画面を表示
    public function create()
    {
        $user_id = Auth::id();
        $categories = Category::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
        return view('topics.create', compact('user_id', 'categories'));
    }


//    トピック登録処理
    public function store(StoreTopicRequest $request)
    {
        $validated = $request->validated();
        try {
            Topic::create($validated);
            return to_route('index')->with('info', 'トピックを登録しました。');
        } catch (Exception $e) {
            report($e);
            return back()->withErrors('トピックの登録に失敗しました。')->withInput($validated);
        }
    }


//    トピック詳細画面を表示
    public function show(Topic $topic)
    {
        $objections = Objection::where('topic_id', $topic->id)->orderBy('created_at', 'asc')->get();
        $counterObjections = CounterObjection::where('topic_id', $topic->id)->orderBy('created_at', 'asc')->get();
        $opinion = Opinion::where('topic_id', $topic->id)->first();
        return view('topics.show', compact('topic', 'objections', 'counterObjections', 'opinion'));
    }


//    トピック編集画面を表示
    public function edit(Topic $topic)
    {
        $user = Auth::user();
        $categories = Category::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return view('topics.edit', compact('topic', 'categories'));
    }


//    トピック更新処理
    public function update(UpdateTopicRequest $request, Topic $topic)
    {
        $updateData = $request->validated();
        $topic->update($updateData);
        return to_route('topics.show', ['topic' => $topic])->with('info', 'トピックを更新しました。');
    }


// 削除確認画面を表示する
    public function confirmDelete(Topic $topic)
    {
        return view('topics.delete', ['topic' => $topic]);
    }


//    トピック削除処理
    public function destroy(Topic $topic)
    {
        $topic->delete();
        return to_route('index')->with('info', 'トピックを削除しました。');
    }

}


