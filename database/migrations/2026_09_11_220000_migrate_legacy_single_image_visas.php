<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Safety-net migration.
     *
     * The Visa feature has always stored photos in the `visa_images` table
     * (see create_visas_table / create_visa_images_table) — there is no
     * single-image column in this codebase's own migration history. But if
     * an older deployment ever stored a photo directly on the `visas` row
     * (under any of the column names below) before multi-image support
     * existed, this migration copies that value into `visa_images` and
     * drops the old column, so old records keep their photo and show up
     * correctly in the new multi-image gallery.
     *
     * On a database that never had one of these columns (which is the
     * normal case here), this migration does nothing.
     */
    private array $legacyColumns = ['image_path', 'image', 'photo', 'photo_path'];

    // public function up(): void
    // {
    //     $existing = array_values(array_filter(
    //         $this->legacyColumns,
    //         fn ($column) => Schema::hasColumn('visas', $column)
    //     ));

    //     if (empty($existing)) {
    //         return;
    //     }

    //     DB::table('visas')
    //         ->select(array_merge(['id'], $existing))
    //         ->orderBy('id')
    //         ->chunkById(100, function ($rows) use ($existing) {
    //             foreach ($rows as $row) {
    //                 foreach ($existing as $column) {
    //                     $path = $row->{$column} ?? null;

    //                     if (!empty($path)) {
    //                         DB::table('visa_images')->insert([
    //                             'visa_id'    => $row->id,
    //                             'image_path' => $path,
    //                             'created_at' => now(),
    //                             'updated_at' => now(),
    //                         ]);
    //                     }
    //                 }
    //             }
    //         });

    //     Schema::table('visas', function (Blueprint $table) use ($existing) {
    //         $table->dropColumn($existing);
    //     });
    // }

    // public function down(): void
    // {
    //     // Data-only / conditional structural migration — nothing meaningful to reverse.
    //     // If you need the columns back, re-add them manually; the copied
    //     // visa_images rows are left in place.
    // }
};
