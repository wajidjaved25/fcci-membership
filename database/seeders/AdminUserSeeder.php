<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Wajid Javed',
            'email' => 'wajidjaved2512@gmail.com',
            'password' => Hash::make('Pa$$w0rd123'), // Use a secure password
            'mobile_number' => '923180538512', // Replace with actual admin mobile number
            'role' => 'admin',
        ]);
	User::create([
            'name' => 'Bilal Sharif Sandhu',
            'email' => 'info@fcci.com.pk',
            'password' => Hash::make('Pa$$w0rd123'), // Use a secure password
            'mobile_number' => '923338387487', // Replace with actual admin mobile number
            'role' => 'admin',
        ]);
    }
}
