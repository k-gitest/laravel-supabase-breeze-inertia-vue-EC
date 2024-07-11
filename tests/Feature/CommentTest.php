<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Comment;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Services\CommentService;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;
use Mockery;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected function setup(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'general']);
        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create();
        $this->comment = Comment::factory()->create(['user_id' => $this->user->id]);
        $this->actingAs($this->user);
        $this->commentService = Mockery::mock(CommentService::class);
        $this->app->instance(CommentService::class, $this->commentService);
    }
    
    /**
     * A basic feature test example.
     */
    
    public function test_comment_index(): void
    {
        $this->commentService->shouldReceive('getComments')
            ->once()
            ->andReturn($this->comment);

        $response = $this->get(route('comment.index'));

        $response->assertStatus(200);

        $response->assertInertia(fn (Assert $page) => $page
            ->component('EC/CommentIndex')
            ->has('pagedata')
        );
    }

    public function test_comment_store(): void
    {
        $data = [
            'product_id' => $this->product->id,
            'title' => 'title',
            'comment' => 'comment',
            'user_id' => $this->user->id,
        ];
        
        $this->commentService->shouldReceive('createComment')
            ->once()
            ->with(Mockery::on(function ($request) use ($data) {
                return $request->all() == $data;
            }));

        $response = $this->post(route('comment.store'), $data);

        $response->assertStatus(302);

        $response->assertRedirect(route('product.show', $this->product->id));
        $response->assertSessionHas('success', 'コメントを投稿しました');
    }

    public function test_comment_destroy(): void
    {
        $this->commentService->shouldReceive('getCommentById')
            ->with($this->user->id, $this->comment->id)
            ->once()
            ->andReturn($this->comment);
        
        $this->commentService->shouldReceive('deleteComment')
            ->with($this->user->id, $this->comment->id)
            ->once();

        $response = $this->delete(route('comment.destroy', $this->comment->id));

        $response->assertRedirect(route('comment.index'));
        $response->assertSessionHas('success', 'コメントを削除しました');
    }
}
