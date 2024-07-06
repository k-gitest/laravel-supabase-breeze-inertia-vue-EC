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
        $comments = $this->commentService->getComments();

        return Inertia::render('EC/CommentIndex', [
            'pagedata' => $comments,
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

        try {
            $this->commentService->createComment($request);
        } catch (\Exception $e) {
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
        $comment = $this->commentService->getCommentById($user_id, $id);

        Gate::authorize('delete', $comment);

        try {
            $this->commentService->deleteComment($user_id, $id);
        } catch (\Exception $e) {
            report($e);
            return false;
        }

        return redirect()->route('comment.index')->with('success', 'コメントを削除しました');
    }
}
