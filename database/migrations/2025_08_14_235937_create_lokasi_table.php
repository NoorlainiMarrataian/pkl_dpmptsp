<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lokasi', function (Blueprint $table) {
            $table->id(); // PK untuk relasi
            $table->string('kabupaten_kota', 100);
            $table->year('tahun');
            $table->string('periode', 20);

            $table->integer('proyek_pmdn')->nullable();
            $table->decimal('tambahan_investasi_dalam_juta_pmdn', 15, 2)->nullable();

            $table->integer('proyek_pma')->nullable();
            $table->decimal('tambahan_investasi_dalam_ribu_usd', 15, 2)->nullable();
            $table->decimal('tambahan_investasi_dalam_juta_pma', 15, 2)->nullable();

            $table->integer('proyek')->nullable();
            $table->decimal('tambahan_investasi_dalam_juta', 15, 2)->nullable();

            // Unik supaya tidak ada data dobel di periode sama
            $table->unique(['kabupaten_kota', 'tahun', 'periode'], 'unik_lokasi');
            $table->index(['tahun', 'periode'], 'lokasi_tahun_periode_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
};
