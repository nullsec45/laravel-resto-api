<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daftar_pemesan', function (Blueprint $table) {
            $table->uuid("kode_pesanan")->primary();
            $table->primary('kode_pesanan');
            $table->string("nama_pemesan", 255);
            $table->text("catatan");
            $table->integer("total_harga")->nullable();
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
        Schema::dropIfExists('table_daftar_pemesan');
    }
};
