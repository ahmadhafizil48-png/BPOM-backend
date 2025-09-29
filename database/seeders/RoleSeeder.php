<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator sistem'],
            ['name' => 'pembimbing', 'description' => 'Pembimbing magang'],
            ['name' => 'user', 'description' => 'Mahasiswa/User biasa'],
            ['name' => 'koordinator', 'description' => 'Koordinator program magang'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}