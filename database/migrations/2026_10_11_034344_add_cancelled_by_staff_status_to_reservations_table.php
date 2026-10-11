<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->enum('status_reservasi', ['pending', 'approved', 'cancelled_by_user', 'cancelled_by_officer', 'cancelled_by_staff'])
                ->default('pending')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('reservations')
            ->where('status_reservasi', 'cancelled_by_staff')
            ->update(['status_reservasi' => 'cancelled_by_officer']);

        Schema::table('reservations', function (Blueprint $table) {
            $table->enum('status_reservasi', ['pending', 'approved', 'cancelled_by_user', 'cancelled_by_officer'])
                ->default('pending')
                ->change();
        });
    }
};
