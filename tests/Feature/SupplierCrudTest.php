<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_user_can_create_a_supplier_and_preserve_leading_zero_in_phone(): void
    {
        $response = $this->post(route('supplier.store'), [
            'name' => 'PT Sumber Makmur',
            'phone' => '0123456789',
            'address' => 'Jakarta',
        ]);

        $response->assertRedirect(route('supplier.index'));
        $this->assertDatabaseHas('suppliers', [
            'name' => 'PT Sumber Makmur',
            'phone' => '0123456789',
            'address' => 'Jakarta',
        ]);
    }

    public function test_user_can_update_a_supplier(): void
    {
        $supplier = Supplier::factory()->create();

        $response = $this->put(route('supplier.update', $supplier), [
            'name' => 'CV Berkah Jaya',
            'phone' => '012345678901234',
            'address' => 'Bandung',
        ]);

        $response->assertRedirect(route('supplier.index'));
        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'name' => 'CV Berkah Jaya',
            'phone' => '012345678901234',
            'address' => 'Bandung',
        ]);
    }

    public function test_supplier_forms_render_for_create_and_edit(): void
    {
        $supplier = Supplier::factory()->create([
            'name' => 'PT Sumber Makmur',
            'phone' => '0215550100',
        ]);

        $this->get(route('supplier.create'))
            ->assertOk()
            ->assertSee('name="name"', false)
            ->assertSee('pattern="[0-9]{10,15}"', false);

        $this->get(route('supplier.edit', $supplier))
            ->assertOk()
            ->assertSee('value="PT Sumber Makmur"', false)
            ->assertSee('value="0215550100"', false);
    }

    public function test_supplier_name_is_required(): void
    {
        $this->from(route('supplier.create'))
            ->post(route('supplier.store'), [
                'phone' => '081234567890',
            ])
            ->assertRedirect(route('supplier.create'))
            ->assertSessionHasErrors([
                'name' => 'The name field is required.',
            ]);

        $this->assertDatabaseCount('suppliers', 0);
    }

    public function test_supplier_phone_must_have_at_least_ten_digits(): void
    {
        $this->from(route('supplier.create'))
            ->post(route('supplier.store'), [
                'name' => 'Supplier Salah',
                'phone' => '123456789',
            ])
            ->assertRedirect(route('supplier.create'))
            ->assertSessionHasErrors([
                'phone' => 'The phone field must be between 10 and 15 digits.',
            ]);

        $this->assertDatabaseCount('suppliers', 0);
    }

    public function test_supplier_phone_must_not_exceed_fifteen_digits(): void
    {
        $this->from(route('supplier.create'))
            ->post(route('supplier.store'), [
                'name' => 'Supplier Salah',
                'phone' => '1234567890123456',
            ])
            ->assertRedirect(route('supplier.create'))
            ->assertSessionHasErrors([
                'phone' => 'The phone field must be between 10 and 15 digits.',
            ]);

        $this->assertDatabaseCount('suppliers', 0);
    }

    public function test_supplier_phone_must_contain_only_digits(): void
    {
        $this->from(route('supplier.create'))
            ->post(route('supplier.store'), [
                'name' => 'Supplier Salah',
                'phone' => '0812345abcde',
            ])
            ->assertRedirect(route('supplier.create'))
            ->assertSessionHasErrors([
                'phone' => 'The phone field must be between 10 and 15 digits.',
            ]);

        $this->assertDatabaseCount('suppliers', 0);
    }

    public function test_user_can_delete_a_supplier_not_used_by_items(): void
    {
        $supplier = Supplier::factory()->create();

        $response = $this->delete(route('supplier.destroy', $supplier));

        $response->assertRedirect(route('supplier.index'));
        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }

    public function test_supplier_used_by_items_cannot_be_deleted(): void
    {
        $category = Category::create(['name' => 'Elektronik']);
        $supplier = Supplier::factory()->create();
        $item = Item::create([
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'name' => 'Mouse USB',
            'stock' => 1,
            'price' => 100,
        ]);

        $response = $this->delete(route('supplier.destroy', $supplier));

        $response->assertRedirect(route('supplier.index'))
            ->assertSessionHas('error');
        $this->assertModelExists($item);
        $this->assertModelExists($supplier);
    }

    public function test_guest_is_redirected_to_login_from_supplier_module(): void
    {
        auth()->logout();

        $this->get(route('supplier.index'))
            ->assertRedirect(route('login'));

        $this->post(route('supplier.store'), [
            'name' => 'PT Sumber Makmur',
            'phone' => '081234567890',
        ])->assertRedirect(route('login'));
    }
}
