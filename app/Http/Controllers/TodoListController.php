<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\TodoRequest;
use App\Services\TodoListService;

class TodoListController extends Controller
{  
  protected $todoListService;

  public function __construct(TodoListService $todoListService)
  {
      $this->todoListService = $todoListService;
  }
  
  public function index(Request $request): Response
  {
    $todoLists = $this->todoListService->getTodoListItems();
    
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
    try{
      $this->todoListService->createTodoList($request);
    }
    catch(\Exception $e){
      report($e);
      return false;
    }
    
    return redirect(route('todo.index', absolute: false))->with('success', 'TodoListを作成しました。');
  }

  public function edit(Request $request, int $id): Response
  {
    $todo = $this->todoListService->getTodoListItem($id);
    
    return Inertia::render('Todo/Edit', [
        'todoList' => $todo,
    ]);    
  }

  public function update(TodoRequest $request, int $id): RedirectResponse|bool
  {
    Gate::authorize('isGeneral');
    Gate::authorize('update', $this->todoListService->getTodoListItem($id));
    
    try{
      $this->todoListService->updateTodoList($request, $id);
    }
    catch(\Exception $e){
      report($e);
      return false;
    }

    return redirect(route('todo.index', absolute: false))->with('success', 'TodoListを更新しました。');
  }

  public function destroy(Request $request, int $id): RedirectResponse|bool
  {
    Gate::authorize('isGeneral');    
    Gate::authorize('delete', $this->todoListService->getTodoListItem($id));

    try{
      $this->todoListService->deleteTodoList($id);
    }
    catch(\Exception $e){
      report($e);
      return false;
    }

    return redirect()->route('todo.index')->with('success', 'todoListを削除しました');
  }
  
}
