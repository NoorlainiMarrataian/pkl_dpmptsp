<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('negara_investor', function (Blueprint $table) {
            $table->id(); // PK untuk relasi
            $table->string('negara', 100)->collation('utf8mb4_unicode_ci');
            $table->year('tahun');
            $table->string('periode', 20);
            $table->integer('proyek');

            $table->decimal('tambahan_investasi_dalam_ribu_usd', 15, 2)->nullable();
            $table->decimal('tambahan_investasi_dalam_juta', 15, 2)->nullable();

            // Unik supaya 1 negara-tahun-periode tidak dobel
            $table->unique(['negara', 'tahun', 'periode'], 'unik_negara_investor');
            $table->index(['tahun', 'periode'], 'negara_tahun_periode_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negara_investor');
    }
};
