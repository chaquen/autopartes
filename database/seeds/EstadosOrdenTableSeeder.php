<?php

use Illuminate\Database\Seeder;
use App\EstadoOrden;

class EstadosOrdenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoOrden::truncate();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombre = "Cotizado";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombre = "Ordenado";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombre = "Despachado";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombre = "Facturado";
        $estadoOrden->save();
    }
}
