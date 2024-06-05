<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class AdminCategoryController extends Controller
{
    /**
     * 一覧画面表示
     */
    public function index(): Response
    {
      $category = Category::all();

      return inertia::render('EC/Admin/CategoryIndex', [
        "data" => $category,
      ]);
    }

    /**
     * 登録フォーム画面表示
     */
    public function create(): Response
    {
      $categoryData = Category::all();
      return Inertia::render('EC/Admin/CategoryRegister', [
          "data" => $categoryData,
      ]);
    }

    /**
     * 登録処理->DB
     */
    public function store(Request $request): RedirectResponse
    {
      $result = DB::transaction(function () use ($request) {
        $request->validate([
          "name" => "required|unique:categories,name",
          "description" => "required",
        ]);

        $result = Category::create([
          "name" => $request->name,
          "description" => $request->description,
        ]);
      });

      return redirect()->route('admin.category.create', $result);
    }

    /**
     * 編集フォーム画面表示
     */
    public function edit(Category $category, $id): Response
    {
      $category = Category::find($id);

      return inertia::render('EC/Admin/CategoryEdit', [
        "data" => $category,
      ]);
    }

    /**
     * 編集処理->DB
     */
    public function update(Request $request, Category $category, $id): RedirectResponse
    {
      $category = DB::transaction(function () use ($request, $category, $id) {
        $category = Category::find($id);

        $request->validate([
           "name" => "required|unique:categories,name,{$category->id}",
           "description" => "required",
        ]);

        $category->name = $request->name;
        $category->description = $request->description;

        if ($category->isDirty()) {
            $category->save();
        }
        return $category;
      });

      return redirect()->route('admin.category.edit', $category)->with('success', '更新しました');
    }

    /**
     * 削除処理->DB
     */
    public function destroy(Category $category, $id): RedirectResponse
    {
      DB::transaction(function () use ($category, $id) {
        $category = Category::find($id);
        $category->delete();
      });
      
      return redirect()->route('admin.category.index')->with('success', '削除しました');
    }
}
