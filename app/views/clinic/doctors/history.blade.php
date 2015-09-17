@extends('clinic.master')

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
						<form>
							<table class="table table-striped" id="table-example">
								<thead>
									<tr>
										<th class="text-center">day</th>
										<th class="text-center">start_date</th>
										<th class="text-center">end_date</th>
										<th class="text-center">reason</th>
										<th class="text-center">last_date_update</th>
										<th class="text-center">state</th>
									</tr>
								</thead>
								<tbody align="center">
								@if($Appointment) 																				
									@foreach($Appointment as $d)
									<tr class="odd gradeX">	
										<td>{{$d->day}}</td>																					
										<td>{{$d->start_date}}</td>
										<td>{{$d->end_date}}</td>
										<td>{{$d->reason}}</td>
										<td>{{$d->last_date_update}}</td>
										<td>{{$d->state}}</td>
									</tr>
									@endforeach
								@else
								<h3>No hay citas Pendientes</h3>
								@endif																				
								</tbody>
							</table>
						</form>
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