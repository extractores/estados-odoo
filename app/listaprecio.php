<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class listaprecio extends Model
{
    //
    protected $table='listaprecios';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['id_odoo','name'];


}
