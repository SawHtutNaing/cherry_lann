<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_input_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_input_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['client', 'service']);
            $table->string('image_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_input_images');
    }
};
