<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promo_detalle extends Model
{
    //
    protected $table='promo_det';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['promc_idpromo','promd_artprov'];
}
