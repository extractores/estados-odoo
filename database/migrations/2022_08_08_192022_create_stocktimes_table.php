<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocktimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocktimes', function (Blueprint $table) {
            $table->id();
            $table->string('cod_producto');
            $table->string('almacen_id');
            $table->decimal('stock_diponible',20,18);
            $table->decimal('stock_seguridad',20,18);
            $table->decimal('cantidad_mano',20,18);
            $table->decimal('total_soles',20,18);
            $table->date('fecha');
            $table->string('periodo');
            $table->decimal('masterStockAmount');
            $table->integer('cod_proveedor');
            $table->time('hora');
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
        Schema::dropIfExists('stocktimes');
    }
}
