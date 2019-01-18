@extends('admin.layout')

@section('contenido')
	<div class="box box-warning">
	    <div class="box-header col-md-12">
	      <h3 class="box-title">Detalles de la Orden - <b>{{$orden_id}}</b></h3>
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
		        		<th>Costo Parte Unidad</th>
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
		        				{{$detalle->pesoLb}}
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
			        							if($detalle->convencion_id == 1)
			        							{
			        								$precioMoneda = $totalFlete;
			        							}else {
			        								$precioMoneda = $totalFlete * $detalle->Trm;
			        							}
		        							@endphp
		        							<label>{{ $precioMoneda }}</label>
	        							@else
	        								@php
	        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;
	        									$totalFlete = $costoTotalFlete * $detalle->cantidad;
	        									if($detalle->convencion_id == 1)
			        							{
			        								$precioMoneda = $totalFlete;
			        							}else {
			        								$precioMoneda = $totalFlete * $detalle->Trm;
			        							}	
	        								@endphp
	        								<label id="costoTotalFlete{{$detalle->id}}">{{$precioMoneda}}</label>
        								@endif
		        					@endif	
		        				@endforeach
		        			</td>
		        			<td class="bg-success">
		        				@php
		        					$a = $detalle->costoUnitario;
		        					$b = $detalle->margenUsa;
		        					$prom = $a * $b / 100;

		        					$precioParteUnidad = $detalle->costoUnitario + $prom;
		        				@endphp
		        				<label>{{ $precioParteUnidad }}</label>
		        			</td>		        				
	        				<td class="bg-danger">

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
					        					//dd($precioUnidad);
					        					if($detalle->convencion_id == 1)
			        							{
			        								$precioMonedaUnidad = $precioUnidad;
			        							}else {
			        								$precioMonedaUnidad = $precioUnidad * $detalle->Trm;
			        							}

		        							@endphp
		        							<input type="hidden" name="pesoPromedio" value="{{ $promedio }}">
		        							<label>{{ $precioMonedaUnidad }}</label>
	        							@else
	        								@php
	        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;

	        									$a = $detalle->costoUnitario;
					        					$b = $detalle->margenUsa;
					        					$prom = $a * $b / 100;

					        					$precioUnidad = $a + $prom + $costoTotalFlete;	
					        					if($detalle->convencion_id == 1)
			        							{
			        								$precioMonedaUnidad = $precioUnidad;
			        							}else {
			        								$precioMonedaUnidad = $precioUnidad * $detalle->Trm;
			        							}
	        								@endphp
	        								<label id="costoTotalFlete{{$detalle->id}}">{{$precioMonedaUnidad}}</label>
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

					        					if($detalle->convencion_id == 1)
			        							{
			        								$precioMonedaTotal = $precioTotal;
			        							}else {
			        								$precioMonedaTotal = $precioTotal * $detalle->Trm;
			        							}

		        							@endphp
		        							<label>{{ $precioMonedaTotal }}</label>
	        							@else
	        								@php
	        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;

	        									$a = $detalle->costoUnitario;
					        					$b = $detalle->margenUsa;
					        					$prom = $a * $b / 100;

					        					$precioUnidad = $a + $prom + $totalFlete;
					        					$precioTotal = $precioUnidad * $detalle->cantidad;	
					        					if($detalle->convencion_id == 1)
			        							{
			        								$precioMonedaTotal = $precioTotal;
			        							}else {
			        								$precioMonedaTotal = $precioTotal * $detalle->Trm;
			        							}
	        								@endphp
	        								<label id="costoTotalFlete{{$detalle->id}}">{{$precioMonedaTotal}}</label>
        								@endif
		        					@endif	
		        				@endforeach
	        				</td>
		        			
		        			<td><input type="hidden" name="detalle_id[]" value="{{$detalle->id}}"></td>
		        		</tr>
		        	@endforeach
		        	
		        </tbody>
		      	</table>
	      <!--Seccion solo para el Administrador-->
	      <!--Asignar Usuario para gestionar la orden-->
      	</div>
		@if($detalle->estado_id == 4)
	      	<div class="form-group col-md-offset-3">
	    		<button type="submit" class="btn btn-primary col-md-3">Aceptar</button>
	    	</div>
    	@endif
    	</form>

    	<!--Actualizar la orden a cotizado y enviar los datos al cliente-->
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