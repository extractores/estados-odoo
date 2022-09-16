<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Almacenes extends Model
{
    //

    protected $table='almacen';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['almacen'];

}
