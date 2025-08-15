<?php
// 2025_08_13_000000_create_negara_investor_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('negara_investor', function (Blueprint $table) {
            $table->string('negara', 100)
                  ->collation('utf8mb4_unicode_ci')
                  ->primary();
            $table->year('tahun');
            $table->string('periode', 20);
            $table->integer('proyek');
            $table->decimal('tambahan_investasi_dalam_ribu_usd', 15, 2)->nullable();
            $table->decimal('tambahan_investasi_dalam_juta', 15, 2)->nullable();

            $table->index(['tahun', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negara_investor');
    }
};
