<?php

namespace App\Http\Controllers\Trabajos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Sede;

class SedesController extends Controller
{
	public function index()
	{
		$user = auth()->user()->id;

		//dd($user);
		$sedes = Sede::select('id','nombre','telefono','direccion','contactoSede')
		->where('user_id','=',$user)
		->get();
		return view('trabajos.sedes.index', compact('sedes'));

	}

    public function crear()
    {
    	return view('trabajos.sedes.crear');
    }

    public function almacenar(Request $request)
    {
    	//return Sede::create($request->all());

    	$sede = new Sede;

    	$sede->user_id = auth()->user()->id;
    	$sede->nombre = $request->get('nombre');
    	$sede->direccion = $request->get('direccion');
    	$sede->telefono = $request->get('telefono');
    	$sede->contactoSede = $request->get('contacto');
    	$sede->save();

    	return back()->with('flash','La sede ha sido creada');
    }

    public function actualizar(Request $request, $sede_id)
    {	
    	//dd($sede_id);
    	$sede = Sede::where('id', $sede_id)->first();
    	$sede->valor = $request->get('nombre');
    	$sede->valor = $request->get('direccion');
    	$sede->valor = $request->get('telefono');
    	$sede->valor = $request->get('contacto');
    	$sede->update();

		return view('trabajos.sedes.index');
        //return view('admin.variables.index')->with('success','Se actualizo exitosamente');
    }
}
