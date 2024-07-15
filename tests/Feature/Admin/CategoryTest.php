<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;

class CategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    public function testIndex()
    {
        $categories = Category::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.category.index'));

        $response->assertInertia(fn (Assert $assert) => $assert
            ->component('EC/Admin/CategoryIndex')
            ->has('data', 3)
        );
    }

    public function testCreate()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.category.create'));

        $response->assertInertia(fn (Assert $assert) => $assert
            ->component('EC/Admin/CategoryRegister')
            ->has('data')
        );
    }

    public function testStore()
    {
        $categoryData = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.category.store'), $categoryData);

        $response->assertRedirect()
            ->assertSessionHas('success', 'カテゴリー登録が成功しました');

        $this->assertDatabaseHas('categories', $categoryData);
    }

    public function testEdit()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.category.edit', ['id' => $category->id]));

        $response->assertInertia(fn (Assert $assert) => $assert
            ->component('EC/Admin/CategoryEdit')
            ->has('data', fn ($assert) => $assert
                ->where('id', $category->id)
                ->etc()
            )
        );
    }

    public function testUpdate()
    {
        $category = Category::factory()->create();
        $updatedData = [
            'id' => $category->id,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.category.update', ['id' => $category->id]), $updatedData);

        $response->assertRedirect()
            ->assertSessionHas('success', '更新しました');

        $this->assertDatabaseHas('categories', $updatedData);
    }

    public function testDestroy()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.category.destroy', ['id' => $category->id]));

        $response->assertRedirect()
            ->assertSessionHas('success', '削除しました');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}