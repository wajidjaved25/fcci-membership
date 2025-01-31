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
    Schema::table('document_requirements', function (Blueprint $table) {
        $table->boolean('is_required')->default(1)->after('document_name'); // Default is required
    });
}

public function down()
{
    Schema::table('document_requirements', function (Blueprint $table) {
        $table->dropColumn('is_required');
    });
}

};
