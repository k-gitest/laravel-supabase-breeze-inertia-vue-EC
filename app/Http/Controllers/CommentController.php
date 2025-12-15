<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Services\CommentService;
use Log;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $user_id = auth()->user()->id;
        $comments = $this->commentService->getComments();

        return Inertia::render('EC/CommentIndex', [
            'pagedata' => $comments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse|bool
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'title' => 'required|string',
            'comment' => 'required|string|max:255',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'product_id' => $validated['product_id'],
            'title' => $validated['title'],
            'content' => $validated['comment'], 
        ];

        try {
            $this->commentService->createComment($data);
        } catch (\Exception $e) {
            report($e);
            return false;
        }

        return redirect()->route('product.show', $request->product_id)->with('success', 'コメントを投稿しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        Gate::authorize('isGeneral');
        Gate::authorize('delete', $comment);

        try {
            $this->commentService->deleteComment($comment->id); 
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'コメントの削除に失敗しました。');
        }

        return redirect()->route('comment.index')->with('success', 'コメントを削除しました');
    }
}
