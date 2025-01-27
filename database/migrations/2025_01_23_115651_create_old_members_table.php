<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOldMembersTable extends Migration
{
    public function up()
    {
        Schema::create('old_members', function (Blueprint $table) {
            $table->id();
	    $table->string('Company_Name');
            $table->string('ACCNO')->unique();
            $table->string('name');
	    $table->string('ACADDRESS');
            $table->string('CELL_NO');
	    $table->string('CNIC');
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('old_members');
    }
}
