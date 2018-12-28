@extends('admin.layout')

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
		        		<th>fecha creación</th>
		        	</tr>
		        </thead>
		        
		        <tbody>
		        	@foreach($ordenAsignadas as $orden)	
		        		<tr>
		        			<td>{{ $orden->id }}</td>
		        			<td>{{ $orden->nombre }}</td>
		        			<td>{{ $orden->name }}</td>
		        			<td>{{ $orden->created_at }}</td>
		        			<td>
		        				<a href="{{ route('detalle.asignadas', $orden->id) }}" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Ver Detalle</a>
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