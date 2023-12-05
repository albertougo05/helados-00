<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->after('password', function ($table) {
                $table->unsignedBigInteger('empresa_id')->default(0);
                $table->unsignedBigInteger('sucursal_id')->default(0);
                $table->unsignedBigInteger('empleado_id')->default(0);
                $table->unsignedBigInteger('perfil_id')->default(0);
                $table->unsignedBigInteger('estado')->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('empresa_id');
            $table->dropColumn('sucursal_id');
            $table->dropColumn('empleado_id')->nullable();
            $table->dropColumn('perfil_id');
            $table->dropColumn('estado');
        });
    }
}
