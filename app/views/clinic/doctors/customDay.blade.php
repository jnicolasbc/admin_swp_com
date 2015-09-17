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
					<a href="{{url('/clinic/doctor/custom-days/create')}}?agendaID={{$agendaID}}" class="btn btn-primary" style='margin-top: 10px; margin-left: 10px;'>
					<i class="fa fa-pencil-square-o"></i> Nuevo
					</a>
					<div class="panel-body">
					    <table class="table table-striped" id="table-example">
							<thead>
								<tr>
									<th class="text-center">Dia</th>
									<th class="text-center">Inicio Mañana</th>
									<th class="text-center">Fin Tarde</th>
									<th class="text-center">Inicio Almuerzo</th>
									<th class="text-center">Fin Almuerzo</th>
									<th class="text-center">Inicio Tarde</th>
                                    <th class="text-center">Fin Tarde</th>
                                    <th class="text-center">Dia no laboral </th>
                                    <th class="text-center">Editar/Eliminar</th>
								</tr>
							</thead>
							<tbody align="center"> 																				
								@foreach($customddays as $customdday)
                                <tr class="odd gradeX"> 
                                    <td>{{$customdday->day}}</td>                                         
                                    <td>{{$customdday->starttime_am}}</td>                                          
                                    <td>{{$customdday->endtime_am}}</td>  
                                    <td>{{$customdday->lunch_start}}</td>  
                                    <td>{{$customdday->lunch_end}}</td>  
                                    <td>{{$customdday->starttime_pm}}</td>  
                                    <td>{{$customdday->endtime_pm}}</td>  
                                    <td>@if($customdday->is_day_off==1)Feriado @else --- @endif</td>
							        <td>
							        	<a href="{{url('/clinic/doctor/custom-days/'.$customdday->id.'/edit')}}" type="button" class="btn btn-info btn-transparent"><i class="fa fa-pencil-square-o"></i></a>
							        	{{ Form::open(array('class'=>'pull-right','url' => 'clinic/doctor/custom-days/'.$customdday->id)) }}
                                            {{ Form::hidden("_method", "DELETE") }}
                                            <button class="btn btn-theme-inverse btn-transparent" type="submit">
                                                <i class="glyphicon glyphicon-remove"></i>
                                            </button>
                                        {{ Form::close() }}																																														
									</td>																					
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