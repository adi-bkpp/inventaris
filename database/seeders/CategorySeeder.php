<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'description' => 'Perangkat elektronik dan aksesorinya',
            ],
            [
                'name' => 'Alat Tulis',
                'description' => 'Perlengkapan tulis dan kebutuhan administrasi',
            ],
            [
                'name' => 'Furniture',
                'description' => 'Meja, kursi, lemari, dan perlengkapan ruangan',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']],
            );
        }
    }
}
