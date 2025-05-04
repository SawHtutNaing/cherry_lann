<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'WZO',
            'email' => 'wzo@cherrylann.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 1

        ]);

        User::create([
            'name' => 'Developer',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'admin',
            'status' => 1
        ]);
    }
}
