<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\Admin\AdminCategoryRequest;

class AdminCategoryService
{
    public function getAllCategories()
    {
        return Category::all();
    }

    public function createCategory($request)
    {
        DB::transaction(function () use ($request) {
            Category::create($request->validated());
        });
    }

    public function getCategoryById(string $id)
    {
        return Category::findOrFail($id);
    }

    public function updateCategory(AdminCategoryRequest $request)
    {
        DB::transaction(function () use ($request) {
            $category = Category::lockForUpdate()->findOrFail($request->id);
            $category->fill($request->validated());

            if ($category->isDirty()) {
                $category->save();
            }
        });
    }

    public function deleteCategory($request)
    {
        DB::transaction(function () use ($request) {
            $category = Category::lockForUpdate()->findOrFail($request->id);
            $category->delete();
        });
    }
}
