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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('message');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('language')->nullable();
            $table->string('previous_url')->nullable();
            $table->string('referrer')->nullable();
            $table->string('platform')->nullable();
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
