<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * 一覧画面表示
     */
    public function index(): Response
    {
      $category = Category::all();

      return inertia::render('EC/CategoryIndex', [
        "data" => $category,
      ]);
    }

    /**
     * 特定IDデータの画面表示
     */
    public function show(Category $category, Request $request, $id): Response
    {
      $result = Category::with(['product.image', 'product.category'])->find($id);
      
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

}
