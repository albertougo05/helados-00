<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firmas', function (Blueprint $table) {
            $table->id();
            $table->string('firma');
            $table->string('nombre_fantasia')->nullable();
            $table->unsignedBigInteger('tipo_doc_id')->comment('Segun tabla afip');
            $table->string('dni_cuit')->nullable();
            $table->string('cond_iva', 2)->comment('RI, MO, CF, EX');
            $table->string('nro_ing_brutos', 50)->nullable();
            $table->string('direccion')->nullable();
            $table->string('localidad')->nullable();
            $table->string('provincia', 50)->nullable();
            $table->string('codigo_postal', 10)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('celular', 50)->nullable();
            $table->string('contacto')->nullable();
            $table->string('email')->nullable();
            $table->tinyInteger('cliente')->comment('1 si - 0 no');
            $table->tinyInteger('proveedor')->comment('1 si - 0 no');
            $table->tinyInteger('otros')->comment('1 si - 0 no');
            $table->unsignedBigInteger('plan_cuenta_id')->comment('tabla plan de cuentas');
            $table->tinyInteger('estado')->comment('1 activo - 0 no');
            $table->timestamps();
        });

        Schema::create('tipo_docs_afip', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento');
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
        Schema::dropIfExists('firmas');
        Schema::dropIfExists('tipo_docs_afip');
    }
}
