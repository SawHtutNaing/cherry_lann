<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The `role` column was created as an ENUM('admin', 'user') (see
     * create_users_table), but the app has referenced a 'super_admin' role
     * throughout (navigation config, UserManagement, HideSuperAdminScope,
     * UserProfitLogManagement) without a migration ever widening the column
     * to allow it. Saving role = 'super_admin' fails against that
     * constraint wherever it hasn't been patched out-of-band already.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'super_admin') NOT NULL DEFAULT 'user'");
            return;
        }

        if ($driver === 'pgsql') {
            // enum() on Postgres is a CHECK constraint, not a native enum type.
            $constraint = DB::selectOne(
                "SELECT conname FROM pg_constraint WHERE conrelid = 'users'::regclass AND contype = 'c' AND pg_get_constraintdef(oid) LIKE '%role%'"
            );

            if ($constraint) {
                DB::statement('ALTER TABLE users DROP CONSTRAINT ' . $constraint->conname);
            }

            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'user', 'super_admin'))");
            return;
        }

        // SQLite bakes enum() into a CHECK constraint on the column itself, which can
        // only be changed by rebuilding the column (no in-place ALTER for constraints).
        if ($driver === 'sqlite') {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role_tmp')->nullable()->after('role');
            });

            DB::statement('UPDATE users SET role_tmp = role');

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['admin', 'user', 'super_admin'])->default('user')->after('password');
            });

            DB::statement('UPDATE users SET role = role_tmp');

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role_tmp');
            });
        }
    }

    public function down(): void
    {
        // Intentionally a no-op: narrowing the enum back to ('admin','user') would
        // truncate/break any row already saved with role = 'super_admin'.
    }
};
