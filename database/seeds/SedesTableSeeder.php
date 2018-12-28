<?php

use Illuminate\Database\Seeder;
use App\Sede;

class SedesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sede::truncate();

        $sede = new Sede;
        $sede->user_id = 1;
        $sede->nombre = "Chico";
        $sede->direccion = "Cl 98 # 34 - 56";
        $sede->telefono = "3445653";
        $sede->contactoSede = "Jaime Montoya";
        $sede->save();

        $sede = new Sede;
        $sede->user_id = 1;
        $sede->nombre = "Usaquen";
        $sede->direccion = "Cr 9 # 134 - 23";
        $sede->telefono = "8866432";
        $sede->contactoSede = "Andrea Nuñez";
        $sede->save();

        $sede = new Sede;
        $sede->user_id = 1;
        $sede->nombre = "Galerias";
        $sede->direccion = "Cl 53 # 24 - 98";
        $sede->telefono = "6452894";
        $sede->contactoSede = "Alexander Benavidez";
        $sede->save();
    }
}
