@extends('clinic.doctor.master')

@section('title', 'Doctor Dashboard')

@section('content')
	<ol class="breadcrumb">
			<li><a href="{{url('/clinic')}}">Home</a></li>
			<li class="active">{{trans('doctor.see doctors')}}</li>
	</ol>
	<!-- //breadcrumb-->
	<div id="content">
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">								 
					
				</section>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-body">
							<table class="table table-striped" id="table-example">
								<thead>
									<tr>
										<th class="text-center">Tipo de plan</th>
										<th class="text-center">Fecha de Pago</th>
										<th class="text-center">Tiempo</th>
										<th class="text-center">Total</th>
									</tr>
								</thead>
								<tbody align="center">
								@if($payments) 																				
									@foreach($payments as $d)
									<tr class="odd gradeX">																					
										<td>{{trans('main.'.$d->plan)}}</td>
										<td>{{$d->created_at}}</td>
										<td>{{$d->time}} Días</td>
										<td>{{$d->money}}</td>
									</tr>
									@endforeach
								@else
								<h3>No hay Pagos Realizados</h3>
								@endif																				
								</tbody>
							</table>
					</div>
				</section>
			</div>
		</div>
			<!-- //content > row-->
	</div>
	<!-- //content-->
@stop
@section('js-script')
	function fnShowHide( iCol , table){
	    var oTable = $(table).dataTable(); 
	    var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	    oTable.fnSetColumnVis( iCol, bVis ? false : true );
	}

	$(function() {
		//////////     DATA TABLE  COLUMN TOGGLE    //////////
		$('[data-table="table-toggle-column"]').each(function(i) {
				var data=$(this).data(), 
				table=$(this).data("table-target"), 
				dropdown=$(this).parent().find(".dropdown-menu"),
				col=new Array;
				$(table).find("thead th").each(function(i) {
				 		$("<li><a  class='toggle-column' href='javascript:void(0)' onclick=fnShowHide("+i+",'"+table+"') ><i class='fa fa-check'></i> "+$(this).text()+"</a></li>").appendTo(dropdown);
				});
		});

		//////////     COLUMN  TOGGLE     //////////
		 $("a.toggle-column").on('click',function(){
				$(this).toggleClass( "toggle-column-hide" );  				
				$(this).find('.fa').toggleClass( "fa-times" );  			
		});

		// Call dataTable in this page only
		$('#table-example').dataTable();
		$('table[data-provide="data-table"]').dataTable();
	});
@stop