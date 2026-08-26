<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('data_inputs')
            ->select('id', 'client_side_image', 'service_side_image')
            ->orderBy('id')
            ->chunkById(100, function ($rows) {
                foreach ($rows as $row) {
                    if (!empty($row->client_side_image)) {
                        DB::table('data_input_images')->insert([
                            'data_input_id' => $row->id,
                            'type'          => 'client',
                            'image_path'    => $row->client_side_image,
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ]);
                    }

                    if (!empty($row->service_side_image)) {
                        DB::table('data_input_images')->insert([
                            'data_input_id' => $row->id,
                            'type'          => 'service',
                            'image_path'    => $row->service_side_image,
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ]);
                    }
                }
            });
    }

    public function down(): void
    {
        // Data-only migration — nothing to structurally reverse.
        // If you need to undo, truncate data_input_images manually.
    }
};
