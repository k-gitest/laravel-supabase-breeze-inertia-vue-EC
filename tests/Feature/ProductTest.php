<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use App\Services\ProductService;
use App\Models\Product;
use App\Models\Category;
use Mockery;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productServiceMock = Mockery::mock(ProductService::class);
        $this->app->instance(productService::class, $this->productServiceMock );
    }
    
    /**
     * A basic feature test example.
     */
    public function testProductIndex(): void
    {
        $category = Category::factory()->create();
        $products = Product::factory()->count(2)->create();

        $this->productServiceMock
            ->shouldReceive('getAllProducts')
            ->once()
            ->andReturn($products);

        $response = $this->get(route('product.index'));

        $response->assertStatus(200);
        
        $response->assertInertia(fn (Assert $page) => $page
            ->component('EC/ProductAllList')
            ->has('pagedata', 2)
            ->has('price_ranges')
            ->has('filters', fn (Assert $page) => $page
                ->where('category_ids', [])
                ->where('q', "")
                ->where('price_range', [])
                ->where('warehouse_check', false)
                ->where('sort_option', 'newest')
            )
        );
        
    }

    public function testProductShow(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create();

        $this->productServiceMock
            ->shouldReceive('getProductDetails')
            ->once()
            ->andReturn($product);

        $response = $this->get(route('product.show', $product->id));

        $response->assertStatus(200);
        
        $response->assertInertia(fn (Assert $page) => $page
            ->component('EC/ProductDetail')
        );
        
    }
}
