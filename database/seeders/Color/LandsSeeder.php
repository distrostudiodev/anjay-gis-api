<?php

namespace Database\Seeders\Color;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('color_lands')->insert([
            [
                'label' => 'land_type_lsd',
                'color' => '#92dc7d',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'land_type_na',
                'color' => '#dd9459',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'land_type_normal',
                'color' => '#6499f7',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
