<?php

use App\Enums\BoostStatus;
use App\Models\BoostType;
use App\Models\User;
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
        Schema::create('data_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable();
            $table->string('page_name')->nullable();
            $table->foreignIdFor(BoostType::class)->nullable();
            $table->date('start_date')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->unsignedTinyInteger('status'); // Store status as int
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_inputs');
    }
};
