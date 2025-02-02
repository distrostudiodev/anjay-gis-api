<?php

namespace Database\Seeders\Color;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('color_fields')->insert([
            [
                'label' => 'field_type_agriculture',
                'color' => '#a1c349',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'field_type_residential',
                'color' => '#f4a261',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'field_type_commercial',
                'color' => '#2a9d8f',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
