<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_compras extends Model
{
    //

    protected $table='compras_det';
    protected $primaryKey = 'id';
    public $timestamps=false;

    protected $fillable=['id_compra','product_id','producto','unidad_medida_master','unidad_venta','precio_compra'
    ,'cod_proveedor','proveedor','cantidad_rechazada','unidad_medida_rechazada','conver_master_unidad',
     'cantidad_master','cantidad_unidades','costo_master','price_unit','precio_sin_igv','product_qty',
      'tax_id','impuesto'];
}
