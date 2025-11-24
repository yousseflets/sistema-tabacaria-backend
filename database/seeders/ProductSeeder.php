<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $category = Category::first();

        Product::firstOrCreate([
            'sku' => 'TAB-001'
        ], [
            'name' => 'Fumo Especial',
            'sku' => 'TAB-001',
            'price' => 19.90,
            'quantity' => 50,
            'category_id' => $category?->id,
            'description' => 'Fumo de alta qualidade para cachimbo.'
        ]);
    }
}
