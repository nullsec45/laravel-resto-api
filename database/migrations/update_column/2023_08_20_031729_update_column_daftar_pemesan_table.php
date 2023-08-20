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
        Schema::table('daftar_pemesan', function (Blueprint $table) {
            $table->string("kode_meja",10)->after("kode_pesanan");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daftar_pemesan', function (Blueprint $table) {
            $table->dropColumn("kode_meja");
        });
    }
};
