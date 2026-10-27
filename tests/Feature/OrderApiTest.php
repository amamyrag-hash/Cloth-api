<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    private function product(int $stock = 5): Product
    {
        $category = Category::create(['name' => 'Tees', 'slug' => 'tees']);

        return Product::factory()->create([
            'category_id' => $category->id,
            'price' => 20,
            'stock' => $stock,
        ]);
    }

    public function test_order_is_created_and_stock_decreases(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $product = $this->product(5);

        $this->postJson('/api/orders', [
            'items' => [['product_id' => $product->id, 'quantity' => 2]],
        ])->assertCreated();

        $this->assertEquals(3, $product->fresh()->stock);
        $this->assertDatabaseHas('orders', ['total' => 40]);
    }

    public function test_order_fails_when_stock_is_not_enough(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $product = $this->product(5);

        $this->postJson('/api/orders', [
            'items' => [['product_id' => $product->id, 'quantity' => 99]],
        ])->assertStatus(422);

        $this->assertEquals(5, $product->fresh()->stock);
    }
}