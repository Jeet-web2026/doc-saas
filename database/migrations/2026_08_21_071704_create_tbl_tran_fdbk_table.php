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
        Schema::create('tbl_tran_fdbk', function (Blueprint $table) {
            $table->id('Fdbk_UIN');
            $table->unsignedBigInteger('Orga_UIN');
            $table->unsignedBigInteger('Page_UIN');
            $table->string('Page_Name', 300)->default('');
            $table->string('Page_load', 20);
            $table->string('Page_Dsin', 20);
            $table->string('Page_Oall', 20);
            $table->string('Fdbk_Type', 100);
            $table->text('Mesg_Fdbk');
            $table->integer('PinCod');
            $table->unsignedBigInteger('CrBy');
            $table->integer('CrOn');

            $table->index('Orga_UIN');
            $table->index('Page_UIN');
            $table->index('CrBy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_tran_fdbk');
    }
};