<?php

namespace App\Http\Controllers\Admin;

use Inertia\Response;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class AdminSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categoryIds = $request->query('category_ids', []);
        $searchTerm = $request->query('q');

        $query = Product::with(['image', 'category'])->withSum('stock', 'quantity')->orderBy('created_at', 'desc');

        if(!empty($categoryIds)){
            $query->whereIn('category_id', $categoryIds);
        }

        if($searchTerm){
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        $result = $query->paginate(10)->withQueryString();

        return inertia::render('EC/Admin/ProductAllList', [
              'pagedata' => $result,
              'filters'  => [
                  'category_ids' => $categoryIds,
                  'q'  => $searchTerm,
              ],
          ]);
    }

}
