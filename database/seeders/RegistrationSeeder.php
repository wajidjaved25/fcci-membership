<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
             Registration::create([
            'user_id' => 1, // Replace with an existing user ID
            'company_name' => 'Test Company',
            'address' => '123 Test Street',
            'telephone' => '123456789',
            'mobile' => '987654321',
            'email' => 'test@example.com',
            'website' => 'http://test.com',
            'membership_class' => 'Corporate',
            'year_establishment' => '2020',
            'ntn' => '123456789',
            'sales_tax_number' => '987654321',
            'main_business' => 'Manufacturing',
            'product_line' => 'Test Products',
            'status' => null, // Pending status
]);
    }
}
