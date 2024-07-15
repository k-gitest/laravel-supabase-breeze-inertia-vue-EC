<?php

namespace Tests\Feature\Controllers;

use App\Http\Controllers\SearchController;
use App\Http\Requests\SearchRequest;
use App\Services\SearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Mockery;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    protected $searchService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->searchService = Mockery::mock(SearchService::class);
        $this->app->instance(SearchService::class, $this->searchService);
    }

    public function testIndex()
    {
        $mockResult = ['mock' => 'result'];
        $this->searchService->shouldReceive('searchProducts')->once()->andReturn($mockResult);

        $requestData = [
            'category_ids' => [],
            'q' => '',
            'price_range' => [],
            'warehouse_check' => 'true',
            'sort_option' => 'newest',
        ];

        $response = $this->get(route('search', $requestData));

        $response->assertStatus(200);
        
        $response->assertInertia(function (Assert $assert) use ($mockResult, $requestData) {
            $assert->component('EC/ProductAllList')
               ->has('pagedata')
               ->where('pagedata', $mockResult)
               ->has('price_ranges')
               ->has('filters', function (Assert $assert) use ($requestData) {
                   $assert->where('category_ids', $requestData['category_ids'])
                        ->where('q', fn ($value) => $value === null || $value === '')
                        ->where('price_range', $requestData['price_range'])
                        ->where('warehouse_check', $requestData['warehouse_check'] === 'true')
                        ->where('sort_option', $requestData['sort_option']);
               });
        });
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}