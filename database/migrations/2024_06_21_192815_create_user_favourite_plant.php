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
        Schema::create('user_favourite_plant', function (Blueprint $table) {
            $table->foreignId('plant_id')->constrained()->OnDelete('cascade');
            $table->foreignId('user_id')->constrained()->OnDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_favourite_plant');
    }
};
