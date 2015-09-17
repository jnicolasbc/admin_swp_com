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
							<table class="table table-striped" id="table-example">
								<thead>
									<tr>
										<th class="text-center">Dia</th>
										<th class="text-center">Inicio</th>
										<th class="text-center">Fin</th>
										<th class="text-center">Motivo</th>
										<th class="text-center">Fecha estimada</th>
										<th class="text-center">Cancelar</th>
									</tr>
								</thead>
								<tbody align="center">
								@if($Appointment) 																				
									@foreach($Appointment as $cita)
									@if($cita->state=='pending')
									<tr class="odd gradeX">	
										<td>{{$cita->day}}</td>																					
										<td>{{$cita->start_date}}</td>
										<td>{{$cita->end_date}}</td>
										<td>{{$cita->reason}}</td>
										<td>{{$cita->last_date_update}}</td>
                                        <td>
							        	<a href="{{url('clinic/confirmation-appointment')}}?cita_id={{$cita->id}}" type="button" class="btn btn-info btn-transparent"><i class="fa fa-pencil-square-o"></i> Confirmar cita</a>																																												
									    <button class="btn btn-info btn-transparent" onclick="var notice = new PNotify({
					                        text: $('#form_notice_{{$cita->id}}').html(),
					                        icon: false,
					                        width: 'auto',
					                        hide: false,
					                        addclass: 'custom',
					                        icon: 'picon picon-32 picon-dialog-cancel',
                                            opacity: .8,
                                            animation: {
                                                effect_in: 'show',
                                                effect_out: 'slide'
                                            },
                                            nonblock: {
                                                nonblock: true
                                            },
					                        buttons: {
					                        	closer: false,
					                        	sticker: false
					                        },
					                        insert_brs: false
				                            });
				                            notice.get().find('form.pf-form').on('click', '[name=cancel]', function(){
				                            	notice.remove();
				                            }).submit(function(){
                                             $('#form_notice').submit();
                                            });"><i class="fa fa-pencil-square-o"></i> No Aceptar</button>
				                            <div id="form_notice_{{$cita->id}}" style="display: none;">
		                                        {{Form::open(array('class' => 'pf-form pform_custom', 'method'=>'get','action' => 'ClinicController@getCancelAppointment', 'files' => 'true'))}}
				                            		{{Form::hidden("cita_id", $cita->id)}}
				                            		<div class="pf-element pf-heading">
				                            			<h3>Confirme que No aceptara esta cita!</h3>
				                            			<p></p>
				                            		</div>
				                            		<div class="pf-element pf-buttons pf-centered">
				                            			<input class="pf-button btn btn-primary" type="submit" name="submit" value="Confirmar" />
				                            			<input class="pf-button btn btn-default" type="button" name="cancel" value="Cancelar" />
				                            		</div>
				                            	{{ Form::close() }}
				                            </div>
									</td>
									</tr>
									@endif
									@endforeach
								@else
								<h3>No hay citas Pendientes</h3>
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