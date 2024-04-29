<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TodoList;

class TodoPolicy
{
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
    public function update(User $user, TodoList $todoList)
    {
        return $user->id === $todoList->user_id;
    }
}
