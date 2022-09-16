<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class lineas extends Model
{
    //

    protected $table='lineas';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['id_odoo_lineas','name'];

}
