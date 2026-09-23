<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'no_telepon')) {
                $table->string('no_telepon')->nullable()->after('email');
                $table->string('fakultas')->nullable()->after('no_telepon');
                $table->string('program_studi')->nullable()->after('fakultas');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['no_telepon', 'fakultas', 'program_studi']);
        });
    }
};