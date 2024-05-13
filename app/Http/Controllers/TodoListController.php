<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TodoRequest;
use App\Models\TodoList;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Gate;


class TodoListController extends Controller
{
    //
  public function index(Request $request): Response
  {
    $todoLists = TodoList::all();
    return Inertia::render('Todo/Index', [
        "data" => $todoLists,
    ]);
  }

  public function create(Request $request): Response
  {
    return Inertia::render('Todo/Register');
  }
  
  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'name' => 'required|string|max:100',
    ]);

    $userId = $request->user()->id;
    
    $todoList = TodoList::create([
      'name' => $request->name,
      'user_id' => $userId,
    ]);
    
    return redirect(route('todo.index', absolute: false));
  }

  public function edit(Request $request, $id): Response
  {
    $todo = TodoList::findOrFail($id); 
    return Inertia::render('Todo/Edit', [
        'todoList' => $todo,
    ]);    
  }

  public function update(Request $request, $id, TodoList $todolist): RedirectResponse
  {
    if (Gate::allows('update-todo-list', $todolist)) {
        abort(403);
    }

    $todo = TodoList::find($id);
    $todo->name = $request->name;
    if ($todo->isDirty()) {
        $todo->save();
    }
    return redirect(route('todo.index', absolute: false));
  }

  public function destroy(Request $request, $id): RedirectResponse
  {
    DB::transaction(function () use ($id)  {
        $todo = TodoList::find($id);
        $todo->delete();
    });

    return redirect()->route('todo.index')->with('message', 'My message');
  }
  
}
