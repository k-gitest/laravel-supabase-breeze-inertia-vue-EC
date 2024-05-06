<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    /**
     * 一覧画面表示
     */
    public function index()
    {
        //
      $category = Category::all();

      return inertia::render('EC/CategoryIndex', [
        "data" => $category,
      ]);
    }

    /**
     * 登録フォーム画面表示
     */
    public function create(): Response
    {
        //
      $categoryData = Category::all();
      return Inertia::render('EC/CategoryRegister', [
          "data" => $categoryData,
      ]);
    }

    /**
     * 登録処理->DB
     */
    public function store(Request $request): RedirectResponse
    {
        //
      $request->validate([
        "name" => "required|unique:categories,name",
        "description" => "required",
      ]);
      
      $result = Category::create([
        "name" => $request->name,
        "description" => $request->description,
      ]);

      return redirect()->route('category.create', $result);
    }

    /**
     * 特定IDデータの画面表示
     */
    public function show(Category $category, Request $request, $id)
    {
        //
      $result = Category::with(['product.category'])->find($id);
      
      if($result){
        $data = $result->product;
        $categoryName = $result->name;
      } else {
        return redirect("/");
      }

      return Inertia::render('EC/CategoryProductList', [
          "data" => $result,
          "category_name" => $categoryName,
      ]);
    }

    /**
     * 編集フォーム画面表示
     */
    public function edit(Category $category, $id)
    {
        //
      $category = Category::find($id);

      return inertia::render('EC/CategoryEdit', [
        "data" => $category,
      ]);
    }

    /**
     * 編集処理->DB
     */
    public function update(Request $request, Category $category, $id)
    {
        //
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
      
      return redirect()->route('category.edit', $category)->with('success', '更新しました');
    }

    /**
     * 削除処理->DB
     */
    public function destroy(Category $category, $id)
    {
        //
      $category = Category::find($id);
      $category->delete();
      return redirect()->route('category.index')->with('success', '削除しました');
    }
}
