<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class subcategoria extends Model
{
    //
    protected $table='subcategorias';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['id_odoo_sub_cat','name'];

}
