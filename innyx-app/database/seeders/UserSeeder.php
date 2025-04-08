<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123123'),
            'role_id' => 1,
        ]);

        User::create([
            'name' => 'Common User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role_id' => 2,
        ]);
    }
}
