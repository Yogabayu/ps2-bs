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
            $table->uuid('user_uuid');
            $table->unsignedBigInteger('transc_id');
            $table->unsignedBigInteger('place_transc_id');
            $table->date('date');
            $table->time('start');
            $table->time('end');
            $table->string('no_rek');
            $table->string('evidence_file');
            $table->integer('result')->comment("1=sesuai,2=tdk sesuai");
            $table->boolean("isActive")->comment("untuk patokan transaksi aktif atau tidak");
            $table->timestamps();

            $table->foreign('user_uuid')->references('uuid')->on('users');
            $table->foreign('transc_id')->references('id')->on('transactions');
            $table->foreign('place_transc_id')->references('id')->on('place_transcs');
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
