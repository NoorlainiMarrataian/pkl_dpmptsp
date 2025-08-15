<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi', function (Blueprint $table) {
            $table->string('kabupaten_kota'); // foreign key
            $table->year('tahun');
            $table->string('periode', 20);
            $table->integer('proyek_pmdn')->nullable();
            $table->decimal('tambahan_investasi_dalam_juta_pmdn', 15, 2)->nullable();
            $table->integer('proyek_pma')->nullable();
            $table->decimal('tambahan_investasi_dalam_ribu_usd', 15, 2)->nullable();
            $table->decimal('tambahan_investasi_dalam_juta_pma', 15, 2)->nullable();
            $table->integer('proyek')->nullable();
            $table->decimal('tambahan_investasi_dalam_juta', 15, 2)->nullable();

            // Primary Key gabungan kalau mau unik per kabupaten & tahun
            $table->primary(['kabupaten_kota', 'tahun']);

            // Foreign key
            $table->foreign('kabupaten_kota')
                  ->references('kabupaten_kota')
                  ->on('data_investasi')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
};
