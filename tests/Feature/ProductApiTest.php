<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    private function category(): Category
    {
        return Category::create(['name' => 'Tees', 'slug' => 'tees']);
    }

    public function test_guest_can_list_products(): void// این برایه نمایش محصولات 
    {
        $category = $this->category();
        Product::factory()->count(3)->create(['category_id' => $category->id]);

        $this->getJson('/api/products')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_search_filters_products(): void// این برایه سرچه محصولات
    {
        $category = $this->category();
        Product::factory()->create(['category_id' => $category->id, 'name' => 'Blue Jeans']);
        Product::factory()->create(['category_id' => $category->id, 'name' => 'Red Tee']);

        $this->getJson('/api/products?search=jeans')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_guest_cannot_create_product(): void//تست احراز هویت
    {
        $this->postJson('/api/products', [])->assertUnauthorized();
    }

    public function test_normal_user_cannot_create_product(): void// تست کاربره معمولی
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/products', [])->assertForbidden();
    }

    public function test_admin_can_create_product(): void//تست ساخت محصول توسط ادمین
    {
        $this->withoutExceptionHandling();
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));
        $category = $this->category();

        $this->postJson('/api/products', [
            'category_id' => $category->id,
            'name' => 'Black Tee',
            'slug' => 'black-tee',
            'price' => 19.99,
            'stock' => 10,
        ])->assertCreated();

        $this->assertDatabaseHas('products', ['slug' => 'black-tee']);
    }

    public function test_product_validation_fails_when_fields_missing(): void// بررسی اعتبار سنجی
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));

        $this->postJson('/api/products', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'price']);
    }
}

