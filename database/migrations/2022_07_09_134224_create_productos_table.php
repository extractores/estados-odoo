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
            $table->string('tipoproducto')->nullable();
            $table->string('emp_id')->nullable();
            $table->string('prov_id')->nullable();
            $table->string('proveedor')->nullable();
            $table->string('codart')->nullable();
            $table->string('producto')->nullable();
            $table->string('nombrecorto')->nullable();
            $table->string('idcategoria')->nullable();
            $table->string('categoria')->nullable();
            $table->string('idlinea')->nullable();
            $table->string('linea')->nullable();
            $table->string('idsublinea')->nullable();
            $table->string('sublinea')->nullable();
            $table->string('idmarca')->nullable();
            $table->string('marca')->nullable();
            $table->string('idsubmarca')->nullable();
            $table->string('submarca')->nullable();
            $table->string('codori')->nullable();
            $table->string('empaquecompra')->nullable();
            $table->string('upreoriginal')->nullable();
            $table->string('empaquevta')->nullable();
            $table->string('undpresenta')->nullable();
            $table->string('estado_articulo')->nullable();
            $table->string('estado_compra')->nullable();
            $table->string('estado_venta')->nullable();
            $table->double('peso')->nullable();
            $table->string('art_unimed')->nullable();
            $table->double('volumen')->nullable();
            $table->double('preciovta')->nullable();
            $table->double('preciocompra')->nullable();
            $table->string('tipoigv')->nullable();
            $table->double('igv')->nullable();
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
