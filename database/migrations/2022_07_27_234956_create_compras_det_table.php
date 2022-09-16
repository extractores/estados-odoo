<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras_det', function (Blueprint $table) {
            $table->id();
            $table->integer('id_compra')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('producto')->nullable();
            $table->string('unidad_medida_master')->nullable();
            $table->string('unidad_venta')->nullable();
            $table->decimal('precio_compra',20,3)->nullable();
            $table->integer('cod_proveedor')->nullable();
            $table->string('proveedor')->nullable();
            $table->decimal('cantidad_rechazada',20,3)->nullable();
            $table->string('unidad_medida_rechazada')->nullable();
            $table->decimal('conver_master_unidad',20,3)->nullable();
            $table->decimal('cantidad_master',20,3)->nullable();
            $table->decimal('cantidad_unidades',20,3)->nullable();
            $table->decimal('costo_master',20,3)->nullable();
            $table->decimal('price_unit',20,3)->nullable();
            $table->decimal('precio_sin_igv',20,3)->nullable();
            $table->decimal('product_qty',20,3)->nullable();
            $table->integer('tax_id')->nullable();
            $table->string('impuesto')->nullable();
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
        Schema::dropIfExists('compras_det');
    }
}
