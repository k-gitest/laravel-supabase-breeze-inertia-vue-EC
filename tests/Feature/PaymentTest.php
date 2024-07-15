<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use App\Models\Image;
use App\Services\CartService;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Inertia\Testing\AssertableInertia as Assert;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $product;
    protected $cart;
    protected $cartService;
    protected $paymentService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create();
        $this->cart = Cart::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $this->cartService = Mockery::mock(CartService::class);
        $this->app->instance(CartService::class, $this->cartService);

        $this->paymentService = Mockery::mock(PaymentService::class);
        $this->app->instance(PaymentService::class, $this->paymentService);
    }

    public function test_payment_index()
    {
        $totalPrice = [
            "total_price_including_tax" => 1000,
        ];

        $paymentIntent = (object)[
            'client_secret' => 'test_secret',
        ];

        $this->cartService->shouldReceive('getTotalPrices')
            ->with(Mockery::type('Illuminate\Database\Eloquent\Collection'))
            ->andReturn($totalPrice);

        $this->paymentService->shouldReceive('createPaymentIntent')
            ->with($totalPrice, config('services.stripe.warehouse_id'), $this->user->id, $this->user->email)
            ->andReturn($paymentIntent);

        $response = $this->get(route('payment.index'));

        $response->assertStatus(200);

        $response->assertInertia(fn (Assert $page) => $page
            ->component('EC/PaymentComponent')
            ->where('clientSecret', 'test_secret')
            ->where('totalPrice', $totalPrice)
            ->has('data')
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
