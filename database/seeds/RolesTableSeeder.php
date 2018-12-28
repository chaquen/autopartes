<?php

use Illuminate\Database\Seeder;
use App\Rol;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::truncate();

        $rol = new Rol;
        $rol->nombre = "Administrador";
        $rol->save();

        $rol = new Rol;
        $rol->nombre = "Empleado";
        $rol->save();

        $rol = new Rol;
        $rol->nombre = "Cliente";
        $rol->save();
    }
}
