<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\VectorizerService;
use App\Models\Product;

class VectorizeProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productId;

    /**
     * Create a new job instance.
     */
    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    /**
     * Execute the job.
     */
    public function handle(VectorizerService $vectorizerService): void
    {
        $product = Product::findOrFail($this->productId);
        $vectorizerService->transform([$product->description]);
    }
}
