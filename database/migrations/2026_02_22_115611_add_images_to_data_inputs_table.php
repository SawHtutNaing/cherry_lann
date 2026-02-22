<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('data_inputs', function (Blueprint $table) {
            $table->string('client_side_image')->nullable()->after('remark');
            $table->string('service_side_image')->nullable()->after('client_side_image');
        });
    }

    public function down(): void
    {
        Schema::table('data_inputs', function (Blueprint $table) {
            $table->dropColumn(['client_side_image', 'service_side_image']);
        });
    }
};
