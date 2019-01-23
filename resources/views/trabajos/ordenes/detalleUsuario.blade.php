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
		        		<th>Costo Unidad</th>
		        		@if(auth()->user()->rol_id == 4)
		        			<th>Valor Sugerido</th>
		        		@endif
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
        				$precioTotalGlobal = 0;
        				$cantidadTotalGlobal = 0;
        				$pesoTotalGlobal = 0;
        				$costoTotalFleteGlobal = 0;

        				$totalFleteUnidad = 0;
        				$totalPrecioUnitario = 0;
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
		        				@php
		        					$cantidadTotalGlobal = $cantidadTotalGlobal + $detalle->cantidad;
		        				@endphp
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
		        							$pesoTotalGlobal = $pesoTotalGlobal + $pesoTotal;
		        							@endphp
		        							<label>{{ $pesoTotal }}</label>
	        							@else
	        								@php
	        									$pesoTotal = $detalle->pesoLb * (int)$detalle->cantidad;
	        									$pesoTotalGlobal = $pesoTotalGlobal + $pesoTotal;
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
			        								$costoTotalFleteGlobal = $costoTotalFleteGlobal + $totalFlete;
			        								$precioMoneda = $totalFlete;

			        							}else {
			        								
			        								$precioMoneda = $totalFlete * $detalle->Trm;
			        								$costoTotalFleteGlobal = $costoTotalFleteGlobal + $precioMoneda;
			        							}
		        							@endphp
		        							<label>{{ $precioMoneda }}</label>
	        							@else
	        								@php
	        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;
	        									$totalFlete = $costoTotalFlete * $detalle->cantidad;
	        									if($detalle->convencion_id == 1)
			        							{	
			        								$costoTotalFleteGlobal = $costoTotalFleteGlobal + $totalFlete;
			        								$precioMoneda = $totalFlete;
			        							}else {
			        								
			        								$precioMoneda = $totalFlete * $detalle->Trm;
			        								$costoTotalFleteGlobal = $costoTotalFleteGlobal + $precioMoneda;
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
		        			@if(auth()->user()->rol_id == 4)
		        				<td>
			        				<input type="text" name="valorSugerido">
			        			</td>
		        			@endif
		        				        				
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
			        								$totalPrecioUnitario = $totalPrecioUnitario + $precioMonedaUnidad;
			        								$totalPrecioUnitarioM = number_format($totalPrecioUnitario, 2, ",", ".");
			        							}else {
			        								$precioMonedaUnidad = $precioUnidad * $detalle->Trm;
			        								$totalPrecioUnitario = $totalPrecioUnitario + $precioMonedaUnidad;
			        								$totalPrecioUnitarioM = number_format($totalPrecioUnitario, 2, ",", ".");
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
			        								$totalPrecioUnitario = $totalPrecioUnitario + $precioMonedaUnidad;
			        								$totalPrecioUnitarioM = number_format($totalPrecioUnitario, 2, ",", ".");
			        							}else {
			        								$precioMonedaUnidad = $precioUnidad * $detalle->Trm;
			        								$totalPrecioUnitario = $totalPrecioUnitario + $precioMonedaUnidad;
			        								$totalPrecioUnitarioM = number_format($totalPrecioUnitario, 2, ",", ".");
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
			        								$precioTotalGlobal = $precioTotalGlobal + $precioTotal;
			        								
			        								$precioMonedaTotal = $precioTotal;
			        								$precioMonedaTotal = number_format($precioMonedaTotal, 2, ",", ".");
			        							}else {
			        								$precioMonedaTotal = $precioTotal * $detalle->Trm;
			        								$precioTotalGlobal = $precioTotalGlobal + $precioMonedaTotal;
			        								$precioMonedaTotal = number_format($precioMonedaTotal, 2, ",", ".");
			        								
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
			        								$precioTotalGlobal = $precioTotalGlobal + $precioTotal;
			        								
			        								$precioMonedaTotal = $precioTotal;
			        								$precioMonedaTotal = number_format($precioMonedaTotal, 2, ",", ".");
			        							}else 
			        							{
			        								$precioMonedaTotal = $precioTotal * $detalle->Trm;
			        								
			        								$precioTotalGlobal = $precioTotalGlobal + $precioMonedaTotal;
			        								$precioMonedaTotal = number_format($precioMonedaTotal, 2, ",", ".");
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
		        	<tr>
	        			<td><label class="text-danger">Totales</label></td>
		        		<td></td>
		        		<td></td>
		        		<td></td>
		        		<td></td>
		        		<td></td>
		        		<td><label class="text-primary">{{ $cantidadTotalGlobal }}</label></td>
		        		<td></td>
		        		<td></td>
		        		<td><label class="text-primary">{{ $pesoTotalGlobal }}</label></td>
		        		<td><label class="text-primary"></label></td>
		        		<td></td>
		        		<td>
		        			@php
		        				$totalCostoTotalFlete = $costoTotalFleteGlobal;
		      					$totalCostoTotalFlete = number_format($totalCostoTotalFlete, 2, ",", ".");
		      				@endphp
		      				<label class="text-primary"> {{ $totalCostoTotalFlete }}</label>
		        		</td>
		        		<td></td>
		        		@if(auth()->user()->rol_id == 4)
		        			<td></td>
		        		@endif
		        		<td><label class="text-primary"> {{$totalPrecioUnitarioM}} </label></td>
		        		
		        		<td>
		        			@php
		        				$totalPrecioTotal = $precioTotalGlobal;
		      					$totalPrecioTotal = number_format($totalPrecioTotal, 2, ",", ".");
		      				@endphp
		      				<label class="text-primary">{{ $totalPrecioTotal }}</label>
		        		</td>
	        		</tr>
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
	<div class="box col-md-6">
	    <!-- /.box-header -->
	    <div class="box-body table-responsive col-md-4 col-md-offset-4 bg-success">
	    	<input type="hidden" name="ordenId" value="{{$orden_id}}">
	    	<table class="table table-bordered table-striped table-hover">
	    		<thead>
    				<tr class="text-primary">
    					<td><label>Sede</label></td>
    					<td><label>Articulos por Sede</label></td>
    					<td><label>Peso Lb por sede</label></td>
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
      		<table class="table table-bordered table-striped table-hover">
      			<tr>
      				<td><label class="text-primary">Cantidad total</label></td>
      				<td><label class="text-primary">{{ $cantidadTotalGlobal }} Productos </label></td>
      			</tr>
      			<tr>
      				<td><label class="text-primary">Peso total en libras</label></td>
      				<td><label class="text-primary">{{ $pesoTotalGlobal }} Lbs </label></td>
      			</tr>
      			<tr>
      				<td><label class="text-success">Costo total del Flete</label></td>
      				<td><label class="text-success">
      					@php
	      					$costoTotalFleteGlobal = number_format($costoTotalFleteGlobal, 2, ",", ".");
	      				@endphp
      					<strong class="text-danger">$</strong>  {{ $costoTotalFleteGlobal }}</label></td>
      			</tr>
      			<tr>
      				<td><label class="text-success">Total precio Usd</label></td>
      				@php
      					$precioTotalGlobal = number_format($precioTotalGlobal, 2, ",", ".");
      				@endphp
      				<td><label class="text-success"><strong class="text-danger">$</strong>  {{ $precioTotalGlobal }}</label></td>
      			</tr>
      		</table>
      	</div>
  	</div>
@stop