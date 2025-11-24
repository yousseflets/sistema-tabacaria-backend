<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $cats = [
            ['name' => 'Tabacos', 'slug' => 'tabacos'],
            ['name' => 'Acessórios', 'slug' => 'acessorios'],
            ['name' => 'Charutos', 'slug' => 'charutos'],
        ];

        foreach ($cats as $c) {
            Category::firstOrCreate(['slug' => $c['slug']], $c);
        }
    }
}
