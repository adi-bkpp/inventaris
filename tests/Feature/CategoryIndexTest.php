<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryIndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_category_index_displays_three_seeded_categories(): void
    {
        $this->seed(CategorySeeder::class);

        $response = $this->get(route('category.index'));

        $response->assertOk()
            ->assertSeeText('Elektronik')
            ->assertSeeText('Alat Tulis')
            ->assertSeeText('Furniture');

        $this->assertDatabaseCount('categories', 3);
    }

    public function test_category_index_paginates_results(): void
    {
        foreach (range(1, 6) as $number) {
            Category::create(['name' => "Kategori {$number}"]);
        }

        $this->get(route('category.index'))
            ->assertOk()
            ->assertSee('page=2', false);

        $this->get(route('category.index', ['page' => 2]))
            ->assertOk()
            ->assertSeeText('Kategori 6');
    }

    public function test_category_index_searches_name_and_description(): void
    {
        Category::create([
            'name' => 'Elektronik',
            'description' => 'Perangkat untuk kantor',
        ]);
        Category::create([
            'name' => 'Furniture',
            'description' => 'Meja dan kursi',
        ]);

        $this->get(route('category.index', ['search' => 'kursi']))
            ->assertOk()
            ->assertSeeText('Furniture')
            ->assertDontSeeText('Elektronik');
    }
}
