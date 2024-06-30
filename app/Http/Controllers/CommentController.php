<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Log;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $comment = Comment::with('product')->where('user_id', auth()->id())->orderBy('updated_at', 'desc')->paginate(12);

        return Inertia::render('EC/CommentIndex', [
            'pagedata' => $comment,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
            'title' => 'required|string',
            'comment' => 'required|string|max:255',
        ]);

        try{
            DB::transaction(function () use ($request){
                Comment::create([
                    'user_id' => auth()->user()->id,
                    'product_id' => $request->product_id,
                    'title' => $request->title,
                    'content' => $request->comment,
                ]);
            });
            Log::info('Comment created');
        }
        catch (\Exception $e){
            report($e);
            return false;
        }

        return redirect()->route('product.show', $request->product_id)->with('success', 'コメントを投稿しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment, $id): RedirectResponse
    {
        Gate::authorize('isGeneral');
        
        $user_id = auth()->user()->id;
        $comment = Comment::where('user_id', $user_id)->findOrFail($id);

        Gate::authorize('delete', $comment);

        try{
            DB::transaction(function () use ($comment){
                $comment->delete();
            });
            Log::info('Comment deleted');
        }
        catch(\Exception $e){
            report($e);
            return false;
        }

        return redirect()->route('comment.index')->with('success', 'コメントを削除しました');
    }
}
