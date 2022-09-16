<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_cab', function (Blueprint $table) {
            $table->id();
            $table->integer('id_odoo')->nullable();
            $table->string('nro_order')->nullable();
            $table->string('date_order')->nullable();
            $table->string('date_emision')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('ruta_master')->nullable();
            $table->string('ruta_dia')->nullable();
            $table->string('date_delivery')->nullable();
            $table->string('state')->nullable();
            $table->string('tipo_documento')->nullable();
            $table->string('numero_comprobante')->nullable();
            $table->string('fecha_liquidacion')->nullable();
            $table->double('total_amt',20,3)->nullable();
            $table->string('fecha_cobranza')->nullable();
            $table->double('total_amt_inv',20,3)->nullable();
            $table->double('total_discount',20,3)->nullable();
            $table->string('id_almacen')->nullable();
            $table->string('reason_reject')->nullable();
            $table->string('forma_pago')->nullable();
            $table->string('codigo_dir')->nullable();
            $table->string('dir_entrega')->nullable();
            $table->string('price_list')->nullable();
            $table->string('modulo')->nullable();
            $table->string('idcliente_odoo')->nullable();
            $table->string('idclie')->nullable();
            $table->string('razon_social')->nullable();
            $table->string('cod_distrito')->nullable();
            $table->string('distrito')->nullable();
            $table->string('ubigeo')->nullable();
            $table->string('seller_id')->nullable();
            $table->string('seller_name')->nullable();
            $table->string('driver_id')->nullable();
            $table->string('driver')->nullable();
            $table->string('vehicle_id')->nullable();
            $table->string('vehicle')->nullable();
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
        Schema::dropIfExists('pedido_cab');
    }
}
