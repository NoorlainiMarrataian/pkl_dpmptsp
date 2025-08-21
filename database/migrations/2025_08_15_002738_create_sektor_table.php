<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sektor', function (Blueprint $table) {
            $table->id(); // PK auto increment

            $table->string('nama_sektor', 150)->collation('utf8mb4_unicode_ci');
            $table->year('tahun');
            $table->string('periode', 20);

            $table->integer('proyek_pmdn')->nullable();
            $table->decimal('tambahan_investasi_dalam_juta_pmdn', 15, 2)->nullable();

            $table->integer('proyek_pma')->nullable();
            $table->decimal('tambahan_investasi_dalam_ribu_usd', 15, 2)->nullable();
            $table->decimal('tambahan_investasi_dalam_juta_pma', 15, 2)->nullable();

            $table->integer('proyek')->nullable();
            $table->decimal('tambahan_investasi_dalam_juta', 15, 2)->nullable();

            // Unik sektor-tahun-periode
            $table->unique(['nama_sektor', 'tahun', 'periode'], 'unik_sektor');
            $table->index(['tahun','periode'], 'sektor_tahun_periode_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sektor');
    }
};
