<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class StatusController extends Controller
{
    //    完了、未完了を切り替える
    public function update(Request $request)
    {
        $topic = Topic::findOrFail($request->topic_id);

        // 反転させる
        $status = Topic::reverseStatus($request->topic_status);

        $result = $topic->update([
            'status' => $status
        ]);

        return Response::json($result);
    }


}
