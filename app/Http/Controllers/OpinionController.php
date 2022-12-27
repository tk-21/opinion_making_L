<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreOpinionRequest;
use App\Http\Requests\Update\UpdateOpinionRequest;
use App\Models\Opinion;
use App\Models\Topic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OpinionController extends Controller
{
    public function create(Topic $topic)
    {
        return view('opinions.create', ['topic' => $topic]);
    }


    public function store(StoreOpinionRequest $request)
    {
        $validated = $request->validated();
        Log::debug($validated);
        try {
            Opinion::create($validated);
            Log::debug('success');
            return to_route('topics.show', ['topic' => $validated['topic_id']])->with('info', '意見を作成しました。');
        } catch (Exception $e) {
            report($e);
            Log::debug('fail');
            return back()->withErrors('意見の作成に失敗しました。')->withInput($validated);
        }
    }


    public function edit(Opinion $opinion)
    {
        $user_id = $opinion->topic()->value('user_id');
        if ($user_id !== Auth::id()) {
            abort(404);
        }
        return view('opinions.edit', ['opinion' => $opinion]);
    }


    public function update(UpdateOpinionRequest $request, Opinion $opinion)
    {
        $updateData = $request->validated();
        Log::debug($updateData);
        try {
            DB::beginTransaction();
            $opinion->update($updateData);
            DB::commit();
            Log::debug('success');
            return to_route('topics.show', ['topic' => $opinion->topic_id])->with('info', '意見を更新しました。');
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            Log::debug('fail');
            return back()->withErrors('意見の更新に失敗しました。')->withInput($updateData);
        }

    }
}
