<?php

namespace Tests\Feature;

use Database\Seeders\CategorySeeder;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventorySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_and_item_seeders_create_expected_records_and_are_repeatable(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ItemSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(ItemSeeder::class);

        $this->assertDatabaseCount('categories', 3);
        $this->assertDatabaseCount('items', 7);
        $this->assertDatabaseHas('items', [
            'name' => 'Kertas A4',
        ]);
    }
}
