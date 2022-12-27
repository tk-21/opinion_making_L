<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreCategoryRequest;
use App\Http\Requests\Update\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        Log::debug($validated);
        $user = Auth::user();
        try {
            $user->categories()->create([
                'name' => $validated['name']
            ]);
            Log::debug('success');
            return to_route('index')->with('info', 'カテゴリーを作成しました。');
        } catch (Exception $e) {
            report($e);
            Log::debug('fail');
            return back()->withErrors('カテゴリーの作成に失敗しました。');
        }
    }


    public function show(Category $category)
    {
        if ($category['user_id'] !== Auth::id()) {
            abort(404);
        }
        $topics = $category->topics()->orderBy('created_at', 'desc')->paginate(5);
        $user = Auth::user();
        $categories = $user->categories()->orderBy('created_at', 'desc')->get();
        return view('categories.index', compact('category', 'topics', 'categories'));
    }


    public function edit(Category $category)
    {
        if ($category['user_id'] !== Auth::id()) {
            abort(404);
        }
        return view('categories.edit', ['category' => $category]);
    }


    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $updateData = $request->validated();
        Log::debug($updateData);
        try {
            DB::beginTransaction();
            $category->update($updateData);
            DB::commit();
            Log::debug('success');
            return to_route('categories.show', ['category' => $category])->with('info', 'カテゴリーを更新しました。');
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            Log::debug('fail');
            return back()->withErrors('カテゴリーの更新に失敗しました。')->withInput($updateData);
        }
    }


    // 削除確認画面を表示する
    public function confirmDelete(Category $category)
    {
        return view('categories.delete', ['category' => $category]);
    }


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
