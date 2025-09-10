<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Urutan penting: Role dulu, baru User
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}