<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'leticiayoussef.si@gmail.com'],
            [
                'name' => 'Leticia Youssef',
                'password' => bcrypt('123456'),
            ]
        );
    }
}
