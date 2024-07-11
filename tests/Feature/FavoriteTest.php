<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Services\FavoriteService;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;

class FavoriteTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'general']);
        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create();
        $this->favorite = Favorite::factory()->create(['user_id' => $this->user->id]);
        $this->actingAs($this->user);    
        $this->FavoriteService = Mockery::mock(FavoriteService::class);
        $this->app->instance(FavoriteService::class, $this->FavoriteService);
    }
    
    public function test_favorite_index(): void
    {
        $this->FavoriteService->shouldReceive('getFavoritesByUser')
            ->with($this->user->id)
            ->once()
            ->andReturn($this->favorite);
        
        $response = $this->get(route('favorite.index'));

        $response->assertStatus(200);

        $response->assertInertia(fn (Assert $page) => $page
            ->component('EC/FavoriteIndex')
            ->has('pagedata')
        );
    }

    public function test_favorite_store(): void
    {
        $data = [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ];
        
        $this->FavoriteService->shouldReceive('addFavorite')
            ->with(Mockery::on(function ($request) use ($data){
                return $request->all() == $data;
            }))
            ->once();

        $response = $this->post(route('favorite.store'), $data);

        $response->assertStatus(302);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'お気に入りに追加しました');
    }

    public function test_favorite_destroy(): void
    {
        $this->FavoriteService->shouldReceive('removeFavorite')
            ->with($this->user->id, $this->favorite->id)
            ->once();

        $response = $this->delete(route('favorite.destroy', $this->favorite->id));
    }
}
