<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promo_cabecera extends Model
{
    //
    protected $table='promo_cab';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['promc_idpromo'];

    public function default_code(){

        return $this->hasMany('App\Promo_detalle','promc_idpromo','promc_idpromo');//->orderBy('fven_cuo', 'asc');
    }



}
