<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cliente extends Model
{
    //

    protected $table='clientes';
    protected $primaryKey = 'id';
    public $timestamps=true;

    protected $fillable=['companyId','paternalSurname','maternalSurname','name','documentType','documentNumber','address','latitude','longitude','routeId','clientCode'];

}
