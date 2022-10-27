<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreCategoryRequest;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
//    カテゴリー一覧表示
    public function index()
    {
        $user_id = Auth::id();
        $categories = Category::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
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


//    カテゴリー作成
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        Category::create($validated);
        return to_route('index')->with('info', 'カテゴリーを作成しました。');
    }


    // カテゴリーに紐付くトピックスを表示する
    public function show(Category $category)
    {
        $topics = Topic::where('category_id', $category->id)->orderBy('created_at', 'asc')->get();
        $categories = Category::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('categories.index', compact('category', 'topics', 'categories'));
    }


//    カテゴリー編集画面表示
    public function edit(Category $category)
    {
        return view('categories.edit', ['category' => $category]);
    }


//    カテゴリー更新処理
    public function update(Request $request, $id)
    {
        $category = new CategoryModel;

        $category->id = get_param('id', null);
        $category->name = get_param('name', null);

        // 更新処理
        try {
            $validation = new CategoryValidation($category);

            // バリデーションに引っかかった場合
            if (!($validation->validateId() * $validation->validateName())) {
                Msg::push(Msg::ERROR, 'カテゴリーの更新に失敗しました。');
                // エラー時の値の復元のための処理
                // バリデーションに引っかかって登録に失敗した場合、入力した値を保持しておくため、セッションに保存する
                CategoryModel::setSession($category);

                // 元の画面に戻す
                redirect(GO_REFERER);
            }

            $valid_data = $validation->getValidData();

            // バリデーションに問題なかった場合、オブジェクトを渡してクエリを実行
            CategoryQuery::update($valid_data) ? Msg::push(Msg::INFO, 'カテゴリーを更新しました。') : Msg::push(Msg::ERROR, '更新に失敗しました。');

            redirect(sprintf('category?id=%d', $category->id));
        } catch (Exception $e) {
            // エラー内容を出力する
            Msg::push(Msg::ERROR, $e->getMessage());
        }

    }


    // 削除確認画面を表示する
    public function confirmDelete()
    {
        $category = new CategoryModel;
        $category->id = get_param('id', null, false);

        $validation = new CategoryValidation($category);

        if (!$validation->validateId()) {
            redirect(GO_REFERER);
        };

        $valid_data = $validation->getValidData();

        // idからトピックの内容を取ってくる
        $fetchedCategory = CategoryQuery::fetchById($valid_data);

        // 削除確認画面を表示
        \view\category_delete\index($fetchedCategory);
    }


//    カテゴリー削除処理
    public function destroy($id)
    {
        $category = new CategoryModel;
        $category->id = get_param('category_id', null);

        $validation = new CategoryValidation($category);

        if (!$validation->validateId()) {
            redirect(GO_REFERER);
        };

        $valid_data = $validation->getValidData();

        CategoryQuery::delete($valid_data) ? Msg::push(Msg::INFO, 'カテゴリーを削除しました。') : Msg::push(Msg::ERROR, '削除に失敗しました。');

        redirect(GO_HOME);

    }
}
