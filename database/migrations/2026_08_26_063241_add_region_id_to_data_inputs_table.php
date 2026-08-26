<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_inputs', function (Blueprint $table) {
            $table->foreignId('region_id')->nullable()->after('phone')
                ->constrained('regions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('data_inputs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('region_id');
        });
    }
};
