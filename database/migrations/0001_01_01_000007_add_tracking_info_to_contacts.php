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
        Schema::table('contacts', function (Blueprint $table) {
            //
          $table->string('ip_address')->nullable();
          $table->string('user_agent')->nullable();
          $table->string('language')->nullable();
          $table->string('previous_url')->nullable();
          $table->string('referrer')->nullable();
          $table->string('platform')->nullable();
          $table->string('device')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            //
          $table->dropColumn('ip_address');
          $table->dropColumn('user_agent');
          $table->dropColumn('language');
          $table->dropColumn('previous_url');
          $table->dropColumn('referrer');
          $table->dropColumn('platform');
          $table->dropColumn('device');
        });
    }
};
