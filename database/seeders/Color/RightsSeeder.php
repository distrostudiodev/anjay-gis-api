<?php

namespace Database\Seeders\Color;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('color_rights')->insert([
            [
                'label' => 'rights_hgb',
                'color' => '#e7c144',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'rights_hgu',
                'color' => '#7fca8f',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'rights_hm',
                'color' => '#6797f7',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'rights_hp',
                'color' => '#8357f2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'rights_unknown',
                'color' => '#e1e3e9',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
