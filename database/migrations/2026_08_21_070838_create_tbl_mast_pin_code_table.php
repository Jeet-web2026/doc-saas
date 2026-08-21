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
        Schema::create('tbl_mast_pin_code', function (Blueprint $table) {
            // Primary key (UIN convention)
            $table->unsignedInteger('Pin_code_UIN')->primary();

            // The actual pin code number, e.g. 744301
            $table->unsignedInteger('pin_code_No');

            // Foreign UIN references (country / state / district masters)
            $table->unsignedInteger('Cutr_UIN')->index();
            $table->unsignedInteger('Stat_UIN')->index();
            $table->unsignedInteger('Dist_UIN')->index();

            // Rural / Urban / Metro
            $table->string('Loca_Type', 15);

            // Status master UIN
            $table->unsignedInteger('Stau_UIN')->index();

            // Audit columns (project convention: no MoOn on this table, CrOn only)
            $table->unsignedInteger('CrBy');
            $table->unsignedInteger('CrOn');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_mast_pin_code');
    }
};