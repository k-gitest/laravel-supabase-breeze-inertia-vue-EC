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
        $this->call([
            UsersSeeder::class,
            AdminsSeeder::class,
            CategoriesSeeder::class,
            TodoListsSeeder::class,
            ProductsSeeder::class,
            WarehousesSeeder::class,
            StocksSeeder::class,
            CommentsSeeder::class,
            ContactsSeeder::class,
            FavoritesSeeder::class,
            CartsSeeder::class,
        ]);
    }
}
