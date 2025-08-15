<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataInvestasiTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_investasi', function (Blueprint $table) {
            $table->id('id_data');
            $table->year('tahun');
            $table->string('periode', 50);
            $table->string('status_penanaman_modal', 10);
            $table->string('regional', 100)->nullable();

            $table->string('negara', 100);
            $table->string('nama_sektor', 150);
            $table->string('kabupaten_kota', 100);

            $table->string('sektor_utama', 100)->nullable();         
            $table->string('deskripsi_kbli_2digit', 255);
            $table->string('provinsi', 100);
            $table->string('wilayah_jawa', 40);
            $table->string('pulau', 50);
            $table->decimal('investasi_rp_juta', 15, 2)->nullable();
            $table->decimal('investasi_us_ribu', 15, 2)->nullable();
            $table->integer('jumlah_tki')->nullable();
            $table->timestamps();

            // Contoh jika nanti ingin relasi foreign key
            // $table->foreign('negara')->references('id')->on('negara');
            // $table->foreign('sektor_utama')->references('id')->on('sektor_utama');
            // $table->foreign('kabupaten_kota')->references('id')->on('kabupaten_kota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_investasi');
    }
}
