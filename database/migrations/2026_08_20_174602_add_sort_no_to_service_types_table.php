<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('service_types', function (Blueprint $table) {
            $table->unsignedInteger('sort_no')->default(0)->after('type');
        });

        // backfill existing rows so order is stable
        \App\Models\ServiceType::orderBy('id')->get()->each(function ($serviceType, $index) {
            $serviceType->update(['sort_no' => $index + 1]);
        });
    }

    public function down()
    {
        Schema::table('service_types', function (Blueprint $table) {
            $table->dropColumn('sort_no');
        });
    }
};
