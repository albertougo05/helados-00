<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social', 255);
            $table->string('nombre_fantasia', 255);
            $table->string('direccion', 120);
            $table->string('localidad', 50);
            $table->string('codigo_postal', 20)->nullable();
            $table->string('provincia', 50)->nullable();
            $table->string('nro_ing_brutos', 50)->nullable();
            $table->string('celular', 50)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('email', 120)->nullable();
            $table->tinyInteger('estado')->comment('1 activo - 0 no');
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
        Schema::dropIfExists('empresas');
    }
}
