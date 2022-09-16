<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_det', function (Blueprint $table) {
            $table->id();
            $table->integer('id_ordder');
            $table->integer('product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('tipo_producto')->nullable();
            $table->string('unidad_medida')->nullable();
            $table->double('precio_compra',20,3)->nullable();
            $table->string('codigo_fabricante')->nullable();
            $table->double('discount_product',20,3)->nullable();
            $table->double('unidades_complementarias_faltantes_master',20,3)->nullable();
            $table->double('price_unit',20,3)->nullable();
            $table->double('product_uom_qty',20,3)->nullable();
            $table->integer('tax_id')->nullable();
            $table->double('impuesto',3,2)->nullable();
            $table->string('tipo_impuesto')->nullable();
            $table->double('monto_impuesto')->nullable();
            $table->string('is_reward_line')->nullable();
            $table->string('cod_promocion')->nullable();
            $table->string('nombre_promo')->nullable();
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
        Schema::dropIfExists('pedido_det');
    }
}
