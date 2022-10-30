<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreOpinionRequest;
use App\Http\Requests\Update\UpdateOpinionRequest;
use App\Models\Opinion;
use App\Models\Topic;
use Illuminate\Http\Request;

class OpinionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


//    意見作成画面を表示
    public function create(Topic $topic)
    {
        return view('opinions.create', ['topic' => $topic]);
    }


//    意見の登録処理
    public function store(StoreOpinionRequest $request)
    {
        $validated = $request->validated();
        Opinion::create($validated);
        return to_route('topics.show', ['topic' => $validated['topic_id']])->with('info', '意見を作成しました。');
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


//    意見の編集画面を表示
    public function edit(Opinion $opinion)
    {
        return view('opinions.edit', ['opinion' => $opinion]);
    }


//    意見の更新処理
    public function update(UpdateOpinionRequest $request, Opinion $opinion)
    {
        $updateData = $request->validated();
        $opinion->update($updateData);
        return to_route('topics.show', ['topic' => $opinion->topic_id])->with('info', '意見を更新しました。');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
