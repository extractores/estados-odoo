<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jefeventas extends Model
{
    //


    protected $table='jefeventas';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['cod_jefeventas','nombre','equipo'];

}
