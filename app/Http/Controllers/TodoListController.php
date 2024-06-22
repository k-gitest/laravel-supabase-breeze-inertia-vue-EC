<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\TodoRequest;
use App\Models\TodoList;
use App\Models\User;
use Log;

class TodoListController extends Controller
{  
  public function index(Request $request): Response
  {
    $todoLists = TodoList::where('user_id', auth()->id())->paginate(12);
    
    return Inertia::render('Todo/Index', [
        "pagedata" => $todoLists,
    ]);
  }

  public function create(Request $request): Response
  {
    return Inertia::render('Todo/Register');
  }
  
  public function store(TodoRequest $request): RedirectResponse
  {
    $userId = auth()->id(); 

    try{
      DB::transaction(function () use ($request, $userId) {
        $todoList = TodoList::create([
          'name' => $request->name,
          'user_id' => $userId,
        ]);
      });
      Log::info('TodoList create succeeded');
    }
    catch(\Exception $e){
      Log::error( 'Failed to create TodoList.', ['error' => $e->getMessage()]);
      return redirect()->back()->withErrors(['error' => 'Failed to create TodoList. Please try again.']);
    }
    
    return redirect(route('todo.index', absolute: false));
  }

  public function edit(Request $request, int $id): Response
  {
    $todo = TodoList::findOrFail($id);
    
    return Inertia::render('Todo/Edit', [
        'todoList' => $todo,
    ]);    
  }

  public function update(TodoRequest $request, int $id, TodoList $todolist): RedirectResponse
  {
    Gate::authorize('isGeneral');

    $todo = TodoList::findOrFail($id);

    Gate::authorize('update', $todo);
    
    $todo->name = $request->name;
    
    try{
      DB::transaction(function () use ($todo){
        if ($todo->isDirty()) {
            $todo->save();
        }
      });
      Log::info('TodoList update succeeded');
    }
    catch(\Exception $e){
      Log::error('Failed to update TodoList.', ['error' => $e->getMessage()]);
      return redirect()->back()->withErrors(['error' => 'Failed to update TodoList. Please try again.']);
    }

    return redirect(route('todo.index', absolute: false));
  }

  public function destroy(Request $request, int $id): RedirectResponse
  {
    Gate::authorize('isGeneral');
    
    $todo = TodoList::findOrFail($id);
    
    Gate::authorize('delete', $todo);

    try{
      DB::transaction(function () use ($todo)  {
          $todo->delete();
      });
      Log::info('TodoList delete succeeded');
    }
    catch(\Exception $e){
      Log::error('Failed to delete TodoList.', ['error' => $e->getMessage()]);
      return redirect()->back()->withErrors(['error' => 'Failed to delete TodoList. Please try again.']);
    }

    return redirect()->route('todo.index')->with('message', 'My message');
  }
  
}
