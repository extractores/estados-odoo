<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pedido_det extends Model
{
    //
    protected $table='pedido_det';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['id_ordder','product_id','product_name','tipo_producto','unidad_medida',
                         'precio_compra','codigo_fabricante','discount_product','unidades_complementarias_faltantes_master',
                         'price_unit','product_uom_qty','tax_id','impuesto','tipo_impuesto','monto_impuesto','is_reward_line',
                         'cod_promocion','nombre_promo','precio_sin_igv','id_linea_odo'
                        ];

}
