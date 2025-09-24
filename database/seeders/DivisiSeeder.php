<?php

namespace Database\Seeders; // penting supaya Laravel bisa menemukan class ini

use Illuminate\Database\Seeder;
use App\Models\Divisi;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        $divisis = [
            ['nama_divisi' => 'IT Support', 'deskripsi' => 'Divisi IT Support'],
            ['nama_divisi' => 'Finance', 'deskripsi' => 'Divisi Finance'],
            ['nama_divisi' => 'HRD', 'deskripsi' => 'Divisi HRD'],
        ];

        foreach ($divisis as $divisi) {
            Divisi::create($divisi);
        }
    }
}
