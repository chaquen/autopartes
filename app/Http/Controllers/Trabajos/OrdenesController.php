<?php

namespace App\Http\Controllers\Trabajos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $ordenes = Orden::select('ordens.id','Trm','ordens.created_at','estado_ordens.nombreEstado','users.name','convencions.nombreConvencion')
        ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
        ->join('users','ordens.user_id','=','users.id')
        ->join('convencions','ordens.convencion_id','=','convencions.id')
        ->get();
        
        return view('trabajos.ordenes.index', compact('ordenes'));
    }

    public function porUsuario()
    {   
        $user = auth()->user()->id;
        $ordenes = Orden::select('ordens.id','Trm','ordens.created_at','estado_ordens.nombreEstado','users.name','convencions.nombreConvencion')
        ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
        ->join('users','ordens.user_id','=','users.id')
        ->join('convencions','ordens.convencion_id','=','convencions.id')
        ->where('user_id','=',$user)
        ->get();
        
        return view('trabajos.ordenes.misOrdenes', compact('ordenes'));
    }

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

    //metodo para almacenar las nuevas ordenes 
    public function almacenar(Request $request)
    {
    	//dd($request);
    	//Almacenamos los datos de la orden
    	$orden = new Orden;
    	$orden->estado_id = 1;
    	$orden->user_id = auth()->user()->id;
    	$orden->convencion_id = $request->get('convencion');
    	$orden->trm = 2000;
    	$orden->save();    

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

        $historialOrden = new historialOrden;
        $historialOrden->orden_id = $orden->id;
        $historialOrden->estadoAnterior_id = 1;
        $historialOrden->estadoActual_id = 1;
        $historialOrden->userGestiona_id = auth()->user()->id;
        $historialOrden->save();

    	//retornamos a la vista
    	return back()->with('flash','La orden ha sido creada');
    }

    //consulta de Ordenes sin Asignar .........................................................//
    public function cotizadas()
    {   
        //$sinUsuario = Orden::where('ordens.estado_id','=',1)->get();

        $sinUsuario = Orden::select('ordens.id','ordens.created_at','estado_ordens.nombreEstado','users.name')
        ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
        ->join('users','ordens.user_id','=','users.id')
        ->where('ordens.estado_id','=','1')
        ->get();
        
        return view('trabajos.ordenes.cotizadas', compact('sinUsuario'));
    }

    //Detalle de las ordenes sin Asignar .......................................................
    public function detalleCotizadas($orden_id)
    {   
        $detalleOrden = itemOrden::select('sedes.nombre','item_ordens.marca','item_ordens.referencia','item_ordens.descripcion','item_ordens.cantidad','item_ordens.comentarios','item_ordens.orden_id')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->join('historial_Ordens','historial_Ordens.orden_id','ordens.id')
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
        $historialOrden->estadoAnterior_id = 1;
        $historialOrden->estadoActual_id = 1;
        $historialOrden->userGestiona_id = $UserGestiona;
        $historialOrden->userAsignado_id = $request->get('usuarioAsignado');
        $historialOrden->save();

        $Orden = Orden::where('id', $request->get('ordenId'))->first();
        $Orden->update();

        return redirect('trabajos/ordenes/cotizadas')->with('flash','El Usuario fue asignado');
    }

    //Consulta de ordenes asignada...............................................................
    public function asignadas()
    {

        $ordenAsignadas = Orden::select('ordens.id','ordens.created_at','estado_ordens.nombreEstado','users.name','historial_Ordens.userAsignado_id')
            ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
            ->join('users','ordens.user_id','=','users.id')
            ->join('historial_Ordens','historial_Ordens.orden_id','ordens.id')
            ->where('historial_Ordens.userAsignado_id','!=','null')
            ->get();
        //dd($ordenAsignadas);                    
        return view('trabajos.ordenes.asignadas', compact('ordenAsignadas'));
    }

    //Detalle ordenes asignadas ..........................................................
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
        //dd($detalleOrden);
        return view('trabajos.ordenes.detalleAsignada', compact('detalleOrden','variables'))->with('orden_id',$orden_id);
    }

    //Actualización de los items de las ordenes ............................................................................
    public function update(Request $request)
    {          

        
        
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
        
}
