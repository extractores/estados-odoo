<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    //
    protected $table='categorias';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['id_odoo_cat','name'];
}
