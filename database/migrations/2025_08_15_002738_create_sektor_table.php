<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSektorTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sektor', function (Blueprint $table) {
            $table->string('nama_sektor', 150)->primary();//pk
            $table->year('tahun');
            $table->integer('periode', 20);
            $table->integer('proyek_pmdn')->nullable();
            $table->decimal('tambahan_investasi_dalam_juta_pmdn', 15, 2)->nullable();
            $table->integer('proyek_pma')->nullable();
            $table->decimal('tambahan_investasi_dalam_ribu_usd', 15, 2)->nullable();
            $table->decimal('tambahan_investasi_dalam_juta_pma', 15, 2)->nullable();
            $table->integer('proyek')->nullable();
            $table->decimal('tambahan_investasi_dalam_juta', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sektor');
    }
}
