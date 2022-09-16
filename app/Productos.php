<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    //

    protected $table='productos';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['tipoproducto','emp_id','prov_id','proveedor','codart',
                          'producto','nombrecorto','idcategoria','categoria','idlinea','linea','idsubcategoria',
                          'subcategoria','idmarca','marca','idsubmarca','submarca','codori','empaquecompra','upreoriginal',
                          'empaquevta','undpresenta','estado_articulo','estado_compra','estado_venta','peso','art_unimed',
                          'volumen','preciovta','preciocompra','tipoigv','igv',
                          'unidad_sunat','codigo_compra_proveedor','codigo_barras','create_date','write_date','write_uid'
                          ,'cod_odoo','conver_master_unidad'
                ];


}
