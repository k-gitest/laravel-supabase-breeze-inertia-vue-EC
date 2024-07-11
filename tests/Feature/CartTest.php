<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use App\Services\CartService;
use Tests\TestCase;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CartRequest;
use Mockery;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setup(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create();
        $this->cart = Cart::factory()->create();
        $this->actingAs($this->user);
        $this->cartService = Mockery::mock(CartService::class);
        $this->app->instance(CartService::class, $this->cartService);
    }
    
    /**
     * A basic feature test example.
     */
    public function test_cart_index(): void
    {
        $cartItems = [
            [
                'id' => $this->cart->id,
                'user_id' => $this->user->id,
                'product_id' => $this->product->id,
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $expectedTotalPrices = [
            'total_price_excluding_tax' => 100,
            'total_price_including_tax' => 110,
        ];

        $this->cartService->shouldReceive('getCartItems')
            ->once()
            ->andReturn($cartItems);

        $this->cartService->shouldReceive('getTotalPrices')
            ->once()
            ->with($cartItems)
            ->andReturn($expectedTotalPrices);

        $response = $this->get(route('cart.index'));
        $response->assertStatus(200);

        $response->assertInertia(fn (Assert $page) => $page
            ->component('EC/CartIndex')
            ->has('pagedata', 1)
            ->has('totalPrice')
            ->where('totalPrice.total_price_excluding_tax', 100)
            ->where('totalPrice.total_price_including_tax', 110)
        );

    }

    public function test_cart_store(): void
    {
        $data = [
            "product_id" => $this->product->id,
            "user_id" => $this->user->id,
            "quantity" => 1,
        ];
        
        $this->cartService->shouldReceive('createCart')
            ->once()
            ->with(Mockery::type(CartRequest::class));

        $response = $this->from(route('cart.index'))
            ->post(route('cart.store'), $data);
        $response->assertStatus(302);
        
        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', '商品をカートに追加しました。');
        
    }

    public function test_cart_edit(): void
    {
        $cartId = $this->cart->id;
        $user = $this->user;
        $cart = $this->cart;
        
        $this->assertAuthenticated();
        
        $this->cartService->shouldReceive('getCartItem')
            ->with($cartId)
            ->once()
            ->andReturn($this->cart);

        Gate::define('update', function ($user, $cart) {
            return $this->user->id === $this->cart->user_id;
        });

        $response = $this->get(route('cart.edit', $cartId));

        $response->assertStatus(200);

        $response->assertInertia(fn (Assert $page) => $page
            ->component('EC/CartEdit')
            ->has('data')
        );
    }

    public function test_cart_update(): void
    {
        $user = User::factory()->create(['role' => 'general']);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cartId = $cart->id;

        $data = [
            "product_id" => $this->product->id,
            "quantity" => 1,
        ];
        
        $this->actingAs($user);
        $this->assertAuthenticated();

        $this->cartService->shouldReceive('getCartItem')
            ->once()
            ->with($cartId)
            ->andReturn($cart);
        
        $this->cartService->shouldReceive('updateCart')
            ->once()
            ->with(Mockery::type(CartRequest::class), $cartId)
            ->andReturn();

        $response = $this
            ->put(route('cart.update', [ 'id' => $cartId ]), $data);
        
        $response->assertRedirect();
        $response->assertSessionHas('success', '更新しました');
    }

    public function test_cart_destroy(): void
    {
        $user = User::factory()->create(['role' => 'general']);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cartId = $cart->id;

        $this->actingAs($user);
        $this->assertAuthenticated();

        $this->cartService->shouldReceive('getCartItem')
            ->once()
            ->with($cartId)
            ->andReturn($cart);

        $this->cartService->shouldReceive('deleteCart')
            ->once()
            ->with($cartId)
            ->andReturn();

        $response = $this
            ->delete(route('cart.destroy', [ 'id' => $cartId ]));

        $response->assertRedirect();
        $response->assertSessionHas('success', '削除しました');
        
    }
}
