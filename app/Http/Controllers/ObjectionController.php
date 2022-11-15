<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreObjectionRequest;
use App\Http\Requests\Update\UpdateObjectionRequest;
use App\Models\Objection;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use function Symfony\Component\Translation\t;

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
        try {
            Objection::create($validated);
            return back()->with('info', '反論を登録しました。');
        } catch (Exception $e) {
            report($e);
            return back()->withErrors('反論の登録に失敗しました。')->withInput($validated);
        }
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
        try {
            DB::beginTransaction();
            $objection->update($updateData);
            DB::commit();
            return to_route('topics.show', ['topic' => $objection->topic_id])->with('info', '反論を更新しました。');
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors('反論の更新に失敗しました。')->withInput($updateData);
        }
    }


//    反論を非同期通信で削除する
    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();
            $objection = Objection::findOrFail($request->delete_id);
            $result = $objection->delete();
            DB::commit();
            return Response::json($result);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors('反論の削除に失敗しました。');
        }
    }
}
