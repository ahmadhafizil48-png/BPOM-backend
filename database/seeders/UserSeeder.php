<?php
// Update UserSeeder dengan password yang benar

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing users first
        User::truncate();
        
        // Create users with correct password hash
        User::create([
            'id' => 1,
            'name' => 'Admin BPOM',
            'email' => 'admin@bpom.go.id',
            'password' => Hash::make('123456'),
            'role_id' => 1,
            'email_verified_at' => now()
        ]);

        User::create([
            'id' => 2,
            'name' => 'Dr. Siti Pembimbing',
            'email' => 'pembimbing@bpom.go.id',
            'password' => Hash::make('123456'),
            'role_id' => 2,
            'email_verified_at' => now()
        ]);

        User::create([
            'id' => 3,
            'name' => 'Andi Mahasiswa',
            'email' => 'user@bpom.go.id',
            'password' => Hash::make('123456'),
            'role_id' => 3,
            'email_verified_at' => now()
        ]);
    }
}