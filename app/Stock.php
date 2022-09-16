<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    //
    //protected $connection = 'pgsql';
    protected $table='stock';
    protected $primarykey='id';
    public $timestamps=false;

  
    protected $fillable=['cod_producto','almacen_id','stock_diponible','stock_seguridad','cantidad_mano',
                         'total_soles','fecha','periodo','stockmaster','cod_proveedor','hora','cod_odoo'];

}
