<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipFeesTable extends Migration
{
    public function up()
    {
        Schema::create('membership_fees', function (Blueprint $table) {
            $table->id();
            $table->string('membership_class')->unique(); // Corporate, Associate
            $table->decimal('fee_amount', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('membership_fees');
    }
}