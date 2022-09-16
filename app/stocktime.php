<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stocktime extends Model
{
    //
    protected $table='stocktimes';
    protected $primarykey='id';
    public $timestamps=false;

    protected $fillable=['cod_producto','almacen_id','stock_diponible','stock_seguridad','cantidad_mano',
                         'total_soles','fecha','periodo','masterStockAmount','hora'];


}
