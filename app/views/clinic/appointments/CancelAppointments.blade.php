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
	    			<h3>Citas Canceladas</h3>
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
									<th class="text-center">Paciante</th>
									<th class="text-center">Razón</th>
									<th class="text-center">Dia de la cita</th>
									<th class="text-center">Inicio De la Cita</th>
									<th class="text-center">Fin De La Cita</th>
									<th class="text-center">estado</th>
								</tr>
							</thead>
							<tbody align="center"> 																				
								@foreach($citas as $cita)
                                <tr class="odd gradeX"> 
                                    <td>{{$cita->first_name.' '.$cita->last_name}}</td>                                         
                                    <td>{{$cita->reason}}</td>
                                    <td>{{$cita->day}}</td>                                            
                                    <td>{{CustomDay::time($cita->start_date)}}</td>  
                                    <td>{{CustomDay::time($cita->end_date)}}</td>
                                    <td>{{$cita->state}}</td>  																				
								</tr>
								@endforeach																				
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