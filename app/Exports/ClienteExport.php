<?php

namespace App\Exports;

use App\cliente;
use Maatwebsite\Excel\Concerns\FromCollection;


class ClienteExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return cliente::all();
    }
}
