@extends('admin.layout')
@section('header')
	<h1>
	    Ordenes
	    <small> En esta sección se puede ver a detalle las ordenes creadas por <b>{{ auth()->user()->name }}</b></small>
  	</h1>
  	<ol class="breadcrumb">
	    <li class="active">Ordenes</li>
  	</ol>
@stop

@section('contenido')
	<div class="box box-success">
	    <div class="box-header">
	      <h3 class="box-title">Detalle de las ordenes de <b>{{ auth()->user()->name }}</b></h3>
	    </div>
	    <!-- /.box-header -->
	    <div class="box-body">
	      <table id="sedes-table" class="table table-bordered table-striped">
	        <thead class="bg-primary">
	        	<tr>
	        		<th>id</th>
	        		<th>Cliente</th>
	        		<th>Estado</th>
	        		<th>Convención</th>
	        		<th>TRM de la fecha</th>
	        		<th>Fecha Creación</th>
	        		<th>Acciones</th>
	        	</tr>
	        </thead>
	        
	        <tbody class="bg-success"> 
	        	@foreach($ordenes as $orden)	
	        		<tr>
	        			<td>{{ $orden->id }}</td>
	        			<td>{{ $orden->name }}</td>
	        			@if($orden->nombreEstado == 'Por Cotizar Asignado' || $orden->nombreEstado == 'Por Cotizar Sin Asignar')
	        				<td>Por Cotizar</td>
	        			@elseif($orden->nombreEstado == 'Cotizado Sin Asignar' || $orden->nombreEstado == 'Cotizado Asignado' )
        				 	<td>Cotizado</td>

	        			@elseif($orden->estado_id == 4)
	        				<td>Cotizado</td>

        				@elseif($orden->estado_id == 8 || $orden->estado_id == 9)
	        				<td>Orden</td>

	        			@elseif($orden->estado_id == 13 )
	        				<td>Pendiente Factura</td>
	        			@endif

	        			
	        			<td>{{ $orden->nombreConvencion }}</td>
	        			<td>{{ $orden->Trm }}</td>
	        			<td>{{ $orden->created_at }}</td>
	        			<td>

	        				<a href="{{ route('ordenes.detalleUsuario', $orden->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i> Ver Detalle</a>
	        				@if($orden->estado_id == 3 || $orden->estado_id == 4 || $orden->estado_id == 2)
	        					<a href="{{ route('ordenes.editar', $orden->id) }}" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
		        				<a href="" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
	        				@endif		        			
		        		</td>		        		
	        		</tr>
	        	@endforeach
	        	
	        </tbody>
	      </table>
	    </div>
	    <!-- /.box-body -->
	  </div>	
@stop