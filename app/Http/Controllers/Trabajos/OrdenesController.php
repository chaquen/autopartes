<?php

namespace App\Http\Controllers\Trabajos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\NotificationEvent;
use App\Sede;
use App\Orden;
use App\User;
use App\ItemOrden;
use App\HistorialOrden;
use App\VariableEditable;
use App\Convencion;
use DB;

class OrdenesController extends Controller
{

    public function index()
    {
        $ordenes = Orden::select('ordens.id','Trm','ordens.estado_id','ordens.created_at','estado_ordens.nombreEstado','users.name','convencions.nombreConvencion')
        ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
        ->join('users','ordens.user_id','=','users.id')
        ->join('convencions','ordens.convencion_id','=','convencions.id')
        ->get();
        //dd($ordenes);
        return view('trabajos.ordenes.index', compact('ordenes'));
    }
    
    //Listado de Ordenes por cada usuario
    public function porUsuario()
    {
        $user = auth()->user()->id;
        $ordenes = Orden::select('ordens.id','Trm','ordens.estado_id','ordens.created_at','estado_ordens.nombreEstado','users.name','convencions.nombreConvencion')
        ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
        ->join('users','ordens.user_id','=','users.id')
        ->join('convencions','ordens.convencion_id','=','convencions.id')
        ->where('user_id','=',$user)
        ->get();
        //dd($ordenes);
        return view('trabajos.ordenes.misOrdenes', compact('ordenes'));
    }

    //Listado de Ordenes Asignadas al usuario
    public function misAsignadas()
    {
        $user = auth()->user()->id;
        //dd($user);
        $ordenAsignadas = Orden::select('ordens.id','ordens.estado_id','ordens.created_at','historial_ordens.userAsignado_id')
        ->join('historial_ordens','historial_ordens.orden_id','=','ordens.id')
        ->where('historial_ordens.userAsignado_id','=',$user)->first()
        ->whereIn('ordens.estado_id',[3,6,12])
        ->get();
        //dd($ordenAsignadas);

        return view('trabajos.ordenes.asignadas_a_mi', compact('ordenAsignadas'));
    }

    //Detalle de la Orden de usuario en estado Precotizado o Cotizado ..................................
    public function detalleUsuario($orden_id)
    {
        $detalleOrden = itemOrden::select('ordens.id','ordens.estado_id','sedes.nombre','item_ordens.estadoItem_id','item_ordens.sede_id','item_ordens.id','marca','referencia','descripcion','cantidad','comentarios','pesoLb','costoUnitario','margenUsa')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->where('ordens.id','=',$orden_id)
        ->where('item_ordens.estadoItem_id','=',1)
        ->get();
        //dd($detalleOrden);
        $variables = VariableEditable::all();
        //dd($detalleOrden);
        $detallePeso = DB::table('item_ordens')
        ->join('ordens','item_ordens.orden_id','ordens.id')
        ->join('sedes','item_ordens.sede_id','sedes.id')
        ->where('ordens.id','=',$orden_id)
        ->select('ordens.id','sede_id','sedes.nombre',
                DB::raw('sum(pesoLb * cantidad) as PesoSede'),
                DB::raw('sum(cantidad) as cantidadProductos'),
                DB::raw('count(cantidad) as cantidadSede'))
        ->groupBy('sedes.id')
        ->get();

        return view('trabajos.ordenes.detalleUsuario', compact('detalleOrden','variables','detallePeso'))->with('orden_id',$orden_id);
    }

    //Crear Retornar el formualrio para crear nuevas ordenes ....................................
    public function crear()
    {
    	$user = auth()->user()->id;

		//dd($user);
		$sedes = Sede::select('id','nombre','telefono','direccion','contactoSede')
		->where('user_id','=',$user)
		->get();
		$convenciones = Convencion::select('id','nombreConvencion')
		->get();
		return view('trabajos.ordenes.crear', compact('sedes','convenciones'));
    }

