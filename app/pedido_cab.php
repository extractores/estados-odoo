<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pedido_cab extends Model
{
    //
    protected $table='pedido_cab';
    protected $primaryKey = 'id';
    public $timestamps=false;

    protected $fillable=['id_odoo','nro_order','date_order','date_emision','company_id','ruta_master','ruta_dia','date_delivery','state','tipo_documento',
    'numero_comprobante','fecha_liquidacion','total_amt','fecha_cobranza','total_amt_inv','total_discount','id_almacen','reason_reject',
     'forma_pago','codigo_dir','dir_entrega','price_list','modulo','idcliente_odoo','idclie','razon_social','cod_distrito','distrito','ubigeo',
     'seller_id','seller_name','driver_id','driver','vehicle_id','vehicle','periodo','giro_id','giro','id_canal','canal','logx',
     'laty','schedule','dis_visita','idmone','tipocambio','id_mot_r','id_list_price','officeId','officeId_name','fecha_add','fecha_modi',
     'estado_sunat','estado_caja'
];



}
