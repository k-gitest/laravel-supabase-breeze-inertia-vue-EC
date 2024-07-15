<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Image;
use App\Models\Category;
use App\Models\Product;
use App\Services\Admin\AdminImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;

class ImageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $sbStorageMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();

        $this->sbStorageMock = Mockery::mock('SbStorage');
        $this->app->instance('SbStorage', $this->sbStorageMock);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testDestroySuccess()
    {
        Category::factory()->create();
        $product = Product::factory()->create();
        $image = Image::factory()->create([
            'product_id' => $product->id,
            'path' => 'path/to/image.jpg'
        ]);

        $this->sbStorageMock->shouldReceive('deleteImage')
            ->once()
            ->with([$image->path])
            ->andReturn(true);

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.image.destroy', $product->id), [
            'image_id' => $image->id,
            'path' => $image->path,
        ]);

        $response->assertRedirect(route('admin.product.edit', $product->id))
            ->assertSessionHas('success', '削除しました');

        $this->assertDatabaseMissing('images', ['id' => $image->id]);
    }

    public function testDestroyWithInvalidImageId()
    {
        Category::factory()->create();

        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.image.destroy', $product->id), [
            'image_id' => 999,
            'path' => 'path/to/image.jpg',
        ]);

        $response->assertSessionHasErrors('image_id');
    }

    public function testDestroyWithMissingPath()
    {
        Category::factory()->create();

        $product = Product::factory()->create();
        $image = Image::factory()->create(['product_id' => $product->id]);

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.image.destroy', $product->id), [
            'image_id' => $image->id,
        ]);

        $response->assertSessionHasErrors('path');
    }

    public function testDestroyWithServiceException()
    {
        Category::factory()->create();

        $product = Product::factory()->create();
        $image = Image::factory()->create([
            'product_id' => $product->id,
            'path' => 'path/to/image.jpg'
        ]);

        $this->sbStorageMock->shouldReceive('deleteImage')
            ->once()
            ->with([$image->path])
            ->andThrow(new \Exception('Storage error'));

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.image.destroy', $product->id), [
            'image_id' => $image->id,
            'path' => $image->path,
        ]);
        
        $response->assertSessionHasErrors(['error' => 'Failed to action. Please try again.']);

        $this->assertDatabaseHas('images', ['id' => $image->id]); 
    }
}