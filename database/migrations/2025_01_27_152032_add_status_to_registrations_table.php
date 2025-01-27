<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('status')->default('pending'); // 'pending', 'fee_due', 'fee_paid', 'provisionally_approved', 'final_approval'
            $table->text('rejection_reason')->nullable();
        });
    }

    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['status', 'rejection_reason']);
        });
    }
}

