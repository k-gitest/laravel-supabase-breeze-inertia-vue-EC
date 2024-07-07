<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TodoRequest;
use App\Models\TodoList;
use Log;

class TodoListService
{
    public function getTodoListItems()
    {
        return TodoList::where('user_id', auth()->id())->paginate(12);
    }

    public function createTodoList(TodoRequest $request)
    {
        $userId = auth()->id(); 
        
        DB::transaction(function () use ($request, $userId) {
            $todoList = TodoList::create([
              'name' => $request->name,
              'user_id' => $userId,
            ]);
          });
        
        Log::info('TodoList create succeeded');
    }

    public function getTodoListItem(int $id)
    {
        return TodoList::findOrFail($id);
    }

    public function updateTodoList(TodoRequest $request, int $id)
    {
        DB::transaction(function () use ($request, $id){
            $todo = TodoList::findOrFail($id);
            $todo->name = $request->name;
            
            if ($todo->isDirty()) {
                $todo->save();
            }
          });
        
        Log::info('TodoList update succeeded');
    }

    public function deleteTodoList(int $id)
    {
        DB::transaction(function () use ($id)  {
            $todo = TodoList::findOrFail($id);
            $todo->delete();
          });
        
        Log::info('TodoList delete succeeded');
    }

}
