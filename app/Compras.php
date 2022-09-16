<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compras extends Model
{
    //

    protected $table='compras';
    protected $primaryKey = 'id';
    public $timestamps=false;

    protected $fillable=['company_id','nro_order','estado_orden','date_approve','date_planned','numero_documento_proveedor'
    ,'amount_tax','amount_total','warehouse_id','warehouse_name','id_odoo'];

}
