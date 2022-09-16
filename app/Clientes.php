<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    //
    protected $table='clientes';
    protected $primaryKey = 'id';
    public $timestamps=false;

    protected $fillable=['idoo','emp_id','cli_idclie','cli_nrodoc','cli_ruc','cli_apepa','cli_apema'
    ,'cli_nombre','cli_razsoc','cli_domifis','date_birthday','cli_tlf','cli_fax','cli_email','idcategoabc','pais','departamento','provincia',
    'distrito','pricelist_id','pricelist','estado_cliente','es_fabricante','es_conducto','validado_sunat','tipo_doc','ubigeo','id_vendedor','vendedor',
     'ruta_dia'];

}
