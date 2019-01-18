<?php

namespace App\Http\Controllers\Trabajos;

use App\Orden;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FacturasController extends Controller
{
    public function facturasOrden()
    {
    	$orden = Orden::where('ordens.estado_id','=',13)
    	->get();

    	dd($orden);
    }
}
