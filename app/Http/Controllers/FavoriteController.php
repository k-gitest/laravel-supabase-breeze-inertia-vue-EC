<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Services\FavoriteService;
use Log;

class FavoriteController extends Controller
{
    protected $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $favorite = $this->favoriteService->getFavoritesByUser(auth()->id());

        return Inertia::render('EC/FavoriteIndex', [
            'pagedata' => $favorite,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
                $this->favoriteService->addFavorite($request);
            } catch (\Exception $e) {
                return false;
            }

            return redirect()->back()->with('success', 'お気に入りに追加しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorite $favorite, $id): RedirectResponse
    {
        Gate::authorize('isGeneral');

        try {
            $this->favoriteService->removeFavorite(auth()->user()->id, $id);
        } catch (\Exception $e) {
            return false;
        }

        return redirect()->route('favorite.index')->with('success', 'お気に入りから削除しました');
    }
}
