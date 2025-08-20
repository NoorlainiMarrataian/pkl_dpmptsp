<?php
// 2025_08_19_000000_create_data_investasi_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_investasi', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->string('periode', 20);
            $table->string('status_penanaman_modal', 50)->nullable();
            $table->string('regional', 50)->nullable();
            $table->string('negara', 100)->nullable();
            $table->string('sektor_utama', 100)->nullable();
            $table->string('nama_sektor', 150)->nullable();
            $table->text('deskripsi_kbli_2digit')->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kabupaten_kota', 100)->nullable();
            $table->string('wilayah_jawa', 50)->nullable();
            $table->string('pulau', 50)->nullable();

            $table->decimal('investasi_rp_juta', 15, 2)->nullable();
            $table->decimal('investasi_us_ribu', 15, 2)->nullable();
            $table->integer('jumlah_tki')->nullable();

            $table->timestamps();

            $table->foreign('negara')->references('negara')->on('negara_investor')->onDelete('cascade');
            $table->foreign('nama_sektor')->references('nama_sektor')->on('sektor')->onDelete('cascade');
            $table->foreign('kabupaten_kota')->references('kabupaten_kota')->on('lokasi')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_investasi');
    }
};