    //metodo para almacenar las nuevas ordenes ...................................................
    public function almacenar(Request $request)
    {
    	//dd($request);
    	//Almacenamos los datos de la orden
        $trm = VariableEditable::select('variable_editables.valor')
        ->where('variable_editables.id','=',1)
        ->get();

        //dd($trm);
    	$orden = new Orden;
    	$orden->estado_id = 2;
    	$orden->user_id = auth()->user()->id;
    	$orden->convencion_id = $request->get('convencion');
    	$orden->trm = $trm[0]->valor;
    	$orden->save();

        //Se crea el historial
        $historialOrden = new historialOrden;
        $historialOrden->orden_id = $orden->id;
        $historialOrden->estadoActual_id = 2;
        $historialOrden->save();

        //Recorremos cada detalle que trae los productos relacionados a la Orden y los almacenamos
    	foreach ($request['sede'] as $key => $value)
    	{
    		$item = new ItemOrden;
    		$item->estadoItem_id = 1;
	    	$item->sede_id = $value;
            $item->orden_id = $orden->id;
	    	$item->marca = $request['marca'][$key];
	    	$item->referencia = $request['referencia'][$key];
	    	$item->descripcion = $request['descripcion'][$key];
	    	$item->cantidad = $request['cantidad'][$key] ;
	    	$item->comentarios = $request['comentarios'][$key];
	    	$item->save();
    	}



    	//retornamos a la vista
    	return back()->with('flash','La orden ha sido creada');
    }

    //consulta de Ordenes sin Asignar .........................................................//
    public function sinAsignar()
    {
        $sinUsuario = Orden::select('ordens.id','ordens.estado_id','ordens.created_at','estado_ordens.nombreEstado','users.name')
        ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
        ->join('users','ordens.user_id','=','users.id')
        ->whereIn('ordens.estado_id',[8,2])
        //->where('ordens.estado_id','=',8)
        //->orwhere('ordens.estado_id','=',2)
        ->get();
        //dd($sinUsuario);
        return view('trabajos.ordenes.sinAsignar', compact('sinUsuario'));
    }


    public function cotizadas()
    {
        //$sinUsuario = Orden::where('ordens.estado_id','=',1)->get();

        $sinUsuario = Orden::select('ordens.id','ordens.created_at','estado_ordens.nombreEstado','users.name')
        ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
        ->join('users','ordens.user_id','=','users.id')
        ->where('ordens.estado_id','=',5)
        ->orwhere('ordens.estado_id','=',2)
        ->get();
        //dd($sinUsuario);
        return view('trabajos.ordenes.sinAsignar', compact('sinUsuario'));
    }

    //Detalle de las ordenes en estado PreCotizada sin Asignar ...........................................
    public function detalleCotizadas($orden_id)
    {
        $detalleOrden = itemOrden::select('sedes.nombre','item_ordens.marca','item_ordens.referencia','item_ordens.descripcion','item_ordens.cantidad','item_ordens.comentarios','item_ordens.orden_id')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->join('historial_ordens','historial_ordens.orden_id','ordens.id')
        ->where('ordens.id','=',$orden_id)
        ->get();

        //dd($detalleOrden[0]->orden_id);
        $user = User::all();
        return view('trabajos.ordenes.detalleCotizadas', compact('detalleOrden','user'));
    }

    //Asignar usuario a la orden ..................................................................
    public function asignarUsuarioOrden(Request $request)
    {   
        $UserGestiona = auth()->user()->id;

        $historialOrden = new historialOrden;
        $historialOrden->orden_id = $request->get('ordenId');

        $Orden = Orden::where('id', $request->get('ordenId'))->first();
        //dd($Orden->estado_id);
        if($Orden->estado_id == 2)
        {
            $historialOrden->estadoActual_id = 3;
        }
        if($Orden->estado_id == 8)
        {
            $historialOrden->estadoActual_id = 9;
        }

        //dd($historialOrden->estadoActual_id);

        $historialOrden->userAsignado_id = $request->get('usuarioAsignado');
        $historialOrden->save();

        if($Orden->estado_id == 2)
        {
            $Orden->estado_id = 3;
        }
        if($Orden->estado_id == 8)
        {
            $Orden->estado_id = 9;
        }
        
        $Orden->update();

        $sinUsuario = Orden::select('ordens.id','ordens.created_at','estado_ordens.nombreEstado','users.name')
        ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
        ->join('users','ordens.user_id','=','users.id')
        ->whereIn('ordens.estado_id',[8,2])
        //->where('ordens.estado_id','=',8)
        //->orwhere('ordens.estado_id','=',2)
        ->get();

        //dd($UserGestiona);
        return view('trabajos.ordenes.cotizadaSinAsignar', compact('sinUsuario'))->with('flash','El Usuario fue asignado');
    }

