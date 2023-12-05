<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprobanteStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobantes_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sucursal_origen_id');
            $table->unsignedBigInteger('sucursal_destino_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('tipo_comprobante_id');
            $table->date('fecha_comprobante');
            $table->date('fecha_carga');
            $table->string('letra', 5)->nullable();
            $table->string('nro_sucursal', 10)->nullable();
            $table->string('nro_comprobante', 20)->nullable();
            $table->unsignedBigInteger('firma_id');
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
        Schema::dropIfExists('comprobantes_stock');
    }
}
