<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Log;

class CartPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cart $cart): Response
    {
        if($user?->id === $cart->user_id){
            return Response::allow();
        } else {
            Log::error('Failed to authorize update. Not Cart user: ' . request()->ip());
            return Response::denyAsNotFound('Not update authorize.');
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cart $cart): Response
    {
        if($user?->id === $cart->user_id){
            return Response::allow();
        } else {
            Log::error('Failed to authorize delete. Not Cart user: ' . request()->ip());
            return Response::denyAsNotFound('Not delete authorize.');
        }
    }

}
