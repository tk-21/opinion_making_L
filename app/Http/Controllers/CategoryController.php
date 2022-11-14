<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreCategoryRequest;
use App\Http\Requests\Update\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Topic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
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


//    カテゴリー作成
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
//        ログイン中ユーザーのIDを取得
        $validated['user_id'] = Auth::id();
        try {
            Category::create($validated);
            return to_route('index')->with('info', 'カテゴリーを作成しました。');
        } catch (Exception $e) {
            report($e);
            return back()->withErrors('カテゴリーの作成に失敗しました。');
        }
    }


    // カテゴリーに紐付くトピックスを表示する
    public function show(Category $category)
    {
        $topics = $category->topics()->orderBy('created_at', 'desc')->paginate(5);
        $categories = Category::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('categories.index', compact('category', 'topics', 'categories'));
    }


//    カテゴリー編集画面表示
    public function edit(Category $category)
    {
        return view('categories.edit', ['category' => $category]);
    }


//    カテゴリー更新処理
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $updateData = $request->validated();
        try {
            DB::beginTransaction();
            $category->update($updateData);
            DB::commit();
            return to_route('categories.show', ['category' => $category])->with('info', 'カテゴリーを更新しました。');
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors('カテゴリーの更新に失敗しました。')->withInput($updateData);
        }
    }


    // 削除確認画面を表示する
    public function confirmDelete(Category $category)
    {
        return view('categories.delete', ['category' => $category]);
    }


//    カテゴリー削除処理
    public function destroy(Category $category)
    {
        try {
            DB::beginTransaction();
            $category->delete();
            DB::commit();
            return to_route('index')->with('info', 'カテゴリーを削除しました。');
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors('カテゴリーの削除に失敗しました。');
        }
    }
}