    //Consulta de ordenes asignada...............................................................
    public function asignadas()
    {

        $ordenAsignadas = Orden::select('ordens.id','ordens.estado_id','ordens.created_at','estado_ordens.nombreEstado','users.name')
            ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
            ->join('users','ordens.user_id','=','users.id')
            ->whereIn('ordens.estado_id',[3,9])
            ->get();
        //dd($ordenAsignadas);
        return view('trabajos.ordenes.asignadas', compact('ordenAsignadas'));
    }

    //Detalle ordenes asignadas en estado PreCotizar .............................................
    public function detalleAsignada($orden_id)
    {

        //Seleccionamos los items correspondientes al id que traemos como parametro.
        $detalleOrden = itemOrden::select('ordens.id','sedes.nombre','item_ordens.estadoItem_id','item_ordens.sede_id','item_ordens.id','marca','referencia','descripcion','cantidad','comentarios','pesoLb','costoUnitario','margenUsa')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->where('ordens.id','=',$orden_id)
        ->where('item_ordens.estadoItem_id','=',1)
        ->get();
        //dd($detalleOrden);

        $variables = VariableEditable::all();

        $detallePeso = DB::table('item_ordens')
        ->join('ordens','item_ordens.orden_id','ordens.id')
        ->join('sedes','item_ordens.sede_id','sedes.id')
        ->where('ordens.id','=',$orden_id)
        ->select('ordens.id','sede_id','sedes.nombre',
                DB::raw('sum(pesoLb * cantidad) as PesoSede'),
                DB::raw('sum(cantidad) as cantidadProductos'),
                DB::raw('count(cantidad) as cantidadSede'))
        ->groupBy('sedes.id')
        ->get();
        //dd($detallePeso);

        return view('trabajos.ordenes.detalleAsignada', compact('detalleOrden','variables','detallePeso'))->with('orden_id',$orden_id);
    }

    //Actualización de los items de las ordenes en estado PreCotizar ......................................
    public function update(Request $request)
    {   
        //dd($request);
        //Recorremos si hay algun item dividido *********************************
        foreach ($request['detalle_id'] as $key => $value)
        {

            //Validamos cual item de la orden esta dividido
            if(isset($request['itemDividido'.$value]))
            {
                //dd($request['itemDividido'].$value);

                //Hallamos el tamaño o cantidad de item en los que se dividio
                $tamaño = count($request['itemDividido'.$value]);
                echo "El tamaño del arreglo es ".$tamaño;

                //dd('detener');
                //Vamos a crear los item segun la cantidad necesaria
                foreach ($request['itemDividido'.$value] as $k => $values) {
                    $cantidadItem = $values;

                    //dd($request['sedeId']);

                    $item = new ItemOrden;

                    $item->orden_id = $request->get('ordenId');
                    $item->item_id = $value;
                    $item->estadoItem_id = 1;
                    $item->sede_id = $request['sedeId'][$key];
                    $item->marca = $request['marca'][$key];
                    $item->referencia = $request['referencia'][$key];
                    $item->descripcion = $request['descripcion'][$key];
                    $item->cantidad = $cantidadItem;
                    $item->pesoLb = $request['pesoLb'][$key];
                    $item->comentarios = $request['comentarios'][$key];
                    $item->costoUnitario = $request['costoUnitario'][$key];
                    $item->margenUsa = $request['margenUsa'][$key];
                    $item->save();
                }


                $item = ItemOrden::where('id',$value)->first();
                $item->estadoItem_id = 2;
                $item->update();
            }
        }
        $arr=['pesoLb' =>'','costoUnitario' =>'','margenUsa' =>''];
        foreach ($request['pesoLb'] as $key => $value)
        {
            $detalle_id = $request['detalle_id'][$key];

            if($value!=null)
            {
                $arr['pesoLb'] = $value;

            }else
            {
                unset($arr['pesoLb']);
            }

            if($value!=null)
            {
                //dd($request['costoUnitario'][$key]);
                 $arr['costoUnitario'] = $request['costoUnitario'][$key];
            }else
            {
                unset($arr['costoUnitario']);
            }

            if($value!=null)
            {
                 $arr['margenUsa'] = $request['margenUsa'][$key];
            }else
            {
                unset($arr['margenUsa']);
            }

            if(count($arr)>0)
            {
                //dd($arr);
                DB::table('item_ordens')
                ->where('id', $detalle_id)
                ->update($arr);
                $arr=['pesoLb' =>'','costoUnitario' =>'','margenUsa' =>''];
            }
            
        }
            
        return back()->with('flash','La orden ha sido actualizada');
    }

