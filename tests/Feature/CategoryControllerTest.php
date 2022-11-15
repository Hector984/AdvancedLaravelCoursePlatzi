<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(
            User::factory()->create()
        );
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index()
    {
        Category::factory(5)->create();

        //Vamos a la ruta que me enlista todas las categorias
        $response = $this->getjson('/api/categories');

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonCount(6, 'data');

    }

    public function test_new_category()
    {
        $data = [
            'name' => "Deportes"
        ];

        $response = $this->postJson('/api/categories', $data);

        $response->assertSuccessful()
                ->assertHeader('content-type', 'application/json');

        $this->assertDatabaseHas('categories', $data);
    }

    public function test_update_category()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $data = [
            'name' => "Carros"
        ];

        $response = $this->patchJson("api/categories/{$category->getKey()}", $data);

        $response->assertSuccessful()
                ->assertHeader('content-type', 'application/json');
    }

    public function test_show_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson("api/categories/{$category->getKey()}");

        $response->assertSuccessful()
                 ->assertHeader('content-type','application/json');
    }

    public function test_delete_category()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->deleteJson("api/categories/{$category->getKey()}");

        $response->assertSuccessful()
                 ->assertHeader('content-type', 'application/json');

        $this->assertDeleted($category);
    }

    public function test_category_has_many_products()
    {
        $category = Category::factory()->create([
            'name' => 'deportes'
        ]);

        $category->products()->create([
            'name' => 'Balones',
            'price' => 100
        ]);

        $this->assertInstanceOf(Product::class, $category->products->first());
    }

}
