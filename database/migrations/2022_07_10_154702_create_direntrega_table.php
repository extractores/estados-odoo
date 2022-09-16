<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirentregaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direntrega', function (Blueprint $table) {
            $table->id();
            $table->string('cli_idclie');
            $table->string('coddir')->nullable();
            $table->string('full_name')->nullable();
            $table->string('direntrega')->nullable();
            $table->string('pais')->nullable();
            $table->string('departamento')->nullable();
            $table->string('provincia')->nullable();
            $table->string('distrito')->nullable();
            $table->string('ubigeo')->nullable();
            $table->string('dir_corx')->nullable();
            $table->string('dir_cory')->nullable();
            $table->string('zona_geografica')->nullable();
            $table->string('idrutamaster')->nullable();
            $table->string('idruta')->nullable();
            $table->string('nombre_ruta')->nullable();
            $table->string('diavisita')->nullable();
            $table->string('modulo_id')->nullable();
            $table->string('modulo')->nullable();
            $table->string('codvend')->nullable();
            $table->string('vendedor')->nullable();
            $table->string('idgiro')->nullable();
            $table->string('gironegocio')->nullable();
            $table->string('categoriaabc')->nullable();
            $table->string('ventana_horaria_inicial_1')->nullable();
            $table->string('ventana_horaria_final_1')->nullable();
            $table->string('ventana_horaria_inicial_2')->nullable();
            $table->string('ventana_horaria_final_3')->nullable();
            $table->string('creditLimit')->nullable();
            $table->string('officeId')->nullable();
            $table->string('saleCondition')->nullable();
            $table->string('pricelist_id')->nullable();
            $table->string('pricelist_name')->nullable();
            $table->string('warehouse_id')->nullable();
            $table->string('warehouse_name')->nullable();
            $table->string('canal_id')->nullable();
            $table->string('canal_name')->nullable();
            $table->string('estado_dir')->nullable();
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
        Schema::dropIfExists('direntrega');
    }
}
