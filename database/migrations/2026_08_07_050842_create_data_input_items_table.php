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
     // database/migrations/xxxx_xx_xx_create_data_input_items_table.php
Schema::create('data_input_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('data_input_id')->constrained()->cascadeOnDelete();
    $table->foreignId('boost_type_id')->constrained();
    $table->date('start_date');
    $table->decimal('amount', 12, 2)->default(0);
    $table->decimal('mm_kyat', 12, 2)->default(0);
    $table->decimal('discount', 12, 2)->default(0);
    $table->decimal('line_total', 12, 2)->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_input_items');
    }
};
