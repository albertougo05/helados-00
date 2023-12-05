<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientosCajaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos_caja', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sucursal_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('tipo_comprobante_id');
            $table->string('fecha_hora', 30)->nullable();
            $table->string('fecha_registro', 15)->nullable();
            $table->string('sucursal', 20);
            $table->integer('nro_comprobante')->nullable();
            $table->string('concepto', 50);
            $table->string('cuenta_contable', 20)->nullable();
            $table->float('importe',8,2);
            $table->float('total_efectivo',8,2)->nullable();
            $table->float('total_debito',8,2)->nullable();
            $table->float('total_tarjeta',8,2)->nullable();
            $table->float('total_valores',8,2)->nullable();
            $table->float('total_transfer',8,2)->nullable();
            $table->float('total_bonos',8,2)->nullable();
            $table->float('total_retenciones',8,2)->nullable();
            $table->float('total_otros',8,2)->nullable();
            $table->string('cuenta_corriente')->nullable();
            $table->unsignedBigInteger('firma_id')->nullable();
            $table->tinyInteger('estado')->comment('1 activo - 0 no');
            $table->string('observaciones', 100)->nullable();
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
        Schema::dropIfExists('movimientos_caja');
    }
}
