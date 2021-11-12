<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing', function (Blueprint $table) {
            $table->string("kode_billing")->primary();
            $table->string("user_id");
            $table->bigInteger("total_biaya");
            $table->string("status", 15);
            $table->date("tanggal_bayar")->nullable();
            $table->string("metode_pembayaran")->nullable();
            $table->bigInteger("nominal_pembayaran")->nullable();
            $table->bigInteger("nominal_kembalian")->nullable();
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
        Schema::dropIfExists('billing');
    }
}
