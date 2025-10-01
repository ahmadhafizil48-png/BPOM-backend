<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua data lama, aman walau ada foreign key
        DB::table('users')->delete();

        // Reset auto increment (opsional, biar mulai dari 1 lagi)
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');

        // Tambah user Admin
        User::create([
            'name' => 'Admin BPOM',
            'email' => 'admin@bpom.go.id',
            'password' => Hash::make('123456'),
            'role_id' => 1,
            
        ]);

        // Tambah user Pembimbing
        User::create([
            'name' => 'Dr. Siti Pembimbing',
            'email' => 'pembimbing@bpom.go.id',
            'password' => Hash::make('123456'),
            'role_id' => 2,
            
        ]);

        // Tambah user Mahasiswa
        User::create([
            'name' => 'Andi Mahasiswa',
            'email' => 'user@bpom.go.id',
            'password' => Hash::make('123456'),
            'role_id' => 3,
            
        ]);
    }
}
