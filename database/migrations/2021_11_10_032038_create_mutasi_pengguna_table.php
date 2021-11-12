<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutasiPenggunaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutasi_pengguna', function (Blueprint $table) {
            $table->string("kode_mutasi");
            $table->string("user_sender");
            $table->string("user_receiver");
            $table->bigInteger("nominal");
            $table->string("type", 20);
            $table->text("keterangan");
            $table->string("status", 10);
            $table->string("in_out", 10);
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
        Schema::dropIfExists('mutasi_pengguna');
    }
}
