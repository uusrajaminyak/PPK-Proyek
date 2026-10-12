<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The status is only a real CHECK constraint on PostgreSQL.
        // SQLite (used by the test suite) has no ALTER ... DROP CONSTRAINT,
        // and its inline enum check is never exercised with the new value.
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        // Remove the old constraint first, if it exists.
        DB::statement(
            'ALTER TABLE "reservations"
             DROP CONSTRAINT IF EXISTS "reservations_status_reservasi_check"'
        );

        // Change type/default/nullability separately.
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('status_reservasi')
                ->default('pending')
                ->nullable(false)
                ->change();
        });

        // Add the updated PostgreSQL CHECK constraint separately.
        DB::statement(
            'ALTER TABLE "reservations"
             ADD CONSTRAINT "reservations_status_reservasi_check"
             CHECK ("status_reservasi" IN (
                 \'pending\',
                 \'approved\',
                 \'cancelled_by_user\',
                 \'cancelled_by_officer\',
                 \'cancelled_by_staff\'
             ))'
        );
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement(
            'ALTER TABLE "reservations"
             DROP CONSTRAINT IF EXISTS "reservations_status_reservasi_check"'
        );

        DB::statement(
            'ALTER TABLE "reservations"
             ADD CONSTRAINT "reservations_status_reservasi_check"
             CHECK ("status_reservasi" IN (
                 \'pending\',
                 \'approved\',
                 \'cancelled_by_user\',
                 \'cancelled_by_officer\'
             ))'
        );
    }
};
