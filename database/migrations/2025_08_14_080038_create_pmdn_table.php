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
            $table->unsignedBigInteger('data_investasi_id'); // Foreign key ke data_investasi.id_data
            $table->year('tahun');
            $table->string('periode', 20);
            $table->integer('proyek')->nullable();
            $table->decimal('tambahan_investasi_dalam_juta', 15, 2)->nullable();
            $table->integer('tki')->nullable();
            $table->timestamps();

            // Relasi ke data_investasi
            $table->foreign('data_investasi_id')
                  ->references('id_data')
                  ->on('data_investasi')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
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
