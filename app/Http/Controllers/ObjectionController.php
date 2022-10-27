<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreObjectionRequest;
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


//    「反論」または「反論への反論」編集画面を表示
    public function edit($id)
    {
        $type = get_param('type', null, false);

        // バリデーションに引っかかって登録に失敗した場合の処理
        // セッションに保存しておいた値を取ってきて変数に格納する。セッション上のデータは削除する
        // 必ずデータを取得した時点でデータを削除しておく必要がある。そうしないと他の記事を選択したときに出てきてしまう。
        $objection = ObjectionModel::getSessionAndFlush();

        // データが取れてくれば、その値を画面表示し、処理を終了
        if (!empty($objection)) {
            \view\objection_edit\index($objection, $type);
            return;
        }

        // データが取れてこなかった場合、TopicModelのインスタンスを作成して初期化
        $objection = new ObjectionModel;

        // GETリクエストから取得したidをモデルに格納
        $objection->id = get_param('id', null, false);

        // バリデーションが失敗した場合は、画面遷移させない
        $validation = new ObjectionValidation($objection);

        if (!$validation->validateId()) {
            redirect(GO_REFERER);
        };

        $valid_data = $validation->getValidData();

        if ($type === 'objection') {
            // idから反論を取ってくる
            $fetchedObjection = ObjectionQuery::fetchById($valid_data);
        } elseif ($type === 'counterObjection') {
            // idから反論を取ってくる
            $fetchedObjection = counterObjectionQuery::fetchById($valid_data);
        } else {
            redirect(GO_REFERER);
        }

        // トピックが取れてこなかったら４０４ページへリダイレクト
        if (!$fetchedObjection) {
            redirect('404');
            return;
        }

        // 取れてきた反論を渡してviewのindexを表示
        \view\objection_edit\index($fetchedObjection, $type);

        return;

    }


//    「反論」または「反論への反論」の更新処理
    public function update(Request $request, $id)
    {
        $objection = new ObjectionModel;

        $objection->id = get_param('id', null);
        $objection->body = get_param('body', null);
        $objection->topic_id = get_param('topic_id', null);

        // 更新処理
        try {
            $validation = new ObjectionValidation($objection);

            // バリデーションに引っかかった場合
            if (!($validation->validateId() * $validation->validateBody())) {
                Msg::push(Msg::ERROR, '更新に失敗しました。');
                // エラー時の値の復元のための処理
                // バリデーションに引っかかって登録に失敗した場合、入力した値を保持しておくため、セッションに保存する
                ObjectionModel::setSession($objection);

                // 元の画面に戻す
                redirect(GO_REFERER);
            }

            $valid_data = $validation->getValidData();

            $type = get_param('type', null);

            // バリデーションに問題なかった場合、オブジェクトを渡してクエリを実行
            if ($type === 'objection') {
                ObjectionQuery::update($valid_data) ? Msg::push(Msg::INFO, '反論を更新しました。') : Msg::push(Msg::ERROR, '更新に失敗しました。');

                redirect(sprintf('detail?id=%d', $objection->topic_id));
            }

            if ($type === 'counterObjection') {
                counterObjectionQuery::update($valid_data) ? Msg::push(Msg::INFO, '反論への反論を更新しました。') : Msg::push(Msg::ERROR, '更新に失敗しました。');

                redirect(sprintf('detail?id=%d', $objection->topic_id));
            }
        } catch (Exception $e) {
            // エラー内容を出力する
            Msg::push(Msg::ERROR, $e->getMessage());
        }

    }


//    反論を非同期通信で削除する
    public function destroy(Objection $objection)
    {
        $result = $objection->delete();
        return Response::json($result);
    }
}
