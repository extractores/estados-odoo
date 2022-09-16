<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class motivosrechazo extends Model
{
    //
    protected $table='motivosrechazos';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['cod_oddo','motivo'];
}
