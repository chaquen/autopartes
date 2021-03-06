@extends('admin.layout')

@section('contenido')
	<div class="box box-warning">
	    <div class="box-header col-md-12">
	      <h3 class="box-title">Detalles de la Orden - <b>{{$orden_id}}</b></h3>
	    </div>
	    <!-- /.box-header -->

	    <form class="form" method="POST" action="{{ route('ordenes.update') }}">
		{{ csrf_field() }}
	    <div class="box-body table-responsive col-md-12 bg-warning">
	    	<input type="hidden" name="ordenId" value="{{$orden_id}}">
		      <table class="table table-bordered table-striped table-hover">
		        <thead style="overflow-y: hidden;">
		        	<tr class="bg-warning">
		        		<th>Item</th>
		        		<th>Id Item</th>
		        		<th>Sede</th>
		        		<th>Marca</th>
		        		<th>Referencia</th>
		        		<th>Descripción</th>
		        		<th>Cantidad</th>
		        		<th>Comentarios</th>
		        		<th>Peso Lbs</th>
		        		<th>Peso Promedio</th>
		        		<th>Total Peso Libra</th>
		        		<th>Costo Flete Unidad</th>
		        		<th>Costo Total Flete</th>
		        		<th>Costo Unitario</th>
		        		<th>Margen USA</th>
		        		<th>Precio Unitario USD</th>
		        		<th>Precio Total USD</th>
		        		
		        		<!--<th>Venta Unitario</th>
		        		<th>Total USD</th>
		        		<th>Entrega Proveedor</th>
		        		<th>Bodega</th>
		        		<th>Recepción Bodega</th>
		        		<th>Diás Reales Entrega</th>-->
		        	</tr>
		        </thead>
		        
		        <tbody id="detalle">
        			@php
        				$item = 0;
        			@endphp
		        	@foreach($detalleOrden as $detalle)
		        		@php			        			
		        			$item++;
		        		@endphp
		        		@php
	        				$costoFleteUnidad = $variables[1]->valor * $detalle->pesoPromedio;
	        				$totalPeso = $detalle->cantidad * $detalle->pesoLb;
	        				$costoTotalFlete = $costoFleteUnidad * $totalPeso;	        				
	        			@endphp
	        			@php
        					$a = $detalle->costoUnitario;
        					$b = $detalle->margenUsa;
        					$prom = $a * $b / 100;

        					$c = $costoTotalFlete;
        					$d = $detalle->cantidad;

        					$e = $c / $d;

        					$precioVenta = $a+$prom+$e;
        					$precioTotal = $precioVenta * $detalle->cantidad
        				@endphp

		        		<tr>
		        			<td>{{ $item }}</td>
		        			<td>{{ $detalle->id }}</td>
		        			<td>
		        				{{ $detalle->nombre }}
		        				<input type="hidden" name="sede[]" value="{{ $detalle->nombre }}">
		        				<input type="hidden" name="sedeId[]" value="{{ $detalle->sede_id }}">
		        			</td>
		        			<td>
		        				{{ $detalle->marca }}
		        				<input type="hidden" name="marca[]" value="{{ $detalle->marca }}">
		        			</td>
		        			<td>
		        				{{ $detalle->referencia }}
		        				<input type="hidden" name="referencia[]" value="{{ $detalle->referencia }}">
		        			</td>
		        			<td>
		        				{{ $detalle->descripcion }}
		        				<input type="hidden" name="descripcion[]" value="{{ $detalle->descripcion }}">
		        			</td>
		        			<td>
		        				{{ $detalle->cantidad }}
		       	 				<input type="hidden" id="cantidad{{ $detalle->id }}" name="cantidad[]" value="{{ $detalle->cantidad }}">
		        			</td>
		        			<td>
		        				{{ $detalle->comentarios }}
		        				<input type="hidden" name="comentarios[]" value="{{ $detalle->comentarios }}">
		        			</td>
		        			<td>
		        				<input name="pesoLb[]" id="pesoLb{{$detalle->id}}" value="{{$detalle->pesoLb}}" onchange="calculalPesoLibras({{$detalle->id}},this,{{$detalle->cantidad}})">

		        				<input type="hidden" id="valorPesoLibra{{$detalle->id}}" value="{{$variables[1]->valor}}">
		        			</td>
		        			<td>
		        				@foreach($detallePeso as $detalleP)
		        					@if($detalle->sede_id == $detalleP->sede_id)
		        						@if($detalleP->PesoSede < 9)
		        							@php
		        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
		        							@endphp
		        							<input type="hidden" name="pesoPromedio" value="{{ $promedio }}">
		        							<label>{{ $promedio }}</label>
	        							@else
	        								<label>{{ $detalle->pesoLb }}</label>
        								@endif
		        					@endif	
		        				@endforeach
		        				
		        			</td>
		        			<td class="bg-success">
		        				@foreach($detallePeso as $detalleP)
		        					@if($detalle->sede_id == $detalleP->sede_id)
		        						@if($detalleP->PesoSede < 9)
		        							@php
		        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
		        							$pesoTotal = $promedio;
		        							@endphp
		        							<label>{{ $pesoTotal }}</label>
	        							@else
	        								@php
	        									$pesoTotal = $detalle->pesoLb * $detalle->cantidad;
        									@endphp
    										<label>{{ $pesoTotal }}</label>
        								@endif
		        					@endif	
		        				@endforeach
		        			</td>			        			        			
		        			<td class="bg-danger">
		        				<label id="costoFlete{{$detalle->id}}">{{$costoFleteUnidad}}</label>
		        			</td>
		        			<td class="bg-danger">
		        				@foreach($detallePeso as $detalleP)
		        					@if($detalle->sede_id == $detalleP->sede_id)
		        						@if($detalleP->PesoSede < 9)
		        							@php
			        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
			        							$costoTotalFlete = $promedio * $variables[1]->valor;
			        							$totalFlete = $costoTotalFlete * $detalle->cantidad;

		        							@endphp
		        							<label>{{ $totalFlete }}</label>
	        							@else
	        								@php
	        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;
	        									$totalFlete = $costoTotalFlete * $detalle->cantidad;	
	        								@endphp
	        								<label id="costoTotalFlete{{$detalle->id}}">{{$totalFlete}}</label>
        								@endif
		        					@endif	
		        				@endforeach
		        			</td>
	        				<td>
	        					<input name="costoUnitario[]" value="{{$detalle->costoUnitario}}">
	        				</td>  			
		        			<td>
		        				<input name="margenUsa[]" value="{{$detalle->margenUsa}}">
		        			</td>
		        				
	        				<td>
	        					@foreach($detallePeso as $detalleP)
		        					@if($detalle->sede_id == $detalleP->sede_id)
		        						@if($detalleP->PesoSede < 9)
		        							@php
			        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
			        							$costoTotalFlete = $promedio * $variables[1]->valor;

			        							$a = $detalle->costoUnitario;
					        					$b = $detalle->margenUsa;
					        					$prom = $a * $b / 100;

					        					$precioUnidad = $a + $prom + $costoTotalFlete;

		        							@endphp
		        							<input type="hidden" name="pesoPromedio" value="{{ $promedio }}">
		        							<label>{{ $precioUnidad }}</label>
	        							@else
	        								@php
	        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;

	        									$a = $detalle->costoUnitario;
					        					$b = $detalle->margenUsa;
					        					$prom = $a * $b / 100;

					        					$precioUnidad = $a + $prom + $costoTotalFlete;	
	        								@endphp
	        								<label id="costoTotalFlete{{$detalle->id}}">{{$precioUnidad}}</label>
        								@endif
		        					@endif	
		        				@endforeach
	        				</td>
	        				<td class="bg-primary">
	        					@foreach($detallePeso as $detalleP)
		        					@if($detalle->sede_id == $detalleP->sede_id)
		        						@if($detalleP->PesoSede < 9)
		        							@php
			        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
			        							$costoTotalFlete = $promedio * $variables[1]->valor;

			        							$a = $detalle->costoUnitario;
					        					$b = $detalle->margenUsa;
					        					$prom = $a * $b / 100;

					        					$precioUnidad = $a + $prom + $totalFlete;
					        					$precioTotal = $precioUnidad * $detalle->cantidad;

		        							@endphp
		        							<label>{{ $precioTotal }}</label>
	        							@else
	        								@php
	        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;

	        									$a = $detalle->costoUnitario;
					        					$b = $detalle->margenUsa;
					        					$prom = $a * $b / 100;

					        					$precioUnidad = $a + $prom + $totalFlete;
					        					$precioTotal = $precioUnidad * $detalle->cantidad;	
	        								@endphp
	        								<label id="costoTotalFlete{{$detalle->id}}">{{$precioTotal}}</label>
        								@endif
		        					@endif	
		        				@endforeach
	        				</td>
		        			<td>
		        				@if($detalle->cantidad > 1)
		        					<button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#exampleModalCenter{{ $detalle->id }}">Dividir</button>	
				    			@endif	
				    			<div class="modal fade" id="exampleModalCenter{{ $detalle->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
								  	<div class="modal-dialog modal-dialog-centered" role="document">
									    <div class="modal-content">
									      <div class="modal-header">
									        <h5 class="modal-title" id="exampleModalCenterTitle">División del Item {{ $detalle->id }}</h5>
									        <h5>Cantidad del Item {{ $detalle->cantidad }}</h5>
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
									          <span aria-hidden="true">&times;</span>
									        </button>
									      </div>
									      <div class="modal-body">
									        <div class="form-group">
								    			<label>¿En cuantos Items, desea dividirlo?</label>
								    			<input type="number" name="itemDiv" id="itemDiv{{ $detalle->id }}" class="form-control" placeholder="Ingrese la cantidad"/>

								    		</div>
								    		
								    		<div class="form-group">
								    			<input class="btn btn-warning" type="button" value="Dividir Item" onclick="dividirItem('itemDiv{{ $detalle->id }}', '{{ $detalle->id }}')">
								    		</div>

								    		<table id="body_table{{ $detalle->id }}">
								    			
								    		</table>
									      </div>
									      <div class="modal-footer">
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
									        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="crearDivisiones()">Aceptar</button>
									      </div>
									    </div>
						  			</div>
								</div>		        				
		        			</td>
		        			
		        			<td><input type="hidden" name="detalle_id[]" value="{{$detalle->id}}"></td>
		        		</tr>
		        	@endforeach
		        	
		        </tbody>
		      	</table>
	      <!--Seccion solo para el Administrador-->
	      <!--Asignar Usuario para gestionar la orden-->
      	</div>
		<div class="form-group col-md-12">
    		<button type="submit" class="btn btn-primary col-md-offset-5">Actualizar la Orden</button>
    	</div>
    	</form>

    	<!--Actualizar la orden a cotizado y enviar los datos al cliente-->
    </div>
    <script type="text/javascript">
    	function calculalPesoLibras(id,e,cant)
    	{
    		var costoFlete = e.value*document.getElementById('valorPesoLibra'+id).value;
    		document.getElementById('costoFlete'+id).innerHTML = number_format(costoFlete, 2, ',','.');

    		var totalPesoLibra = cant*document.getElementById('pesoLb'+id).value;
    		document.getElementById('totalPesoLibra'+id).innerHTML = number_format(totalPesoLibra, 2, ',','.');

    		var costoTotalFlete = costoFlete * totalPesoLibra;
    		document.getElementById('costoTotalFlete'+id).innerHTML = number_format(costoTotalFlete, 2, ',','.');
    	}

    	function dividirItem(itemDiv, detalle_id)
    	{
    		console.log(itemDiv);
    		console.log(detalle_id);
    		console.log('cantidad'+detalle_id);
    		console.log(Number(document.getElementById(itemDiv).value));

    		if( Number(document.getElementById(itemDiv).value) > 1 && Number(document.getElementById(itemDiv).value) <= Number(document.getElementById('cantidad'+detalle_id).value) )
        	{
        		console.log(itemDiv);
	    		console.log(document.getElementById(itemDiv));
	    		console.log(document.getElementById(itemDiv).value);
		        var t = document.getElementById('body_table'+detalle_id);
		        //limpio lo que tenia en la tabla
		        t.innerHTML="";
		        
		        for(i = 0; i < document.getElementById(itemDiv).value; i++)
		        {
		        	//creo un tr
		            var tr = document.createElement('tr');  
		            //creo un td
		            var td = document.createElement('td');
		            //creo el input
		            var hd = document.createElement('input');
		            hd.setAttribute('type','text');
		            hd.setAttribute('name','itemDividido'+detalle_id+'[]');
		            td.appendChild(hd);
		            tr.appendChild(td);
		            t.appendChild(tr);
		        }
        	}
        	else
        	{
        		alert('El numero tiene que ser mayor a 1 y menor o igual a '+Number(document.getElementById('cantidad'+detalle_id).value));
        	}   		
    	}

    	function crearDivisiones()
    	{

    	}
    </script>
    
@stop

@section('totalSede')
	<div class="box box-warning col-md-6">
	    <!-- /.box-header -->
	    <div class="box-body table-responsive col-md-6 bg-warning">
	    	<input type="hidden" name="ordenId" value="{{$orden_id}}">
	    	<table class="table table-bordered table-striped table-hover">
	    		<thead>
    				<tr>
    					<td>Sede</td>
    					<td>Articulos por Sede</td>
    					<td>Peso Lb por sede</td>
    				</tr>
				</thead>
    			<tbody>
    				@foreach($detallePeso as $detalle)
    					<tr>
    						<td>{{ $detalle->nombre }}</td>
    						<td>{{ $detalle->cantidadProductos }}</td>
    						<td>{{ $detalle->PesoSede }}</td>
    					</tr>
    				@endforeach
    			</tbody>	
      		</table>
      	</div>
      	<form class="form col-md-4" method="POST" action="{{ route('ordenes.cotizarOrden',$orden_id) }}">
			{{ csrf_field() }}
			<div class="form-group">
				<button type="submit" class="btn btn-success">Enviar al Cliente</button>
			</div>
		</form>
  	</div>
@stop