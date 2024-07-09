<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(TodoListsSeeder::class);
        $this->call(ProductsSeeder::class);
        $this->call(WarehousesSeeder::class);
        $this->call(StocksSeeder::class);
    }
}
