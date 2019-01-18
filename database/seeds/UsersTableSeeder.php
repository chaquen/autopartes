<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	User::truncate();

        $user = new User;
        $user->name = "Stalin Chacon";
        $user->email = "stalin1@misena.edu.co";
        $user->password = bcrypt('123');
        $user->telefono = "6236344";
        $user->direccion = "Cl 78 # 234 - 67";
        $user->rol_id = 1;
        $user->save();

        $user = new User;
        $user->name = "Edgar Guzman";
        $user->email = "stalindesarrollador@gmail.com";
        $user->password = bcrypt('123');
        $user->telefono = "4776354";
        $user->direccion = "Cr 45 # 56 - 44";
        $user->rol_id = 2;
        $user->save();

        $user = new User;
        $user->name = "Camilo Monrroy";
        $user->email = "stalinchacu@outlook.com";
        $user->password = bcrypt('123');
        $user->telefono = "788654";
        $user->direccion = "Cr 26 # 746 - 56";
        $user->rol_id = 2;
        $user->save();

        $user = new User;
        $user->name = "Angelica Loaiza";
        $user->email = "josepolytropo@gmail.com";
        $user->password = bcrypt('123');
        $user->telefono = "8743528";
        $user->direccion = "Cl 27 # 32 - 87";
        $user->rol_id = 3;
        $user->save();

        $user = new User;
        $user->name = "Paola Cardona";
        $user->email = "stalin@mohansoft.com";
        $user->password = bcrypt('123');
        $user->telefono = "2983754";
        $user->direccion = "Cr 84 #29 - 74";
        $user->rol_id = 4;
        $user->save();


    }
}
