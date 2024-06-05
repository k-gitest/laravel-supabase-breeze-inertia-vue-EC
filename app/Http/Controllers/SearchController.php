<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
  public function index(Request $request): Response
  {
    $categoryIds = $request->query('category_ids', []);
    $searchTerm = $request->query('q');
    
    $query = Product::with(['category', 'image', 'favorite', 'stock'])->orderBy('created_at', 'desc')->withSum('stock', 'quantity');

    if(!empty($categoryIds)){
        $query->whereIn('category_id', $categoryIds);
    }
    
    if($searchTerm){
      $query->where('name', 'like', '%' . $searchTerm . '%');
    }

    $data = $query->paginate(10)->withQueryString();
    
    return inertia::render('EC/ProductAllList',[
      "pagedata" => $data,
      'filters'  => [
           'category_ids' => $categoryIds,
           'q'  => $searchTerm,
      ],
    ]);
  }
  
}
