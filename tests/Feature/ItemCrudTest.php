<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_user_can_upload_a_photo_when_creating_an_item(): void
    {
        Storage::fake('public');
        $category = Category::create(['name' => 'Elektronik']);
        $photo = $this->fakePng('mouse.png');

        $response = $this->post(route('item.store'), [
            'category_id' => $category->id,
            'name' => 'Mouse USB',
            'stock' => 12,
            'price' => 75000,
            'photo' => $photo,
        ]);

        $response->assertRedirect(route('item.index'));
        $item = Item::where('name', 'Mouse USB')->firstOrFail();
        $this->assertModelExists($item);
        $this->assertStringStartsWith('items/', $item->photo);
        Storage::disk('public')->assertExists($item->photo);
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

    public function test_updating_an_item_without_a_new_photo_keeps_its_current_photo(): void
    {
        Storage::fake('public');
        $category = Category::create(['name' => 'Elektronik']);
        Storage::disk('public')->put('items/mouse.jpg', 'photo');
        $item = Item::create([
            'category_id' => $category->id,
            'name' => 'Mouse USB',
            'stock' => 12,
            'price' => 75000,
            'photo' => 'items/mouse.jpg',
        ]);

        $response = $this->put(route('item.update', $item), [
            'category_id' => $category->id,
            'name' => 'Mouse Wireless',
            'stock' => 8,
            'price' => 125000,
        ]);

        $response->assertRedirect(route('item.index'));
        $this->assertSame('items/mouse.jpg', $item->fresh()->photo);
        Storage::disk('public')->assertExists('items/mouse.jpg');
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

    public function test_user_can_replace_an_item_photo_and_the_old_photo_is_removed(): void
    {
        Storage::fake('public');
        $category = Category::create(['name' => 'Elektronik']);
        Storage::disk('public')->put('items/old-photo.jpg', 'old photo');
        $item = Item::create([
            'category_id' => $category->id,
            'name' => 'Mouse USB',
            'stock' => 12,
            'price' => 75000,
            'photo' => 'items/old-photo.jpg',
        ]);

        $response = $this->put(route('item.update', $item), [
            'category_id' => $category->id,
            'name' => 'Mouse USB',
            'stock' => 12,
            'price' => 75000,
            'photo' => $this->fakePng('new-photo.png'),
        ]);

        $response->assertRedirect(route('item.index'));
        $item->refresh();
        $this->assertNotSame('items/old-photo.jpg', $item->photo);
        Storage::disk('public')->assertMissing('items/old-photo.jpg');
        Storage::disk('public')->assertExists($item->photo);
    }

    public function test_invalid_item_photos_are_rejected(): void
    {
        Storage::fake('public');
        $category = Category::create(['name' => 'Elektronik']);
        $itemData = [
            'category_id' => $category->id,
            'name' => 'Mouse USB',
            'stock' => 12,
            'price' => 75000,
        ];

        $this->from(route('item.create'))
            ->post(route('item.store'), [
                ...$itemData,
                'photo' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect(route('item.create'))
            ->assertSessionHasErrors('photo');

        $this->from(route('item.create'))
            ->post(route('item.store'), [
                ...$itemData,
                'photo' => $this->fakePng('too-large.png')->size(2049),
            ])
            ->assertRedirect(route('item.create'))
            ->assertSessionHasErrors('photo');

        $this->from(route('item.create'))
            ->post(route('item.store'), [
                ...$itemData,
                'photo' => $this->fakePng('wrong-extension.txt'),
            ])
            ->assertRedirect(route('item.create'))
            ->assertSessionHasErrors('photo');

        $this->assertDatabaseMissing('items', ['name' => 'Mouse USB']);
        $this->assertSame([], Storage::disk('public')->allFiles('items'));
    }

    public function test_user_can_delete_an_item(): void
    {
        Storage::fake('public');
        $category = Category::create(['name' => 'Elektronik']);
        Storage::disk('public')->put('items/mouse.jpg', 'photo');
        $item = Item::create([
            'category_id' => $category->id,
            'name' => 'Mouse USB',
            'stock' => 12,
            'price' => 75000,
            'photo' => 'items/mouse.jpg',
        ]);

        $response = $this->delete(route('item.destroy', $item));

        $response->assertRedirect(route('item.index'));
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
        Storage::disk('public')->assertMissing('items/mouse.jpg');
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

        $this->get(route('item.create'))
            ->assertOk()
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('name="photo"', false)
            ->assertSee('accept=".jpg,.jpeg,.png,.webp"', false);

        $this->get(route('item.edit', $item))
            ->assertOk()
            ->assertSeeText('Edit Item')
            ->assertSee('value="Mouse USB"', false)
            ->assertSee('value="75000"', false)
            ->assertDontSee('value="75000.00"', false);
    }

    public function test_edit_form_displays_the_current_item_photo(): void
    {
        Storage::fake('public');
        $category = Category::create(['name' => 'Elektronik']);
        Storage::disk('public')->put('items/mouse.jpg', 'photo');
        $item = Item::create([
            'category_id' => $category->id,
            'name' => 'Mouse USB',
            'stock' => 12,
            'price' => 75000,
            'photo' => 'items/mouse.jpg',
        ]);

        $this->get('http://inventaris.test/item/'.$item->id.'/edit')
            ->assertOk()
            ->assertSee('src="http://inventaris.test/storage/items/mouse.jpg"', false)
            ->assertSee('alt="Foto Mouse USB"', false);
    }

    private function fakePng(string $filename): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            $filename,
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAASsJTYQAAAAASUVORK5CYII='),
        );
    }
}
