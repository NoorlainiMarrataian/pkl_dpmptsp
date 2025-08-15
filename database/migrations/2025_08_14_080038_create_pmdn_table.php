<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmdnTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pmdn', function (Blueprint $table) {
            $table->id('id_pmdn');
            $table->string('kabupaten_kota', 100);
            $table->year('tahun');
            $table->string('periode', 20);
            $table->integer('proyek_pmdn')->nullable();
            $table->decimal('tambahan_investasi_dalam_juta', 15, 2)->nullable();

            // Relasi ke data_investasi
            
            $table->unique(['kabupaten_kota', 'tahun', 'periode'], 'pmdn_lok_periode_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmdn');
    }
}
