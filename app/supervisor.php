<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class supervisor extends Model
{
    //
    protected $table='supervisor';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['cod_supervisor','supervisor','cod_canal','canal','cod_equipo','equipo'];
}
