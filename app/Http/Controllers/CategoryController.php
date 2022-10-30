<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreCategoryRequest;
use App\Http\Requests\Update\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
//    カテゴリー一覧表示
    public function index()
    {
//        $user_id = Auth::id();
//        $categories = Category::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
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
        $topics = Topic::where('category_id', $category->id)->orderBy('created_at', 'desc')->paginate(5);
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
        $category->update($updateData);
        return to_route('categories.show', ['category' => $category])->with('info', 'カテゴリーを更新しました。');
    }


    // 削除確認画面を表示する
    public function confirmDelete(Category $category)
    {
        return view('categories.delete', ['category' => $category]);
    }


//    カテゴリー削除処理
    public function destroy(Category $category)
    {
        $category->delete();
        return to_route('index')->with('info', 'カテゴリーを削除しました。');
    }
}
