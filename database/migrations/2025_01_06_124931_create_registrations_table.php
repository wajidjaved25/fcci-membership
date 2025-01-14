<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained('users'); // Allow null for user_id
    $table->foreignId('form_id')->constrained('registration_forms');
    $table->string('company_name');
    $table->string('address');
    $table->string('telephone')->nullable();
    $table->string('mobile');
    $table->string('email')->nullable();
    $table->string('website')->nullable();
    $table->string('Testimonial_1');
    $table->string('Testimonial_2');
    $table->string('membership_class');
    $table->string('year_establisment');
    $table->string('ntn')->nullable();
    $table->string('sales_tax_number')->nullable();
    $table->string('main_business');
    $table->string('product_line');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
