<?php

namespace App\Http\Controllers;

use Inertia\inertia;
use Inertia\Response;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

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
        DB::transaction(function () use ($request){
            $request->validate([
                'product_id' => 'required|integer',
                'title' => 'required|string',
                'comment' => 'required|string|max:255',
            ]);

            Comment::create([
                'user_id' => auth()->user()->id,
                'product_id' => $request->product_id,
                'title' => $request->title,
                'content' => $request->comment,
            ]);
        });

        return redirect()->route('product.show', $request->product_id)->with('success', 'コメントを投稿しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment, $id): RedirectResponse
    {
        DB::transaction(function () use ($id){
            $user_id = auth()->user()->id;
            $comment = Comment::where('user_id', $user_id)->find($id);
            $comment->delete();
        });

        return redirect()->route('comment.index')->with('success', 'コメントを削除しました');
    }
}
