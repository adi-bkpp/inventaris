<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = Category::query()
            ->whereIn('name', ['Elektronik', 'Alat Tulis', 'Furniture'])
            ->pluck('id', 'name');

        $items = [
            [
                'category_id' => $categoryIds['Alat Tulis'],
                'name' => 'Kertas A4',
                'stock' => 40,
                'price' => 55000,
            ],
            [
                'category_id' => $categoryIds['Alat Tulis'],
                'name' => 'Bulpoin',
                'stock' => 10,
                'price' => 5000,
            ],
            [
                'category_id' => $categoryIds['Alat Tulis'],
                'name' => 'Buku Tulis',
                'stock' => 25,
                'price' => 12000,
            ],
            [
                'category_id' => $categoryIds['Elektronik'],
                'name' => 'Mouse USB',
                'stock' => 12,
                'price' => 75000,
            ],
            [
                'category_id' => $categoryIds['Elektronik'],
                'name' => 'Keyboard USB',
                'stock' => 8,
                'price' => 150000,
            ],
            [
                'category_id' => $categoryIds['Furniture'],
                'name' => 'Kursi Kantor',
                'stock' => 5,
                'price' => 450000,
            ],
            [
                'category_id' => $categoryIds['Furniture'],
                'name' => 'Meja Kerja',
                'stock' => 3,
                'price' => 850000,
            ],
        ];

        foreach ($items as $item) {
            Item::updateOrCreate(
                ['name' => $item['name']],
                $item,
            );
        }
    }
}
