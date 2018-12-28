@extends('admin.layout')

@section('header')
	<h1>
	    Ordenes sin asignar
	    <small> En esta sección podra asignar un usuario a la orden para que la gestione</small>
  	</h1>
  	<ol class="breadcrumb">
	    <li class="active">Ordenes</li>
  	</ol>
@stop

@section('contenido')
	<div class="box">
	    <div class="box-header">
	      <h3 class="box-title">Detalle de las Ordenes</h3>
	    </div>
	    <!-- /.box-header -->
	    <div class="box-body">
	      	<table id="sedes-table" class="table table-bordered table-striped">
		        <thead>
		        	<tr>
		        		<th>Id</th>
		        		<th>Estado</th>
		        		<th>Cliente</th>
		        	</tr>
		        </thead>
		        
		        <tbody>
		        	@foreach($sinUsuario as $orden)	
		        		<tr>
		        			<td>{{ $orden->id }}</td>
		        			<td>{{ $orden->nombre }}</td>
		        			<td>{{ $orden->name }}</td>
		        			<td>
		        				<a href="{{ route('ordenes.detalle', $orden->id) }}" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Ver Detalle</a>
			        			<a href="" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></a>
			        			<a href="" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
			        		</td>
		        		</tr>
		        	@endforeach		        	
		        </tbody>
	      	</table>
	    </div>
	    <!-- /.box-body -->
  	</div>	
@stop