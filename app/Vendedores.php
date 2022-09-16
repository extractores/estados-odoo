<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendedores extends Model
{
    //

    protected $table='vendedores';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['cod_vendedor','cod_supervisor','vendedor','id_equipo','equipo',
      'id_canal','canal','documento','supervisor'
    ];

}
