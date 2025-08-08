<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Table;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderSystemTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // Seed database
        $this->artisan('db:seed');
    }

    /** @test */
    public function admin_can_access_admin_routes()
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    /** @test */
    public function cashier_cannot_access_admin_routes()
    {
        $cashier = User::where('role', 'cashier')->first();
        $response = $this->actingAs($cashier)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_create_order()
    {
        $user = User::where('role', 'user')->first();
        $product = Product::first();
        $table = Table::first();

        $response = $this->actingAs($user)->post('/order', [
            'table_id' => $table->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ]
            ]
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'table_id' => $table->id,
        ]);
    }

    /** @test */
    public function user_cannot_access_cashier_routes()
    {
        $user = User::where('role', 'user')->first();
        $response = $this->actingAs($user)->get('/cashier/orders');
        $response->assertStatus(403);
    }
}
