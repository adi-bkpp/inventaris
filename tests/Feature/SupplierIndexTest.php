<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierIndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_supplier_index_displays_supplier_details(): void
    {
        Supplier::factory()->create([
            'name' => 'PT Sumber Makmur',
            'phone' => '0215550100',
            'address' => 'Jakarta',
        ]);

        $this->get(route('supplier.index'))
            ->assertOk()
            ->assertSeeText('PT Sumber Makmur')
            ->assertSeeText('0215550100')
            ->assertSeeText('Jakarta');
    }

    public function test_supplier_index_searches_by_name_or_phone(): void
    {
        Supplier::factory()->create([
            'name' => 'PT Sumber Makmur',
            'phone' => '0215550100',
        ]);
        Supplier::factory()->create([
            'name' => 'CV Berkah Jaya',
            'phone' => '081234567890',
        ]);

        $this->get(route('supplier.index', ['search' => 'Sumber']))
            ->assertOk()
            ->assertSeeText('PT Sumber Makmur')
            ->assertDontSeeText('CV Berkah Jaya');

        $this->get(route('supplier.index', ['search' => '081234']))
            ->assertOk()
            ->assertSeeText('CV Berkah Jaya')
            ->assertDontSeeText('PT Sumber Makmur');
    }

    public function test_supplier_index_paginates_results(): void
    {
        Supplier::factory()->count(11)->create();

        $this->get(route('supplier.index'))
            ->assertOk()
            ->assertSee('page=2', false);

        $this->get(route('supplier.index', ['page' => 2]))
            ->assertOk()
            ->assertSeeText('Menampilkan 11–11 dari 11 supplier');
    }
}