    //Actulizar la orden a estado Cotizado y envio de datos al cliente ..................................
    public function cotizarOrden($orden_id)
    {
        //dd($orden_id);
        $orden = Orden::where('id', $orden_id)->first();

        //Validamos si el estado es 3 Por cotizar Asignado y lo cambiamos a estado 4 Cotizado
        if($orden->estado_id == 3)
        {
            //Cambiamos el estado de la orden
            $orden->estado_id = 4;
            $orden->update();

            //Creamos el historial con el cambio de estado de la orden
            $historialOrden = new historialOrden;
            $historialOrden->orden_id = $orden_id;
            $historialOrden->estadoActual_id = 4;
            $historialOrden->userAsignado_id = auth()->user()->id;
            $historialOrden->save();

            //Activamos el evento para el envio del Correo
            //NotificationEvent::dispatch(User::where('id',$orden->user_id)->first(),[$orden],"CambioEstadoCotizado");

            //Retornamos a la vista anterior
            return back()->with('flash','La orden ha sido actualizada');
        }

        //Validamos si el estado es 4 Cotizado y lo cambiamos a estado 8 Orden Sin Asignar
        if($orden->estado_id == 4)
        {
            $orden->estado_id = 8;
            $orden->update();

            $historialOrden = new historialOrden;
            $historialOrden->orden_id = $orden_id;
            $historialOrden->estadoActual_id = 8;
            $historialOrden->userAsignado_id = auth()->user()->id;
            $historialOrden->save();
        }
    }

    public function editar()
    {
        $detalleOrden = itemOrden::select('ordens.id','sedes.nombre','item_ordens.estadoItem_id','item_ordens.sede_id','item_ordens.id','marca','referencia','descripcion','cantidad','comentarios','pesoLb','costoUnitario','margenUsa')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->where('ordens.id','=',2)
        //->where('item_ordens.estadoItem_id','=',1)
        ->get();
        //dd($detalleOrden);
        $variables = VariableEditable::all();
        //dd($detalleOrden);
        $orden_id = 2;
        return view('trabajos.ordenes.editarOrden', compact('detalleOrden','variables'))->with('orden_id',$orden_id);
    }  

    public function actualizarEdicion(Request $request)
    {
        //dd($request);
        $arr=['marca' =>'','referencia' =>''];
        foreach ($request['sede'] as $key => $value)
        {
            $detalle_id = $request['detalle_id'][$key];

            if($value!=null)
            {
                //dd($request['costoUnitario'][$key]);
                 $arr['marca'] = $request['marca'][$key];
            }else
            {
                unset($arr['marca']);
            }

            if($value!=null)
            {
                 $arr['referencia'] = $request['referencia'][$key];
            }else
            {
                unset($arr['referencia']);
            }

            if(count($arr)>0)
            {
                //dd($arr);
                DB::table('item_ordens')
                ->where('id', $detalle_id)
                ->update($arr);
                $arr=['marca' =>'','referencia' =>''];
            }
        }
        return back()->with('flash','La orden ha sido actualizada');   
    } 

}
