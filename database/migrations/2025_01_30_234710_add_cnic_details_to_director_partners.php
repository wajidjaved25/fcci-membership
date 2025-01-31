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
    Schema::table('director_partners', function (Blueprint $table) {
        $table->date('cnic_issue_date')->nullable()->after('cnic_number');
        $table->date('cnic_expiry_date')->nullable()->after('cnic_issue_date');
        $table->string('cnic_front')->nullable()->after('cnic_expiry_date');
        $table->string('cnic_back')->nullable()->after('cnic_front');
    });
}

public function down()
{
    Schema::table('director_partners', function (Blueprint $table) {
        $table->dropColumn(['cnic_issue_date', 'cnic_expiry_date', 'cnic_front', 'cnic_back']);
    });
}

};
