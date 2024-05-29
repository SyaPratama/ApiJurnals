<?php

namespace Database\Seeders;

use App\Models\Jurnal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurnalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Jurnal::factory()->count(200)->create();
    }
}
