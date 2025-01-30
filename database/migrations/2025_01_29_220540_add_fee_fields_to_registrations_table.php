<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeeFieldsToRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->decimal('fee_paid', 10, 2)->nullable();
            $table->timestamp('fee_paid_at')->nullable();
            $table->string('payment_status')->default('pending'); // pending, paid
        });
    }

    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['fee_paid', 'fee_paid_at', 'payment_status']);
        });
    }
}