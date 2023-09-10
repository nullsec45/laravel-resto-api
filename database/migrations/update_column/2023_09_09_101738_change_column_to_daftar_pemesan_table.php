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
            $table->string("kode_meja",10)->change();
            $table->unique("kode_meja")->change();

            $table->foreign("kode_meja")->references("kode")->on("daftar_meja")->onDelete("cascade")->onUpdate("cascade");
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
            //
        });
    }
};
