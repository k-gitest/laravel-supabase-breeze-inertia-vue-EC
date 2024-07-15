<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Stock;
use App\Models\Category;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Admin;
use App\Services\Admin\AdminStockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use Illuminate\Support\Facades\Log;

class StockTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminStockService;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
        $this->actingAs($this->admin, 'admin');
        
        $this->adminStockService = Mockery::mock(AdminStockService::class);
        $this->app->instance(AdminStockService::class, $this->adminStockService);

        Category::factory()->create();
        Product::factory()->create();
        Warehouse::factory()->create();
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testStore()
    {
        $stockData = [
            'product_id' => 1,
            'warehouse_id' => 1,
            'quantity' => 10,
            'reserved_quantity' => 5,
        ];

        $this->adminStockService->shouldReceive('createStock')
            ->once()
            ->andReturn(new Stock($stockData));

        //Log::shouldReceive('info')->once()->with('Stock create succeeded');

        $response = $this->post(route('admin.stock.store'), $stockData);

        $response->assertRedirect(route('admin.product.index'))
                 ->assertSessionHas('success', '在庫登録が成功しました');
    }

    public function testShow()
    {
        $product = Product::factory()->create();
        $warehouse = Warehouse::factory()->create();
        $stock = Stock::factory()->create(['product_id' => $product->id, 'warehouse_id' => $warehouse->id]);

        $mockData = [
            'result' => $product->load('stock.warehouse', 'image'),
            'warehouse' => Warehouse::all(),
        ];

        $this->adminStockService->shouldReceive('getProductWithStockAndWarehouse')
            ->once()
            ->with($product->id)
            ->andReturn($mockData);

        $response = $this->get(route('admin.stock.show', $product->id));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('EC/Admin/StockShow')
            ->has('data')
            ->has('warehouse')
        );
    }

    public function testUpdate()
    {
        $stock = Stock::factory()->create();
        $updateData = [
            'id' => $stock->id,
            'quantity' => 20,
            'reserved_quantity' => 10,
        ];

        $this->adminStockService->shouldReceive('updateStock')
            ->once()
            ->andReturn(true);

        Log::shouldReceive('info')->once()->with('Stock update succeeded');

        $response = $this->put(route('admin.stock.update'), $updateData);

        $response->assertRedirect()
                 ->assertSessionHas('success', '在庫情報を更新しました');
    }

    public function testDestroy()
    {
        $stock = Stock::factory()->create();

        $this->adminStockService->shouldReceive('deleteStock')
            ->once()
            ->andReturn(true);

        Log::shouldReceive('info')->once()->with('Stock delete succeeded');

        $response = $this->delete(route('admin.stock.destroy'), ['id' => $stock->id]);

        $response->assertRedirect()
                 ->assertSessionHas('success', '在庫情報を削除しました');
    }
}