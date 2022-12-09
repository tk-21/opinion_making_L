<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreObjectionRequest;
use App\Http\Requests\Update\UpdateObjectionRequest;
use App\Models\CounterObjection;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CounterObjectionController extends Controller
{
    public function store(StoreObjectionRequest $request)
    {
        $validated = $request->validated();
        try {
            CounterObjection::create($validated);
            return back()->with('info', '反論への反論を登録しました。');
        } catch (Exception $e) {
            report($e);
            return back()->withErrors('反論への反論の登録に失敗しました。')->withInput($validated);
        }
    }


    public function edit(CounterObjection $counterObjection)
    {
        $user_id = $counterObjection->topic()->value('user_id');
        if ($user_id !== Auth::id()) {
            abort(404);
        }
        return view('counter_objections.edit', ['counterObjection' => $counterObjection]);
    }


    public function update(UpdateObjectionRequest $request, CounterObjection $counterObjection)
    {
        $updateData = $request->validated();
        try {
            DB::beginTransaction();
            $counterObjection->update($updateData);
            DB::commit();
            return to_route('topics.show', ['topic' => $counterObjection->topic_id])->with('info', '反論への反論を更新しました。');
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors('反論への反論の更新に失敗しました。')->withInput($updateData);

        }
    }


//    反論への反論を非同期通信で削除する
    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();
            $counterObjection = CounterObjection::findOrFail($request->delete_id);
            $result = $counterObjection->delete();
            DB::commit();
            return Response::json($result);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors('反論への反論の削除に失敗しました。');
        }

    }
}
