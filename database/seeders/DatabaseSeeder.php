<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Global\PermissionSeeder;
use Database\Seeders\Global\RoleSeeder;
use Database\Seeders\Global\UserSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
