<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogPengunduhanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_pengunduhan', function (Blueprint $table) {
            $table->id('id_download'); // Primary key
            $table->string('kategori_pengunduh', 50);
            $table->string('nama_instansi', 100);
            $table->string('email_pengunduh', 100);
            $table->string('telpon', 20)->nullable();
            $table->text('keperluan')->nullable();
            $table->timestamp('waktu_download')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_pengunduhan');
    }
}
