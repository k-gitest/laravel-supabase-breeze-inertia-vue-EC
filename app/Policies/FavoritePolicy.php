<?php

namespace App\Policies;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Log;

class FavoritePolicy
{
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Favorite $favorite): Response
    {
        if($user?->id === $favorite->user_id){
            return Response::allow();
        } else {
            Log::error('Failed to authorize delete. Not Favorite user: ' . request()->ip());
            return Response::denyAsNotFound('Not delete authorize.');
        }
    }
    
}
