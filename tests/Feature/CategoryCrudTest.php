<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_user_can_create_a_category(): void
    {
        $response = $this->post(route('category.store'), [
            'name' => 'Furniture',
            'description' => 'Meja dan kursi',
        ]);

        $response->assertRedirect(route('category.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Furniture',
            'description' => 'Meja dan kursi',
        ]);
    }

    public function test_user_can_update_a_category(): void
    {
        $category = Category::create(['name' => 'ATK']);

        $response = $this->put(route('category.update', $category), [
            'name' => 'Alat Tulis',
            'description' => 'Alat tulis kantor',
        ]);

        $response->assertRedirect(route('category.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Alat Tulis',
            'description' => 'Alat tulis kantor',
        ]);
    }

    public function test_user_can_delete_an_unused_category(): void
    {
        $category = Category::create(['name' => 'Furniture']);

        $response = $this->delete(route('category.destroy', $category));

        $response->assertRedirect(route('category.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_category_in_use_cannot_be_deleted(): void
    {
        $category = Category::create(['name' => 'Elektronik']);
        Item::create([
            'category_id' => $category->id,
            'name' => 'Mouse USB',
            'stock' => 1,
            'price' => 100,
        ]);

        $response = $this->delete(route('category.destroy', $category));

        $response->assertRedirect(route('category.index'))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
