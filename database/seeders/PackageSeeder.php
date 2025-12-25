<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('packages')->insert([
            [
                'package_name' => 'Standard',
                'tier' => 'standard',
                'package_icon' => 'mdi-account-check',
                'package_price' => 0.00,
                'stripe_id' => 'price_1Sd9AF1cIgh2RXQv_standard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Approved',
                'tier' => 'approved',
                'package_icon' => 'mdi-account-star',
                'package_price' => 19.99,
                'stripe_id' => 'price_1Sd9AF1cIgh2RXQv_approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Premium',
                'tier' => 'premium',
                'package_icon' => 'mdi-shield-account',
                'package_price' => 29.99,
                'stripe_id' => 'price_1Sd9AF1cIgh2RXQv_premium',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
