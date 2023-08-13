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
        Schema::table('roles_fitur', function (Blueprint $table) {
            $table->foreign("role_id")->references("id")->on("roles")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("fitur_id")->references("id")->on("fiturs")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
