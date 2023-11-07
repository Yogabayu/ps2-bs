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
        Schema::create('datas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_uuid');
            $table->unsignedBigInteger('transc_id');
            $table->unsignedBigInteger('place_transc_id');
            $table->date('date');
            $table->time('start');
            $table->time('end');
            $table->integer('nominal');
            $table->string('customer_name');
            $table->integer('result')->comment("1=sesuai,2=tdk sesuai");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datas');
    }
};
