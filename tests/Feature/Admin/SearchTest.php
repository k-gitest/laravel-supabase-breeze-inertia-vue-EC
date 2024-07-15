<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Http\Controllers\Admin\AdminSearchController;
use App\Services\Admin\AdminSearchService;
use App\Http\Requests\Admin\AdminSearchRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Response;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    protected $adminSearchService;
    protected $controller;

    public function setUp(): void
    {
        parent::setUp();
        $this->adminSearchService = Mockery::mock(AdminSearchService::class);
        $this->controller = new AdminSearchController($this->adminSearchService);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testIndex()
    {
        $mockResult = ['some' => 'data'];

        $this->adminSearchService->shouldReceive('searchProducts')
            ->once()
            ->andReturn($mockResult);

        $request = new AdminSearchRequest([
            'category_ids' => [1, 2],
            'q' => 'test',
            'warehouse_check' => 'true',
            'price_range' => ['1000-2000', '2000-3000'],
        ]);

        $mockResponse = Mockery::mock(Response::class);
        Inertia::shouldReceive('render')
            ->once()
            ->with('EC/Admin/ProductAllList', [
                'pagedata' => $mockResult,
                'price_ranges' => config('constants.PRICE_RANGES'),
                'filters' => [
                    'category_ids' => [1, 2],
                    'q' => 'test',
                    'warehouse_check' => 'true',
                    'price_range' => ['1000-2000', '2000-3000'],
                ],
            ])
            ->andReturn($mockResponse);

        $response = $this->controller->index($request);

        $this->assertInstanceOf(Response::class, $response);
    }
}