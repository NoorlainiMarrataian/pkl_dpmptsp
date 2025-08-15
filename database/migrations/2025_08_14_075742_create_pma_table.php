<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pma', function (Blueprint $table) {
            $table->id(); // PK
            $table->string('kabupaten_kota'); // atau bisa FK kalau ada tabel kabupaten_kota
            $table->year('tahun');
            $table->string('periode', 20);
            $table->integer('proyek_pma');
            $table->bigInteger('tambahan_investasi_dalam_ribu_usd');
            $table->bigInteger('tambahan_investasi_dalam_juta');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pma');
    }
};
