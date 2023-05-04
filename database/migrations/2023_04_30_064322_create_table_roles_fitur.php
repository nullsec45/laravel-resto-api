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
        Schema::create('roles_fitur', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("role_id")->unsigned();
            $table->bigInteger("fitur_id")->unsigned();

            $table->foreign("role_id")->references("id")->on("roles");
            $table->foreign("fitur_id")->references("id")->on("fiturs");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_roles_fitur');
    }
};
