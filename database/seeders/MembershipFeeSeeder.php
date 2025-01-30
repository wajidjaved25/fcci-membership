<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MembershipFee;

class MembershipFeeSeeder extends Seeder
{
    public function run()
    {
        MembershipFee::updateOrCreate(['membership_class' => 'Corporate'], ['fee_amount' => 15162]);
        MembershipFee::updateOrCreate(['membership_class' => 'Associate'], ['fee_amount' => 7854]);
    }
}
