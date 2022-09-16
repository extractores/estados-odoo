<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_det', function (Blueprint $table) {
            $table->id();
            $table->string('promc_idpromo');
            $table->string('promd_artprov');
            //$table->timestamps();

            $table->foreign('promc_idpromo')->references('promc_idpromo')->on('promo_cab');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo_det');
    }
}
