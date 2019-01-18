@extends('admin.layout')

@section('contenido')
	<div class="box box-warning">
	    <div class="box-header col-md-12">
	      <h3 class="box-title">Detalles de la Orden - <b>{{$orden_id}}</b></h3>
	    </div>
	    <!-- /.box-header -->

	    <form class="form" method="POST" action="{{ route('ordenes.actualizarOrden', $orden_id) }}">
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
		        		<th>Dias Entrega Proveedor</th>
		        		<th>En Bodega</th>
		        		<th>Fecha recepción Bodega</th>
		        		<th>Dias Reales Entrega</th>
		        		<th>Guia Internacional</th>
		        		<th>Invoice</th>
		        		<th>Fecha Invoice</th>
		        		<th>Dias Prometidos</th>
		        		<th>Guia Interna destino</th>
		        		<th>Factura Cop</th>
		        		<th>Fecha real Entrega</th>
		        		<th>Fecha Factura</th>
		        		<th>Dias Planeador Vs Factura</th>
		        		
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
					        					if($detalle->convencion_id == 1)
			        							{
			        								$precioMonedaUnidad = $precioUnidad;
			        							}else {
			        								$precioMonedaUnidad = $precioUnidad * $detalle->Trm;
			        							}

		        							@endphp
		        							
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
	        				<td>
	        					@if($detalle->estadoItem_id == 4)
	        						<label>{{$detalle->diasEntregaProveedor}}</label>
        						@else
        							<input type="text" name="diasEntregaProveedor[]" value="{{$detalle->diasEntregaProveedor}}">
	        					@endif
	        					
	        				</td>
	        				<td>
	        					@if($detalle->estadoItem_id == 4)
	        						<label>{{$detalle->bodega}}</label>
        						@else
        							<input type="text" name="bodega[]" value="{{$detalle->bodega}}">
	        					@endif
	        					
	        				</td>
	        				<td>
	        					<input type="date" >
	        				</td>
	        				<td>
	        					<input type="date" >
	        				</td>
	        				<td>
	        					@if($detalle->estadoItem_id == 4)
	        						<label>{{$detalle->guiaInternacional}}</label>
        						@else
        							<input type="text" name="guiaInternacional[]" value="{{$detalle->guiaInternacional}}">
	        					@endif
	        					
	        				</td>
	        				<td>
	        					@if($detalle->estadoItem_id == 4)
	        						<label>{{$detalle->invoice}}</label>
        						@else
        							<input type="text" name="invoice[]" value="{{$detalle->invoice}}">
	        					@endif
	        					
	        				</td>
	        				<td>
	        					@if($detalle->estadoItem_id == 4)
	        						<label>{{$detalle->fechaInvoice}}</label>
        						@else
        							<input type="date" name="fechaInvoice[]" value="{{$detalle->fechaInvoice}}">
	        					@endif
	        					
	        				</td>
	        				<td>
	        					@if($detalle->estadoItem_id == 4)
	        						<label>{{$detalle->diasPrometidosCliente}}</label>
        						@else
        							<input type="text" name="diasPrometidosCliente[]" value="{{$detalle->diasPrometidosCliente}}">
	        					@endif
	        					
	        				</td>
	        				<td>
	        					@if($detalle->estadoItem_id == 4)
	        						<label>{{$detalle->guiaInternaDestino}}</label>
        						@else
        							<input type="text" name="guiaInternaDestino[]" value="{{$detalle->guiaInternaDestino}}">
	        					@endif
	        					
	        				</td>
	        				<td>
	        					@if($detalle->estadoItem_id == 4)
	        						<label>{{$detalle->facturaCop}}</label>
        						@else
        							<input type="text" name="facturaCop[]" value="{{$detalle->facturaCop}}">
	        					@endif
	        					
	        				</td>
	        				<td>
	        					@if($detalle->estadoItem_id == 4)
	        						<label>{{$detalle->fechaRealEntrega}}</label>
        						@else
        							<input type="date" name="fechaRealEntrega[]" value="{{$detalle->fechaRealEntrega}}">
	        					@endif
	        					
	        				</td>
	        				<td>
	        					<div class="form-group">

					                <div class="input-group date">
					                  <div class="input-group-addon">
					                    <i class="fa fa-calendar"></i>
					                  </div>
					                  <input type="text" name="fechaFactura[]" class="form-control pull-right" id="datepicker">
					                </div>
					                <!-- /.input group -->
					              </div>
	        				</td>
	        				<td>
	        					<input type="text" name="entregaVsFactura[]" >
	        				</td>
		        			<td>
		        				@if($detalle->estadoItem_id == 4)
		        					<i class="fa fa-check"></i>
		        				@else
		        					<button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#exampleModalCenter{{ $detalle->id }}"><i class="fa fa-book"></i></button>
			        				<div class="modal fade" id="exampleModalCenter{{ $detalle->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
									  <div class="modal-dialog modal-dialog-centered" role="document">
										    <div class="modal-content">
										    		<input type="hidden" name="ordenId" value="{{$orden_id}}">
									    			<input type="hidden" name="detalleId[]" value="{{ $detalle->id }}">
											      	<div class="modal-header">
												        <h5 class="modal-title" id="exampleModalLongTitle">Cambiar Estado del Item N° {{ $detalle->id }}</h5>
												        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
												          <span aria-hidden="true">&times;</span>
												        </button>
											     	 </div>
											      	<div class="modal-body">
											        	<p>Pulse el boton <b>Cambiar estado</b>, para poder facturar el Item N° {{ $detalle->id }}.</p>
											      	</div>
											      	<div class="modal-footer">
												        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
												        <button type="submit" class="btn btn-primary">Cambiar estado</button>
											      	</div>
										    </div>
									  </div>
									</div>
		        				@endif
		        				
								
		        			</td>
		        			<td><input type="hidden" name="detalle_id[]" value="{{$detalle->id}}"></td>
		        		</tr>
		        	@endforeach
		        	
		        </tbody>
		      	</table>
	      <!--Seccion solo para el Administrador-->
	      <!--Asignar Usuario para gestionar la orden-->
      	</div>
	      	<div class="form-group col-md-offset-3">
	    		<button type="submit" class="btn btn-primary col-md-3">Actualizar</button>
	    	</div>
    	
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

@push('styles')
	<link rel="stylesheet" href="{{asset('adminLte/plugins/datepicker/datepicker3.css')}}">
@endpush

@push('scripts')
	<script src="{{asset('adminLte/plugins/datepicker/bootstrap-datepicker.js')}}">
		//Date picker
	    $('#datepicker').datepicker({
	      autoclose: true
	    });
	</script>
@endpush






