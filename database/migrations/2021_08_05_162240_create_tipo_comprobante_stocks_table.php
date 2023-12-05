<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoComprobanteStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_comprobante_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_comprob', 10);
            $table->string('descripcion', 50);
            $table->string('ingreso_egreso', 10);
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
        Schema::dropIfExists('tipo_comprobante_stocks');
    }
}
