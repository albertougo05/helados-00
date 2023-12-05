<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 12)->nullable();
            $table->unsignedBigInteger('proveedor_id')->comment('Tabla firmas');
            $table->unsignedBigInteger('tipo_producto_id');
            $table->unsignedBigInteger('grupo_id')->nullable();
            $table->string('descripcion');
            $table->string('descripcion_ticket', 100)->nullable();
            $table->tinyInteger('venta_publico')->comment('1 si - 0 no');
            $table->tinyInteger('insumo')->comment('1 si - 0 no');
            $table->tinyInteger('descartable')->comment('1 si - 0 no');
            $table->unsignedInteger('tasa_iva');
            $table->float('precio_lista_1');
            $table->float('precio_lista_2')->nullable();
            $table->float('precio_lista_3')->nullable();
            $table->float('costo');
            $table->float('peso_materia_prima', 8, 3)->nullable()->comment('3 decimales');  // OJO ! 3 decimales
            $table->date('ultima_actualizacion')->useCurrent();
            $table->integer('unidades_x_bulto')->nullable();
            $table->integer('articulo_indiv_id')->nullable();
            $table->integer('cantidad_individual')->nullable();
            $table->binary('foto')->nullable();
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
        Schema::dropIfExists('productos');
    }
}
