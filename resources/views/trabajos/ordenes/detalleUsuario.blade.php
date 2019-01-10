@extends('admin.layout')

@section('contenido')
	<div class="box box-warning">
	    <div class="box-header col-md-12">
	      <h3 class="box-title">Detalles de la Orden - <b></b></h3>
	    </div>
	    <!-- /.box-header -->

	    <form class="form" method="POST" action="{{ route('ordenes.cotizarOrden', $orden_id) }}">
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
			        		<tr>
			        			<td>
			        				{{ $item }}
			        			</td>
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
			        				<label >{{$detalle->pesoLb}}</label>
			        			</td>
			        			@php
			        				$costoFleteUnidad = $variables[1]->valor * $detalle->pesoLb;
			        				$totalPeso = $detalle->cantidad * $detalle->pesoLb;
			        				$costoTotalFlete = $costoFleteUnidad * $totalPeso;
			        			@endphp	
			        			<td class="bg-success">
			        				<label id="totalPesoLibra{{$detalle->id}}">{{$totalPeso}}</label>
			        			</td>
			        			<td>
			        				@foreach($detallePeso as $detalleP)
			        					@if($detalle->sede_id == $detalleP->sede_id)
			        						@if($detalleP->PesoSede < 9)
			        							@php
			        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
			        							@endphp
			        							<label>{{ $promedio }}</label>
		        							@else
		        								<label>{{ $detalle->pesoLb }}</label>
	        								@endif
			        					@endif	
			        				@endforeach
			        			</td>			        			        			
			        			<td class="bg-danger">
			        				<label id="costoFlete{{$detalle->id}}">$US {{$costoFleteUnidad}}</label>
			        			</td>
			        			<td class="bg-danger">
			        				<label id="costoTotalFlete{{$detalle->id}}">$US {{$costoTotalFlete}}</label>
			        			</td>
		        				
			        				@php
			        					$a = $detalle->costoUnitario;
			        					$b = $detalle->margenUsa;
			        					$prom = $a * $b / 100;

			        					$c = $costoTotalFlete;
			        					$d = $detalle->cantidad;

			        					$e = $c / $d;

			        					$precioVenta = $a+$prom+$e;
			        				@endphp
		        				<td>
		        					<label>$US {{ $precioVenta }}</label>
		        				</td>
		        				<td></td>
			        			
			        			<td><input type="hidden" name="detalle_id[]" value="{{$detalle->id}}"></td>
			        		</tr>
			        	@endforeach
		        	
		        </tbody>
		      	</table>
	      <!--Seccion solo para el Administrador-->
	      <!--Asignar Usuario para gestionar la orden-->
	      	<hr>
      	</div>
      	@if($detalle->estado_id == 4)
      	<div class="form-group col-md-offset-3">
    		<button type="submit" class="btn btn-primary col-md-3">Aceptar</button>
    	</div>
    	@endif
    	</form>
    </div>
    
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
  	</div>
@stop