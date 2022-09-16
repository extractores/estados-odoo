<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class giro extends Model
{
    //
    protected $table='giros';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['id_odoo','name'];

}
