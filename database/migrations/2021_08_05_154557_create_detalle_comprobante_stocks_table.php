<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleComprobanteStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_comprobante_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_comprobante_stock_id');
            $table->unsignedBigInteger('producto_id');
            $table->float('cantidad',8,3);
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
        Schema::dropIfExists('detalle_comprante_stocks');
    }
}
