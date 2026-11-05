<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardSummaryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_dashboard_displays_category_and_item_counts_from_database(): void
    {
        $firstCategory = Category::create(['name' => 'Elektronik']);
        $secondCategory = Category::create(['name' => 'Furniture']);

        Item::create([
            'category_id' => $firstCategory->id,
            'name' => 'Mouse USB',
            'stock' => 5,
            'price' => 75000,
        ]);
        Item::create([
            'category_id' => $secondCategory->id,
            'name' => 'Kursi Kantor',
            'stock' => 2,
            'price' => 500000,
        ]);
        Item::create([
            'category_id' => $firstCategory->id,
            'name' => 'Keyboard USB',
            'stock' => 3,
            'price' => 150000,
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSeeText('2')
            ->assertSeeText('3')
            ->assertSee('bg-dark', false)
            ->assertDontSee('text-bg-success', false)
            ->assertDontSee('text-bg-primary', false)
            ->assertDontSee('>K</span>', false)
            ->assertDontSee('>I</span>', false);
    }
}
