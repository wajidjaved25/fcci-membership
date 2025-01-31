<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('firm_type')->nullable()->after('membership_class');
        });
    }
    
    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('firm_type');
        });
    }
};
