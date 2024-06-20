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
use Log;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $favorite = Favorite::with(['product.image', 'product.category'])->where('user_id', auth()->id())->orderBy('updated_at', 'desc')->paginate(12);

        return Inertia::render('EC/FavoriteIndex', [
            'pagedata' => $favorite,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        try{
            DB::transaction(function () use ($request){
                Favorite::create([
                    'user_id' => auth()->user()->id,
                    'product_id' => $request->product_id,
                ]);
            });
            Log::info('Favorite created');
        }
        catch (\Exception $e){
            Log::error('Failed to create favorite.', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to create favorite. Please try again.']);
        }

        return redirect()->back()->with('success', 'お気に入りに追加しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorite $favorite, $id): RedirectResponse
    {
        Gate::authorize('isGeneral');
        
        $user_id = auth()->user()->id;
        $favorite = Favorite::where('user_id', $user_id)->find($id);
        
        Gate::authorize('delete', $favorite);
        
        try{
            DB::transaction(function () use ($favorite){
                $favorite->delete();
            });
            Log::info('Favorite deleted');
        }
        catch(\Exception $e){
            Log::error('Failed to delete favorite.', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to delete favorite. Please try again.']);
        }

        return redirect()->route('favorite.index')->with('success', 'お気に入りから削除しました');
    }
}
