<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_item_index_displays_items_with_their_category_stock_and_price(): void
    {
        $category = Category::create([
            'name' => 'Elektronik',
            'description' => 'Perangkat elektronik',
        ]);

        Item::create([
            'category_id' => $category->id,
            'name' => 'Mouse USB',
            'stock' => 12,
            'price' => '75000.50',
        ]);

        $response = $this->get(route('item.index'));

        $response->assertOk()
            ->assertSeeText('Mouse USB')
            ->assertSeeText('Elektronik')
            ->assertSeeText('12')
            ->assertSeeText('Rp75.000,50');
    }

    public function test_item_index_uses_the_inventory_favicon(): void
    {
        $response = $this->get(route('item.index'));

        $response->assertOk()
            ->assertSee('<link rel="icon" type="image/svg+xml" href="'.asset('favicon.svg').'">', false);

        $this->assertFileExists(public_path('favicon.svg'));
    }

    public function test_item_index_displays_the_item_photo(): void
    {
        Storage::fake('public');
        $category = Category::create(['name' => 'Elektronik']);
        Storage::disk('public')->put('items/mouse.jpg', 'photo');
        Item::create([
            'category_id' => $category->id,
            'name' => 'Mouse USB',
            'stock' => 12,
            'price' => 75000,
            'photo' => 'items/mouse.jpg',
        ]);

        $this->get('http://inventaris.test/item')
            ->assertOk()
            ->assertSee('src="http://inventaris.test/storage/items/mouse.jpg"', false)
            ->assertSee('alt="Foto Mouse USB"', false);
    }

    public function test_items_with_stock_below_five_are_highlighted_orange(): void
    {
        $category = Category::create(['name' => 'Elektronik']);

        Item::create([
            'category_id' => $category->id,
            'name' => 'Stok Rendah',
            'stock' => 4,
            'price' => 100,
        ]);
        Item::create([
            'category_id' => $category->id,
            'name' => 'Stok Cukup',
            'stock' => 5,
            'price' => 100,
        ]);

        $this->get(route('item.index'))
            ->assertOk()
            ->assertSee('style="background-color: #fd7e14"', false)
            ->assertSee('class="badge text-bg-success"', false);
    }

    public function test_item_index_searches_by_item_or_category_name(): void
    {
        $electronics = Category::create(['name' => 'Elektronik']);
        $furniture = Category::create(['name' => 'Furniture']);

        Item::create([
            'category_id' => $electronics->id,
            'name' => 'Mouse USB',
            'stock' => 12,
            'price' => 75000,
        ]);
        Item::create([
            'category_id' => $furniture->id,
            'name' => 'Kursi Kantor',
            'stock' => 4,
            'price' => 500000,
        ]);

        $this->get(route('item.index', ['search' => 'Elektronik']))
            ->assertOk()
            ->assertSeeText('Mouse USB')
            ->assertDontSeeText('Kursi Kantor');

        $this->get(route('item.index', ['search' => 'Kursi']))
            ->assertOk()
            ->assertSeeText('Kursi Kantor')
            ->assertDontSeeText('Mouse USB');
    }

    public function test_item_index_paginates_results(): void
    {
        $category = Category::create(['name' => 'Elektronik']);

        foreach (range(1, 11) as $number) {
            Item::create([
                'category_id' => $category->id,
                'name' => sprintf('Item %02d', $number),
                'stock' => 1,
                'price' => 100,
            ]);
        }

        $this->get(route('item.index'))
            ->assertOk()
            ->assertSee('page=2', false)
            ->assertSeeText('Menampilkan 1–10 dari 11 item');

        $this->get(route('item.index', ['page' => 2]))
            ->assertOk()
            ->assertSeeText('Item 11')
            ->assertSeeText('Menampilkan 11–11 dari 11 item');
    }
}
