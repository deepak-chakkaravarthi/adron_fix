<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            ['name' => 'Supplier A', 'contact_info' => 'contact@supplierA.com'],
            ['name' => 'Supplier B', 'contact_info' => 'contact@supplierB.com'],
            ['name' => 'Supplier C', 'contact_info' => 'contact@supplierC.com'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
