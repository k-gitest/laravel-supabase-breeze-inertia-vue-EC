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
      // rename()はテーブル名の変更rename('現在のテーブル名', '変更テーブル名')
        //Schema::rename('contacts', 'contact');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::rename('contact', 'contacts');
    }
};
