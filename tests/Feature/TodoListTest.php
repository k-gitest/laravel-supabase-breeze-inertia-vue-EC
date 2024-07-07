<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TodoList;
use App\Services\TodoListService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Support\Facades\Gate;
use Mockery;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->todoListService = Mockery::mock(TodoListService::class);
        $this->app->instance(TodoListService::class, $this->todoListService);
    }

    public function testIndex()
    {
        $this->todoListService->shouldReceive('getTodoListItems')
            ->once()
            ->andReturn(collect([]));

        $response = $this->get(route('todo.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Todo/Index')
            ->has('pagedata'));
    }

    public function testCreate()
    {
        $response = $this->get(route('todo.register'));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Todo/Register'));
    }

    public function testStore()
    {
        $data = [
            'name' => 'Test Todo',
        ];

        $this->todoListService->shouldReceive('createTodoList')
            ->once()
            ->andReturn(new TodoList($data));

        $response = $this->post(route('todo.store'), $data);

        $response->assertRedirect(route('todo.index'));
        $response->assertSessionHas('success', 'TodoListを作成しました。');
    }

    public function testEdit()
    {
        $todo = TodoList::factory()->create(['user_id' => $this->user->id]);

        $this->todoListService->shouldReceive('getTodoListItem')
            ->with($todo->id)
            ->once()
            ->andReturn($todo);

        $response = $this->get(route('todo.edit', $todo->id));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Todo/Edit')
            ->has('todoList'));
    }

    public function testUpdate()
    {
        $user = User::factory()->create(['role' => 'general']);
        $todo = TodoList::factory()->create(['user_id' => $user->id]);
        $data = [
            'name' => 'Updated Title',
            'user_id' => $this->user->id,
        ];

        $this->actingAs($user);
        $this->assertAuthenticated();

        Gate::define('update', function ($user, $todo) {
            return $user->id === $todo->user_id;
        });

        $this->todoListService->shouldReceive('getTodoListItem')
        ->with($todo->id)
        ->once()
        ->andReturn($todo);

        $this->todoListService->shouldReceive('updateTodoList')
            ->with(Mockery::type('App\Http\Requests\TodoRequest'), $todo->id)
            ->once();

        $response = $this->put(route('todo.update', ['id' => $todo->id]), $data);
        
        $response->assertRedirect(route('todo.index'));
        $response->assertSessionHas('success', 'TodoListを更新しました。');
    }

    public function testDestroy()
    {
        $user = User::factory()->create(['role' => 'general']);
        $todo = TodoList::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);
        $this->assertAuthenticated(); 

        Gate::define('delete', function ($user, $todo) {
            return $user->id === $todo->user_id;
        });

        $this->todoListService->shouldReceive('getTodoListItem')
        ->with($todo->id)
        ->once()
        ->andReturn($todo);
        
        $this->todoListService->shouldReceive('deleteTodoList')
            ->with($todo->id)
            ->once();

        $response = $this->delete(route('todo.destroy', $todo->id));

        $response->assertRedirect(route('todo.index'));
        $response->assertSessionHas('success', 'todoListを削除しました');
    }
}

