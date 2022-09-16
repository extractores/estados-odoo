<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->string('company_id')->nullable();;
            $table->string('nro_order')->nullable();;
            $table->string("estado_orden")->nullable();;
            $table->date('date_approve')->nullable();;
            $table->date('date_planned')->nullable();;
            $table->string('numero_documento_proveedor')->nullable();;
            $table->string('amount_tax')->nullable();;
            $table->string('amount_total')->nullable();;
            $table->string('warehouse_id')->nullable();;
            $table->string('warehouse_name')->nullable();;
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
        Schema::dropIfExists('compras');
    }
}
