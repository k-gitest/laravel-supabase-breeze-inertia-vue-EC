<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Image;
use App\Http\Requests\Admin\AdminProductRequest;
use App\Services\Admin\AdminImageService;
use App\Services\VectorizerService;
use App\Jobs\VectorizeProduct;
use Log;

class AdminProductService
{
    protected $adminImageService;
    protected $vectorizerService;

    public function __construct(AdminImageService $adminImageService, VectorizerService $vectorizerService)
    {
        $this->adminImageService = $adminImageService;
        $this->vectorizerService = $vectorizerService;
    }

    public function getAllProducts($id, $search_price_ranges)
    {
        $result = Product::with(['image', 'category'])
            ->withSum(['stock' => function ($query) use ($id) {
                if ($id) {
                    $query->where('warehouse_id', $id);
                }
            }], 'quantity')
            ->orderBy('created_at', 'desc');

        return $result->paginate(10);
    }

    public function createProduct(AdminProductRequest $request, Product $product)
    {
        DB::transaction(function () use ($request, $product) {
            $price_including_tax = $this->calculatePriceIncludingTax($request->price_excluding_tax, $request->tax_rate);

            $validateData = $request->validated();
            $validateData["price_including_tax"] = $price_including_tax;

            $result = $product->create($validateData);
            Log::info('Product created', ['name' => $result->name]);

            $product_id = $result->id;
            $filenames = $this->adminImageService->handleImageProductUploads($product_id, $request);
            Log::info('product image save success');

            try {
                $this->adminImageService->saveImages($filenames);
            } catch (\Exception $e) {
                $this->adminImageService->deleteUploadImages($filenames, 'path');
                throw $e;
            }
            VectorizeProduct::dispatch($result->id);
        });
    }

    public function updateProduct(AdminProductRequest $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $product = Product::lockForUpdate()->findOrFail($id);
            $product->fill($request->validated());

            $price_including_tax = $this->calculatePriceIncludingTax($request->price_excluding_tax, $request->tax_rate);
            $product->price_including_tax = $price_including_tax;

            if ($product->isDirty()) {
                $product->save();
            }
            Log::info('Product updated.', ['id' => $product->id]);

            $product_id = $product->id;
            $latestNumber = $this->adminImageService->getLatestImageNumber($product_id);
            $filenames = $this->adminImageService->handleImageProductUploads($product_id, $request, $latestNumber);
            Log::info('Update Image uploaded.', ['id' => $product->id]);

            try {
                $this->adminImageService->saveImages($filenames);
                Log::info('Product updated.', ['id' => $product->id]);
            } catch (\Exception $e) {
                $this->adminImageService->deleteUploadImages($filenames, 'path');
                throw $e;
            }
        });
    }

    public function deleteProduct($id)
    {
        DB::transaction(function () use ($id) {
            $product = Product::lockForUpdate()->findOrFail($id);

            $images = Image::where('product_id', $id)->pluck('path')->toArray();
            $product->delete();
            Log::info('product delete successed');

            $this->adminImageService->deleteImages($images);
        });
    }

    public function bulkDeleteProducts($selectedItems)
    {
        DB::transaction(function () use ($selectedItems) {
            foreach ($selectedItems as $id) {
                $images = Image::where('product_id', $id)->pluck('path')->toArray();
                $this->adminImageService->deleteImages($images);
            }

            Product::whereIn('id', $selectedItems)->delete();
            Log::info('product bulk delete successed');
        });
    }

    private function calculatePriceIncludingTax($priceExcludingTax, $taxRate)
    {
        return $priceExcludingTax + ($priceExcludingTax * $taxRate / 100);
    }
}
