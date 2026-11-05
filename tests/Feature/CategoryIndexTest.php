<?php

namespace Tests\Feature;

use Tests\TestCase;

class CategoryIndexTest extends TestCase
{
    public function test_category_index_displays_four_categories(): void
    {
        $response = $this->get(route('category.index'));

        $response->assertOk()
            ->assertSeeText('Elektronik')
            ->assertSeeText('Furniture')
            ->assertSeeText('Alat Tulis')
            ->assertSeeText('Peralatan Kebersihan');
    }
}
