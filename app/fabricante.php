<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class fabricante extends Model
{
    //

    protected $table='fabricante';
    protected $primaryKey = 'id';
    public $timestamps=false;

    protected $fillable=['cod_odoo','cod_eco','tipo_documento','documento','proveedor','cod_compania','compania'];

}
