<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('facilities')->onDelete('restrict');
            $table->string('kategori_laporan');
            $table->text('deskripsi');
            $table->json('foto_paths')->nullable();
            $table->enum('status_laporan', ['baru', 'diproses', 'selesai', 'ditolak'])->default('baru');
            $table->text('catatan_resolusi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
