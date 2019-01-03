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
        $estadoOrden->nombreEstado = "Por Cotizar";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Cotizado";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Orden";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Ordenado";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Entregado";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Cancelado";
        $estadoOrden->save();
    }
}
