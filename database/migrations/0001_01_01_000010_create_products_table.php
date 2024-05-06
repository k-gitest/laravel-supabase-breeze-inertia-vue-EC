<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('category_id');
            $table->string('name'); //商品名
            $table->decimal('price_excluding_tax', 10, 2); // 税抜き価格
            $table->decimal('price_including_tax', 10, 2); // 税込み価格
            $table->decimal('tax_rate', 5, 2); // 税率
            $table->text('description'); //商品説明
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
