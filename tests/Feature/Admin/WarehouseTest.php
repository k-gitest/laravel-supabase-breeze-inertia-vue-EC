<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use App\Services\Admin\AdminWarehouseService;
use Mockery;

class WarehouseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
        $this->actingAs($this->admin, 'admin');
    }

    public function test_index_displays_all_warehouses()
    {
        Warehouse::factory()->count(3)->create();

        $mockService = Mockery::mock(AdminWarehouseService::class);
        $mockService->shouldReceive('getAllWarehouses')->once()->andReturn(Warehouse::all());
        $this->app->instance(AdminWarehouseService::class, $mockService);

        $response = $this->get(route('admin.warehouse.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) =>
                $page->component('EC/Admin/WarehouseIndex')
                    ->has('data', 3)
            );
    }

    public function test_create_displays_warehouse_registration_form()
    {
        $response = $this->get(route('admin.warehouse.create'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) =>
                $page->component('EC/Admin/WarehouseRegister')
            );
    }

    public function test_store_creates_new_warehouse()
    {
        $warehouseData = Warehouse::factory()->make()->toArray();

        $mockService = Mockery::mock(AdminWarehouseService::class);
        $mockService->shouldReceive('createWarehouse')->once()->andReturn(true);
        $this->app->instance(AdminWarehouseService::class, $mockService);

        $response = $this->post(route('admin.warehouse.store'), $warehouseData);

        $response->assertRedirect(route('admin.warehouse.index'))
            ->assertSessionHas('success', '在庫登録が成功しました');
    }

    public function test_show_displays_warehouse_with_products()
    {
        $warehouse = Warehouse::factory()->create();

        $mockService = Mockery::mock(AdminWarehouseService::class);
        $mockService->shouldReceive('getWarehouseWithProducts')->once()->andReturn(Warehouse::find($warehouse->id)->product()->paginate(10));
        $this->app->instance(AdminWarehouseService::class, $mockService);

        $response = $this->get(route('admin.warehouse.show', $warehouse->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) =>
                $page->component('EC/Admin/WarehouseShow')
                    ->has('pagedata')
            );
    }

    public function test_edit_displays_warehouse_edit_form()
    {
        $warehouse = Warehouse::factory()->create();

        $mockService = Mockery::mock(AdminWarehouseService::class);
        $mockService->shouldReceive('getWarehouseById')->once()->andReturn($warehouse);
        $this->app->instance(AdminWarehouseService::class, $mockService);

        $response = $this->get(route('admin.warehouse.edit', $warehouse->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) =>
                $page->component('EC/Admin/WarehouseEdit')
                    ->has('data')
            );
    }

    public function test_update_updates_warehouse()
    {
        $warehouse = Warehouse::factory()->create();
        $updatedData = Warehouse::factory()->make()->toArray();

        $mockService = Mockery::mock(AdminWarehouseService::class);
        $mockService->shouldReceive('updateWarehouse')->once()->andReturn(true);
        $this->app->instance(AdminWarehouseService::class, $mockService);

        $response = $this->put(route('admin.warehouse.update', $warehouse->id), $updatedData);

        $response->assertRedirect()
            ->assertSessionHas('success', '更新しました');
    }

    public function test_destroy_deletes_warehouse()
    {
        $warehouse = Warehouse::factory()->create();

        $mockService = Mockery::mock(AdminWarehouseService::class);
        $mockService->shouldReceive('deleteWarehouse')->once()->andReturn(true);
        $this->app->instance(AdminWarehouseService::class, $mockService);

        $response = $this->delete(route('admin.warehouse.destroy'), ['id' => $warehouse->id]);

        $response->assertRedirect(route('admin.warehouse.index'))
            ->assertSessionHas('success', '削除しました');
    }
}
