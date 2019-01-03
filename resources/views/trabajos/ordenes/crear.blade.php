@extends('admin.layout')
@section('header')
	<h1>
	    Ordenes
	    <small> En esta sección podra crear las ordenes</small>
  	</h1>
  	<ol class="breadcrumb">
	    <li class="active">Ordenes</li>
  	</ol>
@stop

@section('contenido')

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
			    <div class="box-header">
			      <h3 class="box-title">Ingrese los datos para crear una nueva orden</h3>
			    </div>
			    <!-- /.box-header -->
			    
		    	<div class="box-body">					
		    		<div class="form-group col-md-3">
						<label>Sede</label>
						<select class="form-control" name="sede" id="sede" required>
							<option value="">Selecciona una Sede</option>
							@foreach($sedes as $sede)
								<option value="{{ $sede->id }}">- {{ $sede->nombre }}</option>
							@endforeach
						</select>
					</div>
		    		<div class="form-group col-md-3">
		    			<label>Marca</label>
		    			<input name="marca" id="marca" class="form-control" placeholder="Ingrese la marca" required></input>
		    		</div>
		    		<div class="form-group col-md-3">
		    			<label>Refencia</label>
		    			<input name="direccion" id="referencia" class="form-control" placeholder="Ingrese la referencia" required></input>
		    		</div>
		    		<div class="form-group col-md-3">
		    			<label>Cantidad</label>
		    			<input type="number" name="cantidad" id="cantidad" class="form-control" placeholder="Ingrese la cantidad" required></input>
		    		</div>
		    		<div class="form-group col-md-6">
		    			<label>Descripción</label>
		    			<input name="descripcion" id="descripcion" class="form-control" placeholder="Ingrese la descripcion" required></input>
		    		</div>			    		
		    		<div class="form-group col-md-6">
		    			<label>Comentarios</label>
		    			<input name="comentarios" id="comentarios" class="form-control" placeholder="Ingrese los comentarios"></input>
		    		</div>
		    		<div class="form-group">
		    			<input class="btn btn-warning" value="agregar" onclick="agregar_a_list()">
		    		</div>
		    	</div>

				<form id="crearOrden" method="POST" action="{{ route('ordenes.almacenar') }}">
		    		{{ csrf_field() }}
		    		<table class="table ">
		    			<thead>
		    				<tr class="bg-primary">
		    					<th>Sede</th>
			    				<th>Marca</th>
			    				<th>Refencia</th>
			    				<th>Cantidad</th>
			    				<th>Descripción</th>
			    				<th>Comentarios</th>
		    				</tr>
		    			</thead>

		    			<tbody id="body_table">
		    				
		    			</tbody>
		    		</table>

		    		<div class="form-group col-md-3 col-md-offset-3">
						
						<select class="form-control" name="convencion" id="convencion" required>
							<option value="">Selecciona el tipo de factura</option>
							@foreach($convenciones as $conven)
								<option value="{{ $conven->id }}">- {{ $conven->nombreConvencion }}</option>
							@endforeach
						</select>
					</div>
		    		
		    		<div class="form-group col-md-4">
		    			<button type="submit" class="btn btn-primary">Crear Orden</button>
		    		</div>	        
		    	</form>
		    </div>	    	    			
		</div>			
	</div>
	<script type="text/javascript">

	    var arreglo=[];

	    function agregar_a_list(){
	        
	        var ob={
	        	sede:document.getElementById('sede').value,
	            marca:document.getElementById('marca').value,
	            referencia:document.getElementById('referencia').value,
	            cantidad:document.getElementById('cantidad').value,
	            descripcion:document.getElementById('descripcion').value,
	            comentarios:document.getElementById('comentarios').value
	        };

	        arreglo.push(ob);
	        
	        document.getElementById('sede').value="";
	        document.getElementById('marca').value="";   
	        document.getElementById('referencia').value="";
	        document.getElementById('cantidad').value="";
	        document.getElementById('descripcion').value="";
	        document.getElementById('comentarios').value="";

	        draw_table();
	    }

	    function draw_table(){
	        console.log(arreglo);
	        var t = document.getElementById('body_table');
	        //limpio lo que tenia en la tabla
	        t.innerHTML="";
	        for(var f in arreglo){

	            //creo un tr
	            var tr = document.createElement('tr');  
	            //creo un td
	            
	            var td = document.createElement('td');  
	            //creo un label
	            var label = document.createElement('label');
	            var hd = document.createElement('input');
	            hd.setAttribute('type','hidden');
	            hd.setAttribute('name','sede[]');
	            hd.value=arreglo[f].sede;    
	            label.innerHTML=arreglo[f].sede;
	            td.appendChild(hd);
	            td.appendChild(label);
	            //agrego el campo a la fila de la tabla
	            tr.appendChild(td);
	            
	            var td = document.createElement('td');  
	            //creo un label
	            var label = document.createElement('label');
	            var hd = document.createElement('input');
	            hd.setAttribute('type','hidden');
	            hd.setAttribute('name','marca[]');
	            hd.value=arreglo[f].marca;    
	            label.innerHTML=arreglo[f].marca;
	            td.appendChild(hd);
	            td.appendChild(label);
	            //agrego el campo a la fila de la tabla
	            tr.appendChild(td);
	            
	            var td = document.createElement('td');  
	            //creo un label
	            var label = document.createElement('label');
	            var hd = document.createElement('input');
	            hd.setAttribute('type','hidden');
	            hd.setAttribute('name','referencia[]');
	            hd.value=arreglo[f].referencia;    
	            label.innerHTML=arreglo[f].referencia;
	            td.appendChild(hd);
	            td.appendChild(label);
	            //agrego el campo a la fila de la tabla
	            tr.appendChild(td);
	            
	            var td = document.createElement('td');  
	            //creo un label
	            var label = document.createElement('label');
	            var hd = document.createElement('input');
	            hd.setAttribute('type','hidden');
	            hd.setAttribute('name','cantidad[]');
	            hd.value=arreglo[f].cantidad;    
	            label.innerHTML=arreglo[f].cantidad;
	            td.appendChild(hd);
	            td.appendChild(label);
	            //agrego el campo a la fila de la tabla
	            tr.appendChild(td);
	            
	            var td = document.createElement('td');  
	            //creo un label
	            var label = document.createElement('label');
	            var hd = document.createElement('input');
	            hd.setAttribute('type','hidden');
	            hd.setAttribute('name','descripcion[]');
	            hd.value=arreglo[f].descripcion;    
	            label.innerHTML=arreglo[f].descripcion;
	            td.appendChild(hd);
	            td.appendChild(label);
	            //agrego el campo a la fila de la tabla
	            tr.appendChild(td);
	            
	            var td = document.createElement('td');  
	            //creo un label
	            var label = document.createElement('label');
	            var hd = document.createElement('input');
	            hd.setAttribute('type','hidden');
	            hd.setAttribute('name','comentarios[]');
	            hd.value=arreglo[f].comentarios;    
	            label.innerHTML=arreglo[f].comentarios;
	            td.appendChild(hd);
	            td.appendChild(label);
	            //agrego el campo a la fila de la tabla
	            tr.appendChild(td);
	            
	            
	            //agrego la fila a el cuerpo de la tabla
	            t.appendChild(tr);
	        }
	    }
	</script>				
@stop