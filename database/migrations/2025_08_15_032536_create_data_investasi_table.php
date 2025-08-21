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

            // 🔑 foreign key tetap dipertahankan
            $table->foreignId('negara_id')
                  ->nullable()
                  ->constrained('negara_investor')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->foreignId('sektor_id')
                  ->nullable()
                  ->constrained('sektor')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->foreignId('lokasi_id')
                  ->nullable()
                  ->constrained('lokasi')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            // 🔥 tambahan kolom string langsung
            $table->string('negara', 100)->nullable();
            $table->string('nama_sektor', 150)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kabupaten_kota', 100)->nullable();

            // data tambahan
            $table->string('sektor_utama', 100)->nullable();
            $table->text('deskripsi_kbli_2digit')->nullable();
            $table->string('wilayah_jawa', 50)->nullable();
            $table->string('pulau', 50)->nullable();

            // nilai investasi
            $table->decimal('investasi_rp_juta', 15, 2)->nullable();
            $table->decimal('investasi_us_ribu', 15, 2)->nullable();
            $table->integer('jumlah_tki')->nullable();

            $table->timestamps();

            // 📊 Index untuk optimasi query
            $table->index(['tahun', 'periode']);
            $table->index('status_penanaman_modal');
            $table->index('regional');

            // 🚫 Pastikan kombinasi unik (opsional)
            $table->unique(['tahun', 'periode', 'sektor_id', 'lokasi_id'], 'unik_data_investasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_investasi');
    }
};
