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
        Schema::create('subordinates', function (Blueprint $table) {
            $table->id();
            $table->uuid('supervisor_id');
            $table->uuid('subordinate_uuid');
            $table->timestamps();

            $table->foreign('supervisor_id')->references('uuid')->on('users');
            $table->foreign('subordinate_uuid')->references('uuid')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subordinate');
    }
};
