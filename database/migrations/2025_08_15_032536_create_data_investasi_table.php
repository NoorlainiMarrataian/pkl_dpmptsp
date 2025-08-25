<?php
// 2025_08_19_000000_create_data_investasi_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_investasi', function (Blueprint $table) {
            $table->id(); // ✅ gunakan default "id" dari Laravel
            $table->year('tahun');
            $table->string('periode', 20);

            // kolom umum
            $table->string('status_penanaman_modal', 50)->nullable();
            $table->string('regional', 50)->nullable();
            $table->string('negara', 100)->nullable();
            $table->string('nama_sektor', 150)->nullable();
            $table->string('sektor_utama', 100)->nullable();
            $table->text('deskripsi_kbli_2digit')->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kabupaten_kota', 100)->nullable();
            $table->string('wilayah_jawa', 50)->nullable();
            $table->string('pulau', 50)->nullable();

            // data proyek (gabungan pmdn & pma)
            $table->integer('proyek')->nullable();
            $table->integer('proyek_pmdn')->nullable();
            $table->integer('proyek_pma')->nullable();

            // nilai investasi
            $table->decimal('tambahan_investasi_dalam_ribu_usd', 20, 2)->nullable();
            $table->decimal('tambahan_investasi_dalam_juta', 20, 2)->nullable();
            $table->decimal('tambahan_investasi_dalam_juta_pmdn', 20, 2)->nullable();
            $table->decimal('tambahan_investasi_dalam_ribu_usd_pma', 20, 2)->nullable();
            $table->decimal('tambahan_investasi_dalam_juta_pma', 20, 2)->nullable();

            // total investasi
            $table->decimal('investasi_rp_juta', 20, 2)->nullable();
            $table->decimal('investasi_us_ribu', 20, 2)->nullable();

            // tambahan
            $table->integer('jumlah_tki')->nullable();

            $table->timestamps();

            // index untuk optimasi
            $table->index(['tahun', 'periode']);
            $table->index('negara');
            $table->index('nama_sektor');
            $table->index('kabupaten_kota');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_investasi');
    }
};
