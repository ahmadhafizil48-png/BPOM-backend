<?php

use Illuminate\Database\Seeder;
use App\Models\Divisi;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        Divisi::create(['nama' => 'IT Support']);
        Divisi::create(['nama' => 'Finance']);
        Divisi::create(['nama' => 'HRD']);
    }
}


