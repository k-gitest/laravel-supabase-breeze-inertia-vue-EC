<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Support\Facades\Log;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();

        $this->actingAs($this->admin, 'admin');
    }

    public function test_admin_product_index()
    {
        Category::factory()->create();
        Product::factory()->count(10)->create();

        $response = $this->get(route('admin.product.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('EC/Admin/ProductAllList')
                ->has('pagedata.data', 10)
                ->has('price_ranges')
                ->has('filters')
            );
    }

    public function test_admin_product_create()
    {
        Category::factory()->count(5)->create();

        $response = $this->get(route('admin.product.create'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('EC/Admin/ProductRegister')
                ->has('data', 5)
            );
    }

    public function test_admin_product_store()
    {
        $category = Category::factory()->create();

        $productData = [
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'price_excluding_tax' => 1000,
            'tax_rate' => 10,
            'category_id' => $category->id,
        ];

        $response = $this->post(route('admin.product.store'), $productData);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'description' => 'This is a test product.',
        ]);

        $response->assertRedirect(route('admin.product.index'))
            ->assertSessionHas('success', 'Product created');
    }

    public function test_admin_product_detail()
    {
        Category::factory()->count(5)->create();
        $product = Product::factory()->create();

        $response = $this->get(route('admin.product.show', $product->id));

        $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('EC/Admin/ProductDetail')
            ->has('data', fn (Assert $page) => $page
                ->where('id', $product->id)
                ->where('name', $product->name)
                ->etc()
            )
        );
    }

    public function test_admin_product_edit()
    {
        Category::factory()->count(5)->create();
        $product = Product::factory()->create();

        $response = $this->get(route('admin.product.edit', $product->id));

        $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('EC/Admin/ProductEdit')
            ->has('data', fn (Assert $page) => $page
                ->where('id', $product->id)
                ->where('name', $product->name)
                ->etc()
            )
        );
    }

    public function test_admin_product_update()
    {
        Category::factory()->count(5)->create();
        $product = Product::factory()->create();

        $updateData = [
            'name' => 'Updated Product',
            'description' => 'This is an updated test product.',
            'price_excluding_tax' => 1500,
            'tax_rate' => 8,
            'category_id' => $product->category_id,
        ];

        $response = $this->put(route('admin.product.update', $product->id), $updateData);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'description' => 'This is an updated test product.',
        ]);

        $response->assertRedirect(route('admin.product.edit', $product->id))
            ->assertSessionHas('success', '更新しました');
    }

    public function test_admin_product_delete()
    {
        Category::factory()->count(5)->create();
        $product = Product::factory()->create();

        $response = $this->delete(route('admin.product.destroy', $product->id));

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);

        $response->assertRedirect('admin/product')
            ->assertSessionHas('success', '削除しました');
    }

    public function test_admin_product_bulk_deletes()
    {
        Category::factory()->count(5)->create();
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $products = Product::factory()->count(3)->create();
        $ids = $products->pluck('id')->toArray();

        foreach ($ids as $id) {
            $this->assertDatabaseHas('products', ['id' => $id]);
        }

        $response = $this->delete(route('admin.product.bulkDestroy'), [
            'ids' => $ids,
        ]);

        foreach ($ids as $id) {
            $this->assertDatabaseMissing('products', ['id' => $id]);
        }

        $response->assertRedirect()
            ->assertSessionHas('success', '削除しました');
    }
}
