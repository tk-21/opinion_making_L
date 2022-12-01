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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class TopicController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $topics = $user->topics()->orderBy('created_at', 'desc')->paginate(5);
        $categories = $user->categories()->orderBy('created_at', 'desc')->get();
        return view('index', compact('topics', 'categories'));
    }


    public function create()
    {
        $user = Auth::user();
        $categories = $user->categories()->orderBy('created_at', 'desc')->get();
        return view('topics.create', compact('user', 'categories'));
    }


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


    public function show(Topic $topic)
    {
        try {
            $objections = $topic->objections()->orderBy('created_at', 'asc')->get();
            $counterObjections = $topic->counterObjections()->orderBy('created_at', 'asc')->get();
            $opinion = $topic->opinions()->first();
            return view('topics.show', compact('topic', 'objections', 'counterObjections', 'opinion'));
        } catch (Exception $e) {
            report($e);
            return back()->withErrors('トピックの表示に失敗しました。');
        }
    }


    public function edit(Topic $topic)
    {
        $user = Auth::user();
        $categories = $user->categories()->orderBy('created_at', 'desc')->get();
        return view('topics.edit', compact('topic', 'categories'));
    }


    public function update(UpdateTopicRequest $request, Topic $topic)
    {
        $updateData = $request->validated();
        try {
            DB::beginTransaction();
            $topic->update($updateData);
            DB::commit();
            return to_route('topics.show', ['topic' => $topic])->with('info', 'トピックを更新しました。');
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors('トピックの更新に失敗しました。')->withInput($updateData);
        }
    }


// 削除確認画面を表示する
    public function confirmDelete(Topic $topic)
    {
        return view('topics.delete', ['topic' => $topic]);
    }


    public function destroy(Topic $topic)
    {
        try {
            DB::beginTransaction();
            $topic->delete();
            DB::commit();
            return to_route('index')->with('info', 'トピックを削除しました。');
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors('トピックの削除に失敗しました。');
        }
    }

}


