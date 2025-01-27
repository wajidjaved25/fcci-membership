<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'membership_supervisor'],
            ['name' => 'cashier'],
            ['name' => 'accounts_audit'],
            ['name' => 'dg_secretary'],
            ['name' => 'chairman_president'],
        ]);
    }
}