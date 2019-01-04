<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\UsuarioCreado;
use App\User;
use App\Rol;
class UsuariosController extends Controller
{
	public function panel()
	{
		return view('admin.panel');
	}
    public function index()
    {
        $user = auth()->user()->id;

        //dd($user);
        $usuarios = User::select('users.id',
																 'name',
																 'email',
																 'telefono',
																 'direccion',
																 'rol_id',
																 'rols.nombre')
									        ->join('rols','users.rol_id','=','rols.id')
													->get();
				$roles=Rol::all();
        return view('auth.index', compact('usuarios','user','roles'));
    }

    public function crear()
    {
        $roles=Rol::all();
				//dd($roles);
        return view('admin.usuarios.crear',compact('roles'));
    }

    public function almacenarUsuario(Request $request)
    {
        //dd($request);
				$data = $request->validate([
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users',
          'telefono'=> 'required|unique:users',
          'direccion'=>'required',
					'rol'=>'required'
        ]);
				//dd($data);
        //Instanciamos un objeto del modelo User, para guardar los datos en la BD
        $user = new User;

        $pass = str_random(8);
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = bcrypt($pass);
        $user->telefono = $request->get('telefono');
        $user->direccion = $request->get('direccion');
        $user->rol_id = $request->get('rol');
        $user->save();

        $user = $user->email;
        //dd($user);
        //dd($pass);
        //Enviamos el email con las credenciales de acceso
        UsuarioCreado::dispatch($user, $pass);
        return redirect('admin')->with('flash','El usuario fue creado exitosamente');
    }
    public function actualizar_usuario(Request $request)
    {
     //dd([$request,$request->get('nombre')]);

     User::where('id',$request['id'])->update([
                                        "name"=>$request->get('nombre'),
                                        "email"=>$request->get('email'),
                                        "telefono"=>$request->get('telefono'),
                                        "direccion"=>$request->get('direccion'),
																				"rol_id"=>$request->get('rol')
                                        ]);
     return back()->with('flash','El usuario fue actualizado exitosamente');
    }
}
