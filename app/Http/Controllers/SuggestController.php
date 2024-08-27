<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class SuggestController extends Controller
{
    public function suggest(Request $request): JsonResponse
    {
        $query = $request->input('q');
        $result = Product::where('name', 'like', "{$query}%")
        ->take(10)
        ->get(['id', 'name']);
        return response()->json($result);
    }
}
