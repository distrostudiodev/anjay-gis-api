<?php

namespace Database\Seeders\Global;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $super_admin_account = User::create([
            'name' => 'Super Admin',
            'username' => 'super.admin',
            'email' => 'distrostudiodev@gmail.com',
            'account_status' => 2,
            'password' => Hash::make('superadmin123'),
            'register_at' => now('Asia/Jakarta')
        ]);
        
        $super_admin_account->assignRole('Super Admin');
    }
}
