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
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->string('name',30)->unique();
            $table->string('image');
            $table->text('description')->nullable();
            $table->string('type');
            $table->string('light')->nullable();
            $table->string('ideal_temperature')->nullable();
            $table->string('resistance_zone')->nullable();
            $table->string('suitable_location')->nullable();
            $table->string('careful')->nullable();
            $table->string('liquid_fertilizer')->nullable();
            $table->string('clean')->nullable();
            $table->string('toxicity')->nullable();
            $table->string('names')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
