<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminCategoryRequest;
use App\Services\Admin\AdminCategoryService;
use App\Models\Category;
use App\Models\Product;
use Log;

class AdminCategoryController extends Controller
{  
    protected $adminCategoryService;

    public function __construct(AdminCategoryService $adminCategoryService)
    {
        $this->adminCategoryService = $adminCategoryService;
    }
  
    /**
     * 一覧画面表示
     */
    public function index(): Response
    {
      $category = $this->adminCategoryService->getAllCategories();

      return inertia::render('EC/Admin/CategoryIndex', [
          "data" => $category,
      ]);
    }

    /**
     * 登録フォーム画面表示
     */
    public function create(): Response
    {
      $categoryData = $this->adminCategoryService->getAllCategories();
        
      return Inertia::render('EC/Admin/CategoryRegister', [
          "data" => $categoryData,
      ]);
    }

    /**
     * 登録処理->DB
     */
    public function store(AdminCategoryRequest $request): RedirectResponse
    {
      try {
          $this->adminCategoryService->createCategory($request);
          Log::info('Category create succeeded');
      } catch (\Exception $e) {
          report($e);
          return false;
      }

      return redirect()->back()->with('sucess', 'カテゴリー登録が成功しました');
    }

    /**
     * 編集フォーム画面表示
     */
    public function edit(Request $request): Response|bool
    {
        $request->validate([
              'id' => 'required|string|exists:categories,id',
        ]);
    
        try {
              $category = $this->adminCategoryService->getCategoryById($request->id);
        } catch (\Exception $e) {
              report($e);
              return false;
        }

        return inertia::render('EC/Admin/CategoryEdit', [
              "data" => $category,
        ]);
    }

    /**
     * 編集処理->DB
     */
    public function update(AdminCategoryRequest $request): RedirectResponse|bool
    {
      try {
          $this->adminCategoryService->updateCategory($request);
          Log::info('Category update succeeded');
      } catch (\Exception $e) {
          report($e);
          return false;
      }

      return redirect()->back()->with('success', '更新しました');
      
    }

    /**
     * 削除処理->DB
     */
    public function destroy(Request $request): RedirectResponse|bool
    {
      $request->validate([
          'id' => 'required|string|exists:categories,id',
      ]);

      try {
          $this->adminCategoryService->deleteCategory($request);
          Log::info('Category delete succeeded');
      } catch (\Exception $e) {
          report($e);
          return false;
      }

      return redirect()->back()->with('success', '削除しました');
    }
}
