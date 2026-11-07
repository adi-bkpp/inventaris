<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'PT Sumber Makmur',
                'phone' => '0215550100',
                'address' => 'Jakarta',
            ],
            [
                'name' => 'CV Berkah Jaya',
                'phone' => '0215550200',
                'address' => 'Bandung',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['name' => $supplier['name']],
                $supplier,
            );
        }
    }
}
