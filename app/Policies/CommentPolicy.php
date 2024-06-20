<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Log;

class CommentPolicy
{
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): Response
    {
        if($user?->id === $comment->user_id){
            return Response::allow();
        } else {
            Log::error('Failed to authorize delete. Not Comment user: ' . request()->ip());
            return Response::denyAsNotFound('Not delete authorize.');
        }
    }

}
