<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $testUsers = [
            ['name' => 'Admin User', 'mobile_number' => '923000000001', 'email' => 'admin@test.com', 'role' => 'admin'],
            ['name' => 'Supervisor User', 'mobile_number' => '923000000002', 'email' => 'supervisor@test.com', 'role' => 'membership_supervisor'],
            ['name' => 'Cashier User', 'mobile_number' => '923000000003', 'email' => 'cashier@test.com', 'role' => 'cashier'],
            ['name' => 'Accounts User', 'mobile_number' => '923000000004', 'email' => 'accounts@test.com', 'role' => 'accounts_audit'],
            ['name' => 'Secretary User', 'mobile_number' => '923000000005', 'email' => 'secretary@test.com', 'role' => 'dg_secretary'],
            ['name' => 'Chairman User', 'mobile_number' => '923000000006', 'email' => 'chairman@test.com', 'role' => 'chairman_president'],
        ];

        foreach ($testUsers as $userData) {
            User::updateOrCreate(
                ['mobile_number' => $userData['mobile_number']], // Check by mobile number to avoid duplicates
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('testpassword'), // Temporary password for testing
                    'role' => $userData['role'],
                    'otp' => '123456', // Fixed OTP for offline testing
                    'otp_expires_at' => Carbon::now()->addMinutes(60), // OTP valid for 1 hour
                ]
            );
        }

        $this->command->info('Test users have been created successfully.');
    }
}
