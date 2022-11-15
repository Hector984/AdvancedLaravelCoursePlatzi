<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(
            User::factory()->create()
        );
    }

    public function test_index()
    {

        Product::factory(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonCount(5, 'data');
    }

    public function test_create_new_product()
    {

        $data = [
            'name' => 'Hola',
            'price' => 1000,
        ];

        $response = $this->json('POST', '/api/products', $data);

        $response
            ->assertSuccessful()
            ->assertHeader('content-type', 'application/json');

        $this->assertDatabaseHas('products', $data);
    }

    public function test_update_product()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $data = [
            'name' => 'Update Product',
            'price' => 20000,
        ];

        $response = $this->json('PATCH', "/api/products/{$product->getKey()}", $data);

        $response
            ->assertSuccessful()
            ->assertHeader('content-type', 'application/json');

    }

    public function test_show_product()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->getKey()}");

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
    }

    public function test_delete_product()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->getKey()}");

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $this->assertDeleted($product);
    }

    public function test_a_product_belongs_to_category()
    {
        $category = Category::factory()->create();

        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
    }

    public function test_a_product_belongs_to_user()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create();

        $products = Product::factory()->create([
            'category_id' => $category->id,
            'created_by' => $user->id,
            'name' => 'Balon',
            'price' => 2000
        ]);

        $this->assertInstanceOf(Product::class, $user->products->first());
    }

}
