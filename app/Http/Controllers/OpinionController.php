<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreOpinionRequest;
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
    public function update(Request $request, $id)
    {
        $opinion = new OpinionModel;

        $opinion->id = get_param('id', null);
        $opinion->opinion = get_param('opinion', null);
        $opinion->reason = get_param('reason', null);
        $opinion->topic_id = get_param('id', null, false);

        try {
            $validation = new OpinionValidation($opinion);

            if (!$validation->checkEdit()) {
                Msg::push(Msg::ERROR, '意見の更新に失敗しました。');
                OpinionModel::setSession($opinion);
                redirect(GO_REFERER);
            }

            $valid_data = $validation->getValidData();

            OpinionQuery::update($valid_data) ? Msg::push(Msg::INFO, '意見を更新しました。') : Msg::push(Msg::ERROR, '更新に失敗しました。');

            redirect(sprintf('detail?id=%s', $opinion->topic_id));
        } catch (Exception $e) {
            Msg::push(Msg::ERROR, $e->getMessage());
        }

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
