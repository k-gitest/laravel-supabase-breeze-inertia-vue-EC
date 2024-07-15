<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Services\OrderService;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;

class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected function setup(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'general']);
        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create();
        $this->order = Order::factory()->create(['user_id' => $this->user->id]);
        $this->orderItem = OrderItem::factory()->create(['order_id' => $this->order->id]);
        $this->actingAs($this->user);  
        $this->orderService = Mockery::mock(OrderService::class);
        $this->app->instance(OrderService::class, $this->orderService);
    }
    
    public function test_order_index(): void
    {
        $this->orderService->shouldReceive('getOrderList')
            ->with($this->user->id)
            ->once()
            ->andReturn($this->order);

        $response = $this->get(route('order.index'));
        $response->assertStatus(200);

        $response->assertInertia(fn (Assert $page) => $page
            ->component('EC/Order')
            ->has('pagedata')
        );
    }

    public function test_order_show(): void
    {
        $this->orderService->shouldReceive('getOrderDetails')
            ->with($this->order->id, $this->user->id)
            ->once()
            ->andReturn($this->orderItem);

        $response = $this->get(route('order.show', $this->order->id));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('EC/OrderDetail')
            ->has('data')
        );
    }
}
