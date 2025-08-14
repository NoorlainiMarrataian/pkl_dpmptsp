<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('pmdn', function (Blueprint $table) {
            // Primary Key
            $table->unsignedBigInteger('kabupaten_kota');
            $table->year('tahun');
            $table->string('periode', 10);
            
            // Data kolom
            $table->integer('proyek_pmdn')->nullable();
            $table->decimal('tambahan_investasi_dalam_juta', 15, 2)->nullable();

            // Primary Key gabungan
            $table->primary(['kabupaten_kota', 'tahun', 'periode']);

            // Foreign Key
            $table->foreign('kabupaten_kota')
                  ->references('kabupaten_kota')
                  ->on('lokasi')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmdn');
    }
};
