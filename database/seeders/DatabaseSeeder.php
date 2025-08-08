<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        // Cashier
        User::factory()->create([
            'name' => 'Cashier',
            'email' => 'cashier@example.com',
            'role' => 'cashier',
            'password' => Hash::make('cashier123'),
        ]);

        // User
        User::factory()->create([
            'name' => 'Customer',
            'email' => 'user@example.com',
            'role' => 'user',
            'password' => Hash::make('user123'),
        ]);

        // Products
        Product::create([
            'name' => 'Nasi Goreng',
            'description' => 'Nasi goreng spesial',
            'price' => 20000,
        ]);
        Product::create([
            'name' => 'Es Teh',
            'description' => 'Minuman segar',
            'price' => 5000,
        ]);

        // Tables
        for ($i = 1; $i <= 10; $i++) {
            Table::create([
                'number' => 'Meja ' . $i,
            ]);
        }
    }
}
