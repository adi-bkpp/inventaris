<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_user_can_create_an_item(): void
    {
        $category = Category::create(['name' => 'Elektronik']);

        $response = $this->post(route('item.store'), [
            'category_id' => $category->id,
            'name' => 'Mouse USB',
            'stock' => 12,
            'price' => 75000,
        ]);

        $response->assertRedirect(route('item.index'));
        $this->assertDatabaseHas('items', [
            'category_id' => $category->id,
            'name' => 'Mouse USB',
            'stock' => 12,
            'price' => 75000,
        ]);
    }

    public function test_user_can_update_an_item(): void
    {
        $category = Category::create(['name' => 'Elektronik']);
        $item = Item::create([
            'category_id' => $category->id,
            'name' => 'Mouse USB',
            'stock' => 12,
            'price' => 75000,
        ]);

        $response = $this->put(route('item.update', $item), [
            'category_id' => $category->id,
            'name' => 'Mouse Wireless',
            'stock' => 8,
            'price' => 125000,
        ]);

        $response->assertRedirect(route('item.index'));
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => 'Mouse Wireless',
            'stock' => 8,
            'price' => 125000,
        ]);
    }

    public function test_item_prices_can_include_cents(): void
    {
        $category = Category::create(['name' => 'Elektronik']);

        $response = $this->post(route('item.store'), [
            'category_id' => $category->id,
            'name' => 'Adaptor',
            'stock' => 1,
            'price' => '1234.56',
        ]);

        $response->assertRedirect(route('item.index'));
        $item = Item::where('name', 'Adaptor')->firstOrFail();
        $this->assertSame('1234.56', (string) $item->price);

        $this->put(route('item.update', $item), [
            'category_id' => $category->id,
            'name' => 'Adaptor',
            'stock' => 1,
            'price' => '987.65',
        ])->assertRedirect(route('item.index'));

        $this->assertSame('987.65', (string) $item->fresh()->price);
    }

    public function test_user_can_delete_an_item(): void
    {
        $category = Category::create(['name' => 'Elektronik']);
        $item = Item::create([
            'category_id' => $category->id,
            'name' => 'Mouse USB',
            'stock' => 12,
            'price' => 75000,
        ]);

        $response = $this->delete(route('item.destroy', $item));

        $response->assertRedirect(route('item.index'));
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    public function test_item_forms_are_displayed(): void
    {
        $category = Category::create(['name' => 'Elektronik']);
        $item = Item::create([
            'category_id' => $category->id,
            'name' => 'Mouse USB',
            'stock' => 12,
            'price' => 75000,
        ]);

        $this->get(route('item.create'))
            ->assertOk()
            ->assertSeeText('Tambah Item');

        $this->get(route('item.edit', $item))
            ->assertOk()
            ->assertSeeText('Edit Item')
            ->assertSee('value="Mouse USB"', false)
            ->assertSee('value="75000"', false)
            ->assertDontSee('value="75000.00"', false);
    }
}
