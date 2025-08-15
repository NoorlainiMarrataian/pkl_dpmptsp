<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLokasiTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // lokasi migration
Schema::create('lokasi', function (Blueprint $table) {            
    $table->string('kabupaten_kota', 100)->collation('utf8mb4_unicode_ci')->primary();
    $table->year('tahun');
    $table->string('periode', 20);
    $table->integer('proyek_pmdn')->nullable();
    $table->decimal('tambahan_investasi_dalam_juta_pmdn', 15, 2)->nullable();
    $table->integer('proyek_pma')->nullable();
    $table->decimal('tambahan_investasi_dalam_ribu_usd', 15, 2)->nullable();
    $table->decimal('tambahan_investasi_dalam_juta_pma', 15, 2)->nullable();
    $table->integer('proyek')->nullable();
    $table->decimal('tambahan_investasi_dalam_juta', 15, 2)->nullable();
    
    $table->index(['tahun','periode']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
}
