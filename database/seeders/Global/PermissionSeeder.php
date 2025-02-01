<?php

namespace Database\Seeders\Global;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            'Dashboard' => [
                'view dashboard', // global api (butuh login)
                'edit dashboard',
            ],

            // Bidang
            'Bidang' => [
                'view bidang',
                'create bidang',
                'edit bidang',
                'delete bidang',
            ],

            // GeoJson
            'Geojson' => [
                'view geojson',
            ],

            // Pricing
            'Pricing' => [
                'view pricing',
                'create pricing',
                'edit pricing',
                'delete pricing',
            ],
        ];

        foreach ($permissions as $group => $perms) {
            foreach ($perms as $permissions) {
                Permission::create(['name' => $permissions, 'group' => $group]);
            }
        }
    }
}
