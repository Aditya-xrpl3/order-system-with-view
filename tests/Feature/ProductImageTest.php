<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProductImageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function test_admin_can_upload_product_image()
    {
        Storage::fake('public');
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'Test Product',
            'price' => 10000,
            'stock' => 10,
            'image' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = \App\Models\Product::where('name', 'Test Product')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->image_url);

        // Cek file sesuai path di database, bukan 'products/test.jpg'
        Storage::disk('public')->assertExists($product->image_url);
    }
}
