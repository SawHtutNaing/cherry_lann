<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;
return new class extends Migration
{
public function up()
{
    DB::table('data_inputs')->orderBy('id')->chunkById(200, function ($rows) {
        foreach ($rows as $row) {
            if (!$row->boost_type_id) continue;
            DB::table('data_input_items')->insert([
                'data_input_id' => $row->id,
                'boost_type_id' => $row->boost_type_id,
                'start_date'    => $row->start_date,
                'amount'        => $row->amount,
                'mm_kyat'       => $row->mm_kyat,
                'discount'      => $row->discount,
                'line_total'    => ($row->mm_kyat * $row->amount) - $row->discount,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    });

    Schema::table('data_inputs', function (Blueprint $table) {
        // No FK actually exists on boost_type_id in this database — nothing to drop
        $table->dropColumn(['boost_type_id', 'start_date', 'amount', 'mm_kyat', 'discount']);
    });
}

public function down()
{
    Schema::table('data_inputs', function (Blueprint $table) {
        $table->unsignedBigInteger('boost_type_id')->nullable();
        $table->date('start_date')->nullable();
        $table->decimal('amount', 10, 2)->nullable();
        $table->integer('mm_kyat')->default(0);
        $table->integer('discount')->default(0);
    });
}
};
