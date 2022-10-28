<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreObjectionRequest;
use App\Http\Requests\Update\UpdateObjectionRequest;
use App\Models\CounterObjection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CounterObjectionController extends Controller
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


//    反論への反論を登録する
    public function store(StoreObjectionRequest $request)
    {
        $validated = $request->validated();
        CounterObjection::create($validated);
        return back()->with('info', '反論への反論を登録しました。');
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


//    反論への反論編集画面を表示
    public function edit(CounterObjection $counterObjection)
    {
        return view('counterObjections.edit', ['counterObjection' => $counterObjection]);
    }


//    反論への反論の更新処理
    public function update(UpdateObjectionRequest $request, CounterObjection $counterObjection)
    {
        $updateData = $request->validated();
        $counterObjection->update($updateData);
        return to_route('topics.show', ['topic' => $counterObjection->topic_id])->with('info', '反論への反論を更新しました。');
    }


//    反論への反論を非同期通信で削除する
    public function destroy(Request $request)
    {
        $counterObjection = CounterObjection::findOrFail($request->delete_id);
        $result = $counterObjection->delete();
        return Response::json($result);
    }
}
