<?php

namespace App\Http\Controllers;

use Inertia\inertia;
use Inertia\Response;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Log;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $comment = Comment::with('product')->get();

        return Inertia::render('EC/CommentIndex', [
            'data' => $comment,
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
            Log::error('Failed to create comment.', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to create comment. Please try again.']);
        }

        return redirect()->route('product.show', $request->product_id)->with('success', 'コメントを投稿しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment, $id): RedirectResponse
    {
        $user_id = auth()->user()->id;
        $comment = Comment::where('user_id', $user_id)->find($id);

        try{
            DB::transaction(function () use ($id){
                $comment->delete();
            });
            Log::info('Comment deleted');
        }
        catch(\Exception $e){
            Log::error('Failed to delete comment.', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to delete comment. Please try again.']);
        }

        return redirect()->route('comment.index')->with('success', 'コメントを削除しました');
    }
}
