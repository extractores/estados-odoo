<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    //
    protected $table='promocions';
    protected $primarykey='id';
    public $timestamps=true;

    protected $fillable=['id_odoo','active','promotion_code','description','promotion_type',
                         'manufacter_id','fabricante_promo','company_supplier','from_date','to_date','isztype','is_chatbot',
                        'qty_products_x_factor','qty_products_x_condition','qty_products_x_condition_max','qty_products_z','qty_max_products_z',
                         'product_bonf_z','producto','discount_line_product_id','minimum_quantity_discount','maximun_quantity_discount','products_discount',
                        'isztype_two'];
                        
}
