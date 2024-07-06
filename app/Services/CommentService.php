<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Comment;

class CommentService
{
    public function getComments()
    {
        return Comment::with('product')
            ->where('user_id', auth()->id())
            ->orderBy('updated_at', 'desc')
            ->paginate(12);
    }

    public function createComment(Request $request)
    {
        DB::transaction(function () use ($request) {
            Comment::create([
                'user_id' => auth()->user()->id,
                'product_id' => $request->product_id,
                'title' => $request->title,
                'content' => $request->comment,
            ]);
        });

        Log::info('Comment created');
    }

    public function deleteComment($user_id, $id)
    {
        $comment = Comment::where('user_id', $user_id)->findOrFail($id);

        DB::transaction(function () use ($comment) {
            $comment->delete();
        });

        Log::info('Comment deleted');
    }

    public function getCommentById($user_id, $id)
    {
        return Comment::where('user_id', $user_id)->findOrFail($id);
    }
}
