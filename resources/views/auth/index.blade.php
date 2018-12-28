@extends('admin.layout')

@section('contenido')
	<div class="box box-primary">
	    <div class="box-header">
	      <h3 class="box-title">Detalle de los Usuarios</h3>
	    </div>
	    <!-- /.box-header -->
	    <div class="box-body">
	      <table id="sedes-table" class="table table-bordered table-striped">
	        <thead>
	        	<tr>
	        		<th>Nombre</th>
	        		<th>Email</th>
	        		<th>Telefono</th>
	        		<th>Contacto</th>
	        		<th>Rol</th>
	        	</tr>
	        </thead>
	        
	        <tbody>
	        	@foreach($usuarios as $usuario)	
	        		<tr>
	        			<td>{{ $usuario->name }}</td>
	        			<td>{{ $usuario->email }}</td>
	        			<td>{{ $usuario->telefono }}</td>
	        			<td>{{ $usuario->direccion }}</td>
	        			<td>{{ $usuario->nombre }}</td>
	        			
	        			{{--
	        			<td>
		        			<button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#exampleModal{{$usuario->id}}">Editar</button>
		        			<a href="" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
		        			<div class="modal fade" id="exampleModal{{$usuario->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

								<form method="POST" action="{{ route('trabajos.sedes.update',[$usuario->id]) }}">
						    	{{ csrf_field() }}
							    	<div class="modal-dialog" role="document">

									    <div class="modal-content">

									      <div class="modal-header">
									        <h5 class="modal-title" id="exampleModalLabel">{{$usuario->nombre}}</h5>
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
									          <span aria-hidden="true">&times;</span>
									        </button>
									      </div>

									      <div class="modal-body">

								        	<div class="form-group">
								    			<label>Nombre</label>
								    			<input name="nombre" class="form-control" value="{{ $usuario->nombre }}" required></input>
								    		</div>
								    		<div class="form-group">
								    			<label>Dirección</label>
								    			<input name="direccion" class="form-control" value="{{ $usuario->direccion }}" required></input>
								    		</div>
								    		<div class="form-group">
								    			<label>Telefono</label>
								    			<input name="telefono" class="form-control" value="{{ $usuario->telefono }}" required></input>
								    		</div>
								    		<div class="form-group">
								    			<label>Nombre Contacto</label>
								    			<input name="contacto" class="form-control" value="{{ $usuario->contactoSede }}" required></input>
								    		</div>

									      </div>
									      <div class="modal-footer">
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
									        <button type="submit" class="btn btn-primary">Editar Sede</button>
									      </div>
									    </div>
									</div>								
								</form>
					  		</div>
		        		</td>--}}

		        		
	        		</tr>
	        	@endforeach
	        	
	        </tbody>
	      </table>
	    </div>
	    <!-- /.box-body -->
	  </div>	
@stop