<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class supervisores extends Model
{
    //
    protected $table='supervisores';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['id_odoo_sup','name','correo'];
}
