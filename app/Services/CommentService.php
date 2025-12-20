<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Comment;

class CommentService
{
    public function getComments(int $user_id)
    {
        return Comment::with('product')
            ->where('user_id', $user_id)
            ->orderBy('updated_at', 'desc')
            ->paginate(12);
    }

    public function createComment(array $data)
    {
        $comment = Comment::create([
            'user_id'    => $data['user_id'],
            'product_id' => $data['product_id'],
            'title'      => $data['title'],
            'content'    => $data['content'],
        ]);

        Log::info('Comment created', ['comment_id' => $comment->id, 'user_id' => $data['user_id']]);
    }

    public function deleteComment(Comment $comment): void
    {
        // $deleted = Comment::where('id', $comment->id)->delete();
        // すでにコントローラーで認可済みなので、ここでは削除に専念
        if (!$comment->delete()) {
            throw new \Exception("Failed to delete comment ID: {$comment->id}");
            // return true;
        }
        Log::info('Comment deleted', ['comment_id' => $comment->id]);
    }
}
