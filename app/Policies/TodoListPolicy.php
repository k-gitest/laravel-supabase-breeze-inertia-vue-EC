<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TodoList;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
use Log;

class TodoListPolicy
{
    //use HandlesAuthorization;
    
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can update the todo list.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TodoList  $todoList
     * @return bool
     */
    
    public function update(User $user, TodoList $todoList): Response
    {
        if($user->id === $todoList->user_id){
            return Response::allow();
        } else {
            Log::error('Failed to authorize update. Not TodoList user: ' . request()->ip());
            return Response::denyAsNotFound('Not update authorize.');
        }
    }

    public function delete(User $user, TodoList $todoList): Response
    {
        if($user->id === $todoList->user_id){
            return Response::allow();
        } else {
            Log::error('Failed to authorize delete. Not TodoList user: ' . request()->ip());
            return Response::denyAsNotFound('Not update authorize.');
        }
    }
}
