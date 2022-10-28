<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreObjectionRequest;
use App\Http\Requests\Update\UpdateObjectionRequest;
use App\Models\Objection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ObjectionController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


//    反論を登録する
    public function store(StoreObjectionRequest $request)
    {
        $validated = $request->validated();
        Objection::create($validated);
        return back()->with('info', '反論を登録しました。');
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


//    反論編集画面を表示
    public function edit(Objection $objection)
    {
        return view('objections.edit', ['objection' => $objection]);
    }


//    反論の更新処理
    public function update(UpdateObjectionRequest $request, Objection $objection)
    {
        $updateData = $request->validated();
        $objection->update($updateData);
        return to_route('topics.show', ['topic' => $objection->topic_id])->with('info', '反論を更新しました。');
    }


//    反論を非同期通信で削除する
    public function destroy(Request $request)
    {
        $objection = Objection::findOrFail($request->delete_id);
        $result = $objection->delete();
        return Response::json($result);
    }
}
