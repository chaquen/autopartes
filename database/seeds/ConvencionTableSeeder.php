<?php

use Illuminate\Database\Seeder;
use App\Convencion;

class ConvencionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Convencion::truncate();

        $convencion = new Convencion;
        $convencion->nombre = "Dolar";
        $convencion->save();

        $convencion = new Convencion;
        $convencion->nombre = "Pesos Colombianos";
        $convencion->save();
    }
}
