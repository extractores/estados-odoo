<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->integer('idoo');
            $table->string('emp_id')->nullable();
            $table->string('cli_idclie')->nullable();
            $table->string('cli_nrodoc')->nullable();
            $table->string('cli_ruc')->nullable();
            $table->string('cli_apepa')->nullable();
            $table->string('cli_apema')->nullable();
            $table->string('cli_nombre')->nullable();
            $table->string('cli_razsoc')->nullable();
            $table->string('cli_domifis')->nullable();
            $table->string('date_birthday')->nullable();
            $table->string('cli_tlf')->nullable();
            $table->string('cli_fax')->nullable();
            $table->string('cli_email')->nullable();
            $table->string('idcategoabc')->nullable();
            $table->string('pais')->nullable();
            $table->string('departamento')->nullable();
            $table->string('provincia')->nullable();
            $table->string('distrito')->nullable();
            $table->string('pricelist_id')->nullable();
            $table->string('pricelist')->nullable();
            $table->string('estado_cliente')->nullable();
            $table->string('es_fabricante')->nullable();
            $table->string('es_conducto')->nullable();
            $table->string('validado_sunat')->nullable();
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
        Schema::dropIfExists('cliente');
    }
}
