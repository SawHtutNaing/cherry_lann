<?php

namespace Database\Seeders;

use App\Models\BoostType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoostTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BoostType::create([
            'name' => 'Message'
        ]);
        BoostType::create([
            'name' => 'Engagement'
        ]);
    }
}
