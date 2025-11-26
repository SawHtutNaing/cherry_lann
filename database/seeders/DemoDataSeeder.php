<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Boost Types
        $boostTypes = [
            ['name' => 'Standard Boost', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Premium Boost', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Elite Boost', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mega Boost', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('boost_types')->insert($boostTypes);

        // Seed Users
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('users')->insert($users);

        // Seed Data Inputs (100 records)
        $customerNames = [
            'Aung Ko Ko', 'Ma Aye Aye', 'Kyaw Zin', 'Su Su Hlaing', 'Min Min Oo',
            'Thiha Zaw', 'Nyan Lin', 'Htet Htet', 'Zaw Win', 'Mya Mya',
            'Ko Ko Lwin', 'Hnin Hnin', 'Phyo Wai', 'Khin Khin', 'Soe Soe',
            'Win Win', 'Thant Zin', 'Ei Ei', 'Kyaw Kyaw', 'May May'
        ];

        $pageNames = [
            'Fashion Store Myanmar', 'Tech Hub Yangon', 'Food Paradise',
            'Beauty Corner', 'Electronics World', 'Kids Paradise',
            'Sports Zone', 'Home Decor', 'Travel Myanmar', 'Education Center',
            'Health & Wellness', 'Pet Shop', 'Book Store', 'Music Studio',
            'Car Accessories', 'Mobile Shop', 'Jewelry Store', 'Cafe Express'
        ];

        $statuses = [0, 1, 2]; // pending, completed, cancelled
        $mmKyatRates = [2500, 2600, 2700, 2800, 2900, 3000];
        $remarks = [
            'Customer requested early delivery',
            'Payment confirmed via bank transfer',
            'Special discount applied',
            'VIP customer',
            'Rush order',
            null,
            null,
            null
        ];

        $dataInputs = [];
        for ($i = 1; $i <= 100; $i++) {
            $amount = rand(50, 500);
            $mmKyat = $mmKyatRates[array_rand($mmKyatRates)];
            $discount = rand(0, 20);
            $totalAmount = ($amount * $mmKyat) * (1 - $discount / 100);
            $remark = $remarks[array_rand($remarks)];

            $dataInputs[] = [
                'user_id' => rand(1, 5),
                'customer_name' => $customerNames[array_rand($customerNames)],
                'page_name' => $pageNames[array_rand($pageNames)],
                'phone' => '09' . rand(100000000, 999999999),
                'boost_type_id' => rand(1, 4),
                'start_date' => Carbon::now()->subDays(rand(0, 90))->format('Y-m-d'),
                'amount' => $amount,
                'mm_kyat' => $mmKyat,
                'total_amount' => $totalAmount,
                'status' => $statuses[array_rand($statuses)],
                'discount' => $discount,
                'is_remark' => $remark ? 1 : 0,
                'remark' => $remark,
                'created_at' => Carbon::now()->subDays(rand(0, 90)),
                'updated_at' => Carbon::now()->subDays(rand(0, 30)),
            ];
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($dataInputs, 50) as $chunk) {
            DB::table('data_inputs')->insert($chunk);
        }

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('- 4 Boost Types created');
        $this->command->info('- 5 Users created (1 admin, 4 users)');
        $this->command->info('- 100 Data Inputs created');
        $this->command->info('Login credentials: admin@example.com / password');
    }
}
