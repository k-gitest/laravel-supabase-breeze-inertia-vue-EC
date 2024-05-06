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
        Schema::table('todo_lists', function (Blueprint $table) {
            // unsigned()でマイナス値不可、foreign()で外部キー、references()で参照カラム、on()で参照テーブル、onDelete("cascade")でユーザーレコード削除に連携削除
          $table->bigInteger('user_id')->unsigned()->default(1);
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todo_lists', function (Blueprint $table) {
            //
          //$table->dropForeign('todo_lists_user_id_foreign'); // laravleの命名規則に沿った指定方法
          $table->dropForeign(['user_id']); // カラムを直接指定している
          $table->dropColumn('user_id');
        });
    }
};
