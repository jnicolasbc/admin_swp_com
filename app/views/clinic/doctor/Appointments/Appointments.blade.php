@extends('clinic.doctor.master')

@section('title', 'Doctor Dashboard')

@section('content')

<?php 
use \Api\V1\Helpers as H;
use Carbon\Carbon; ?>
	<ol class="breadcrumb">
		<li><a href="{{url('/clinic')}}">Home</a></li>
		<li class="active">{{trans('doctor.see doctors')}}</li>
	</ol>
	<!-- //breadcrumb-->
	<div id="content" style="height: 100%;">
		<div class="row" style="height: 100%;">
			<div class="col-lg-12" style="height: 100%;">

                <!--
		            /////////////////////////////////////////////////////////////////////////
		            //////////     MAIN SHOW CONTENT     //////////
		            //////////////////////////////////////////////////////////////////////
		            -->
		            <div id="main" style="height: 100%;">
		            	<div class="tabbable">
		            		<ul class="nav nav-tabs" data-provide="tabdrop">
		            			<li><a href="#" class="change" data-change="prev"><i class="fa fa-chevron-left"></i></a></li>
		            			<li><a href="#" class="change" data-change="next"><i class="fa fa-chevron-right"></i></a></li>
		            		    <li ><a href="#" data-view="month" data-toggle="tab" class="change-view">Month</a></li>
		            			<li class="active"><a href="#" data-view="agendaWeek" data-toggle="tab" class="change-view">Week</a></li>
		            			<li><a href="#" data-view="agendaDay" data-toggle="tab" class="change-view">Day</a></li>
		            		</ul>
		            		<div class="tab-content">
		            			<div class="row">
		            				<div class="col-lg-10" >
		            					<div id="calendar"></div>
		            				</div>
		            				<!-- //content > row > col-lg-8 -->
		            			</div>
		            			<!-- //content > row-->
		            		</div>
		            		<!-- //tab-content -->
		            	</div>
		            	<!-- //tabbable -->
		            </div>
		            <!-- //main-->
            
		            <!--
		            ///////////////////////////////////////////////////////////////////
		            //////////     MODAL EDITAR EVENTO     //////////
		            ///////////////////////////////////////////////////////////////
		            -->
		            <div id="md-edit-event" class="modal fade md-slideUp" tabindex="-1" data-width="450"  data-header-color="inverse">
		            		<div class="modal-header">
		            				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
		            				<h4 class="modal-title"><i class="fa fa-plus"></i> Editar Cita</h4>
		            		</div>
		            		<!-- //modal-header-->
		            		<div class="modal-body" style="padding-bottom:0">
		            			{{Form::open(array('method' =>'get','action' => 'ClinicDoctorsController@getEditAppointments', 'files' => 'true'))}}
		            				{{ Form::hidden("cita_id", NULL, ['class'=>'cita_id']) }}
		            				{{ Form::hidden("state", 'delayed') }}
		            				<label class="control-label">Seleccione El Tiempo de Atrazo</label>
		            				<div class="form-group">
                                        {{ Form::select('time', array('' => 'Seleccione una opcion','5' => '5 minutos','10' => '10 minutos','15' => '15 minutos','20' => '20 minutos','25' => '25 minutos','30' => '30 minutos','45' => '45 minutos','60' => '1 Hora','90' => '1 hora y 30 minutos', '120' => '2 Horas'), '', ['id'=>'type', 'class' => 'form-control', 'required'=>'required'])}}
		            				</div>
		            				<div class="form-group">
                                        <button type="submit" class="btn btn-theme">Confirmar Atrazo</button>
		            				</div>
		            			{{ Form::close() }}
		            		</div>
		            		<!-- //modal-body-->
		            </div>
		            <!-- //modal-->

		            <!--
		            ///////////////////////////////////////////////////////////////////
		            //////////     MODAL EDITAR EVENTO     //////////
		            ///////////////////////////////////////////////////////////////
		            -->
		            <div id="md-pending-event" class="modal fade md-slideUp" tabindex="-1" data-width="450"  data-header-color="inverse">
		            		<div class="modal-header">
		            				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
		            				<h4 class="modal-title"><i class="fa fa-plus"></i> Editar Cita</h4>
		            		</div>
		            		<!-- //modal-header-->
		            		<div class="modal-body" style="padding-bottom:0">
		            			<div class="form-group">
                                {{Form::open(array('method' =>'get','action' => 'ClinicDoctorsController@getEditAppointments', 'files' => 'true'))}}
		            				{{ Form::hidden("cita_id", NULL, ['class'=>'cita_id']) }}
		            				{{ Form::hidden("state", 'confirmed') }}
		            				<button type="submit" class="btn btn-theme">Confirmar cita</button>
		            			{{ Form::close() }}
		            			{{Form::open(array('method' =>'get','action' => 'ClinicDoctorsController@getEditAppointments', 'files' => 'true'))}}
		            				{{ Form::hidden("cita_id", NULL, ['class'=>'cita_id']) }}
		            				{{ Form::hidden("state", 'not-accepted') }}
		            				<button type="submit" class="btn" >No aceptar</button>
		            			{{ Form::close() }}
		            			</div>
		            			
		            		</div>
		            		<!-- //modal-body-->
		            </div>
		            <!-- //modal-->	

		            <!--
		            ///////////////////////////////////////////////////////////////////
		            //////////     MODAL EDITAR EVENTO     //////////
		            ///////////////////////////////////////////////////////////////
		            -->
		            <div id="md-confir-event" class="modal fade md-slideUp" tabindex="-1" data-width="450"  data-header-color="inverse">
		            		<div class="modal-header">
		            				<button type="button" class="close" ><i class="fa fa-times"></i></button>
		            				<h4 class="modal-title"><i class="fa fa-plus"></i> Editar Cita</h4>
		            		</div>
		            		<!-- //modal-header-->
		            		<div class="modal-body" style="padding-bottom:0">
		            			<div class="form-group">
		            			{{Form::open(array('method' =>'get','action' => 'ClinicDoctorsController@getEditAppointments', 'files' => 'true'))}}
		            				{{ Form::hidden("cita_id", NULL, ['class'=>'cita_id']) }}
		            				{{ Form::hidden("state", 'in-progress') }}
		            				<button type="submit" class="btn btn-theme">En progreso</button>
		            			{{ Form::close() }}
		            			{{Form::open(array('method' =>'get','action' => 'ClinicDoctorsController@getEditAppointments', 'files' => 'true'))}}
		            				{{ Form::hidden("cita_id", NULL, ['class'=>'cita_id']) }}
		            				{{ Form::hidden("state", 'served') }}
		            					<button type="submit" class="btn btn-theme">Atender</button>
		            			{{ Form::close() }}
		            			<button type="submit" class="btn btn-theme" onClick="$('#md-edit-event').modal();">Atrazar</button>
		            			{{Form::open(array('method' =>'get','action' => 'ClinicDoctorsController@getEditAppointments', 'files' => 'true'))}}
		            				{{ Form::hidden("cita_id", NULL, ['class'=>'cita_id']) }}
		            				{{ Form::hidden("state", 'canceled') }}
		            					<button type="submit" class="btn" >Borrar o cancelar</button>
		            			{{ Form::close() }}
		            			</div>
		            		</div>
		            		<!-- //modal-body-->
		            </div>
		            <!-- //modal-->

		            <!--
		            ///////////////////////////////////////////////////////////////////
		            //////////     MODAL EDITAR EVENTO     //////////
		            ///////////////////////////////////////////////////////////////
		            -->
		            <div id="md-prog-event" class="modal fade md-slideUp" tabindex="-1" data-width="450"  data-header-color="inverse">
		            		<div class="modal-header">
		            				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
		            				<h4 class="modal-title"><i class="fa fa-plus"></i> Editar Cita</h4>
		            		</div>
		            		<!-- //modal-header-->
		            		<div class="modal-body" style="padding-bottom:0">
		            			<div class="form-group">
		            			{{Form::open(array('method' =>'get','action' => 'ClinicDoctorsController@getEditAppointments', 'files' => 'true'))}}
		            				{{ Form::hidden("cita_id", NULL, ['class'=>'cita_id']) }}
		            				{{ Form::hidden("state", 'served') }}
		            					<button type="submit" class="btn btn-theme">Atendida</button>
		            			{{ Form::close() }}
		            			</div>
		            		</div>
		            		<!-- //modal-body-->
		            </div>
		            <!-- //modal-->	

		            <!--
		            ///////////////////////////////////////////////////////////////////
		            //////////     MODAL EDITAR EVENTO     //////////
		            ///////////////////////////////////////////////////////////////
		            -->
		            <div id="md-atra-event" class="modal fade md-slideUp" tabindex="-1" data-width="450"  data-header-color="inverse">
		            		<div class="modal-header">
		            				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
		            				<h4 class="modal-title"><i class="fa fa-plus"></i> Editar Cita</h4>
		            		</div>
		            		<!-- //modal-header-->
		            		<div class="modal-body" style="padding-bottom:0">
		            			<div class="form-group">
		            			{{Form::open(array('method' =>'get','action' => 'ClinicDoctorsController@getEditAppointments', 'files' => 'true'))}}
		            				{{ Form::hidden("cita_id", NULL, ['class'=>'cita_id']) }}
		            				{{ Form::hidden("state", 'in-progress') }}
		            					<button type="submit" class="btn btn-theme">En progreso</button>
		            			{{ Form::close() }}
		            			{{Form::open(array('method' =>'get','action' => 'ClinicDoctorsController@getEditAppointments', 'files' => 'true'))}}
		            				{{ Form::hidden("cita_id", NULL, ['class'=>'cita_id']) }}
		            				{{ Form::hidden("state", 'served') }}
		            					<button type="submit" class="btn btn-theme">Atendida</button>
		            			{{ Form::close() }}
		            			{{Form::open(array('method' =>'get','action' => 'ClinicDoctorsController@getEditAppointments', 'files' => 'true'))}}
		            				{{ Form::hidden("cita_id", NULL, ['class'=>'cita_id']) }}
		            				{{ Form::hidden("state", 'canceled') }}
		            					<button type="submit" class="btn" >Borrar o cancelar</button>
		            			{{ Form::close() }}
		            			</div>
		            		</div>
		            		<!-- //modal-body-->
		            </div>
		            <!-- //modal-->								
		            						
            
		            <!--
		            ///////////////////////////////////////////////////////////////////
		            //////////     MODAL MESSAGES     //////////
		            ///////////////////////////////////////////////////////////////
		            -->
		            <div id="md-add-event" class="modal fade md-slideUp" tabindex="-1" data-width="450"  data-header-color="inverse">
		            	<div class="modal-header">
		            			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
		            			<h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Cita</h4>
		            	</div>
		            	<!-- //modal-header-->
		            	<div class="modal-body" style="padding-bottom:0">
		            		<div class="errors_form"></div>
		 	                {{Form::open(array('class' => 'register_ajax cmxform form-horizontal adminex-form', 'action' => 'ClinicDoctorsController@postaddAppointments', 'files' => 'true'))}}
		            			{{ Form::hidden("agenda_id", $agenda->id) }}
		            			<label class="control-label">Username o email del paciente</label>
		            			<div class="form-group">
		            				{{ Form::text('patient', NULL,['id'=>'patient', 'class' => 'form-control'])}}
		            			</div>

		            			<label class="control-label">Motivo o razón de la cita</label>
		            			<div class="form-group">
		            				{{ Form::text('reason', NULL,['id'=>'patient', 'class' => 'form-control'])}}
		            			</div>
                              
                                    {{ Form::hidden('fecha', NULL,['id'=>'day', 'class' => 'form-control'])}}
                                    {{ Form::hidden('start', NULL,['id'=>'startt', 'class' => 'form-control'])}}
                                    {{ Form::hidden('end', NULL,['id'=>'endd', 'class' => 'form-control'])}}

		            			<div class="form-group">
		            					<hr>
		            					<button type="submit" class="btn btn-theme">	Enviar</button>
		            					<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
		            			</div>
		            					
		            		{{ Form::close() }}
		            	</div>
		            	<!-- //modal-body-->
		            </div>
		            <!-- //modal-->


                </div>
            </div>
			<!-- //content > row-->
		</div>
		<!-- //content-->
@stop
@section('js-script')
	$(document).ready(function() {	

	//petición al enviar el formulario de registro de citas
	    var form = $('.register_ajax');
	    form.bind('submit',function () {
	        $.ajax({
	            type: form.attr('method'),
	            url: form.attr('action'),
	            data: form.serialize(),
	            success: function (data) {
	            	if(data.success == false){
		            	var errores = '';
		            	for(datos in data.errors){
		            		errores += '<small class="error">' + data.errors[datos] + '</small> <br>';
		            	}
                        errores += '<small class="error">' + data.mensaje + '</small> <br>';
		            	$('.errors_form').html(errores)
		            }else{
		                new PNotify({
                            title: "<p>Estado</p>",
                            text: "<p>Agrego esta cita con exito</p>",
                            addclass: 'custom',
                            icon: 'picon picon-32 picon-mail-mark-notjunk',
                            opacity: .8,
                            nonblock: {
                              nonblock: true
                            },
                            before_close: function(PNotify){
                              // You can access the notice's options with this. It is read only.
                              //PNotify.options.text;
                                 
                              // You can change the notice's options after the timer like this:
                              PNotify.update({
                                title: PNotify.options.title+" - Enjoy your Stay",
                                before_close: null
                              });
                              PNotify.queueRemove();
                              return false;
                            }
                        });
                        $(form)[0].reset();
                        $('#md-add-event').modal('hide');
                        $('.error').val('');
		            }
	            }
	        });
	          return false;
	    });

        $('#datetimepicker2').datetimepicker({
                    locale: '{{H::lang()}}',
                    format: 'D/M/YYYY'
                });
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();

        $('.timepicker').timepicki({
            start_time: ["08", "00", "AM"],
       		show_meridian:false,
       		min_hour_value:0,
       		max_hour_value:23,
       		step_size_minutes:15,
       		overflow_minutes:true,
       		increase_direction:'up',
       		disable_keyboard_mobile: true
       	});

		$("#addEvent").submit(function(event){
			event.preventDefault();
			if($("#event_title").val()){
				var events=$('<span class="external-event  label" data-color="'+$("#color_select").val()+'">'+$("#event_title").val() +'</span>');
				events.css({'background-color': $("#color_select").val() || "#CCC" , 'margin-right': "3px"});
				$("#external-events").append(events);
				$("#external-events span.external-event").draggable({
					zIndex: 999,
					revert: true,     
					revertDuration: 0 ,
					drag: function() { $("#slide-trash").addClass("active") },
					stop: function() { $("#slide-trash").removeClass("active") }
				});
				$(this)[0].reset();
				$('#md-add-event').modal('hide');
			}else{
				$.notific8('Please enter <strong>event  title</strong> ',{ life:5000, theme:"danger" ,heading:"ERROR :);" });
				$("#event_title").focus();
			}
		});

		$('#external-events span.external-event').draggable({
				zIndex: 999,
				revert: true,     
				revertDuration: 0 ,
				drag: function() { $("#slide-trash").addClass("active") },
				stop: function() { $("#slide-trash").removeClass("active") }
		});
		
	  $( "#slide-trash" ).droppable({
		  accept: "#external-events span.external-event , .fc-event",
		  hoverClass: "active-hover",
		  drop: function( event, ui ) {
			  ui.draggable.remove();
			  $(this).removeClass( "active" );
		  }
	  });

		var isElemOverDiv = function(draggedItem, dropArea) {
			// Prep coords for our two elements
			var a = $(draggedItem).offset;	
			a.right = $(draggedItem).outerWidth + a.left;
			a.bottom = $(draggedItem).outerHeight + a.top;
			
			var b = $(dropArea).offset;
			a.right = $(dropArea).outerWidth + b.left;
			a.bottom = $(dropArea).outerHeight + b.top;
		
			// Compare
			if (a.left >= b.left
				&& a.top >= b.top
				&& a.right <= b.right
				&& a.bottom <= b.bottom) { return true; }
			return false;
		}

		$('#calendar').fullCalendar({
			eventClick: function(calEvent, jsEvent, view) {
			    if(calEvent.color=='#FFFF00'){
                    $('.cita_id').val(calEvent.id);
			        $('#md-prog-event').modal();
		        }else if(calEvent.color=='#FF9900'){
                    $('.cita_id').val(calEvent.id);
			        $('#md-atra-event').modal();
		        }else if(calEvent.color=='#00D900'){
                    $('.cita_id').val(calEvent.id);
			        $('#md-confir-event').modal();
		        }else if(calEvent.color=='#E40B0B'){
                    $('.cita_id').val(calEvent.id);
			        $('#md-pending-event').modal();
		        }else if(calEvent.color=='#CCCCCC'){
		            $('.cita_id').val(calEvent.id);
                    $('#startt').val(calEvent.startt);
                    $('#endd').val(calEvent.endd);
                    $('#day').val(calEvent.day);
			        $('#md-add-event').modal();
		        }
			}, 
			disableResizing: true,
			eventDrop: function(calEvent, jsEvent, view) {			
       var startd = $.fullCalendar.formatDate(calEvent.start, "yyyy-MM-dd HH:mm:ss");
       var endd = $.fullCalendar.formatDate(calEvent.end, "yyyy-MM-dd HH:mm:ss");

       $.ajax({
       url:  "{{url('/clinic/doctor/appointment/editar-ajax')}}",
       data: { start: startd, end: endd, id: calEvent.id },
       type: "get",
       success: function(json) {
        if(json.success==true){
          new PNotify({
               title: "<p>Estado</p>",
               text: "<p>Cambiado con exito</p>",
               addclass: 'custom',
               icon: 'picon picon-32 picon-mail-mark-notjunk',
               opacity: .8,
               nonblock: {
                 nonblock: true
               },
               before_close: function(PNotify){
                 // You can access the notice's options with this. It is read only.
                 //PNotify.options.text;
                    
                 // You can change the notice's options after the timer like this:
                 PNotify.update({
                   title: PNotify.options.title+" - Enjoy your Stay",
                   before_close: null
                 });
                 PNotify.queueRemove();
                 return false;
               }
           });
        }else{
         new PNotify({
             title: "<p>Error al cambiar estado</p>",
             text: "<p></p>",
             addclass: 'custom',
             icon: 'picon picon-32 picon-dialog-cancel',
             opacity: .8,
             nonblock: {
               nonblock: true
             },
             before_close: function(PNotify){
               // You can access the notice's options with this. It is read only.
               //PNotify.options.text;
                    
               // You can change the notice's options after the timer like this:
               PNotify.update({
                 title: PNotify.options.title+" - Enjoy your Stay",
                 before_close: null
               });
               PNotify.queueRemove();
               return false;
             }
         });
        }
       },
       error: function(){
         new PNotify({
             title: "<p>Error al cambiar estado</p>",
             text: "<p></p>",
             addclass: 'custom',
             icon: 'picon picon-32 picon-dialog-cancel',
             opacity: .8,
             nonblock: {
               nonblock: true
             },
             before_close: function(PNotify){
               // You can access the notice's options with this. It is read only.
               //PNotify.options.text;
                    
               // You can change the notice's options after the timer like this:
               PNotify.update({
                 title: PNotify.options.title+" - Enjoy your Stay",
                 before_close: null
               });
               PNotify.queueRemove();
               return false;
             }
         });
       }
       });
      },
			defaultView: 'agendaWeek',
			header: {
				left: 'title',
				center: '',
				right: ''
			},
			defaultDate: {{date('Y-m-d')}},
			timezone: 'UTC',
			editable: false,
			droppable: true,
			eventLimit: true, 
			events: [

			  @if(!$agendaAppo->isEmpty())
			    @foreach($agendaAppo as $cita)
			      <?php
                    $dtstar= Carbon::parse($cita->start_date);
                    $dtend= Carbon::parse($cita->end_date);
			      ?>

			      {
			          id: '{{$cita->id}}',
			          startt: '{{$dtstar->toDateString()}} {{$dtstar->toTimeString()}}',
			          endd: '{{$dtend->toDateString()}} {{$dtend->toTimeString()}}',
			          title: '{{$cita->reason}}',
			          start: '{{$dtstar->toDateString()}}T{{$dtstar->toTimeString()}}',
			          end: '{{$dtend->toDateString()}}T{{$dtend->toTimeString()}}',
			          allDay: false,
			          @if($cita->state=="delayed")
			            color: "#FF9900",
			          @elseif($cita->state=="confirmed")
			            color: "#00D900",
			          @elseif($cita->state=="pending")
			            color: "#E40B0B",
			          @elseif($cita->state=="served")
			            color: "#4A86E8",
			          @elseif($cita->state=="in-progress")
			            color: "#FFFF00",
			          @endif
			          editable: true,
			      },

			    @endforeach
        @endif

        @if($customddays)
        @foreach($customddays as $customdday)
  		    @if($customdday->is_day_off!=1)    
			      <?php
             $dt = Carbon::now();
              $dt = $dt->toDateString(); 
			      ?>
  
			    	{
			    		title: 'Turno 1',
			    		start: '{{$customdday->day}}T{{$customdday->starttime_am}}',
			    		end: '{{$customdday->day}}T{{$customdday->endtime_am}}',
			    		allDay: false,
			    		color:"#cccccc"
			    	},
			    	{
			    		title: 'Turno 2',
			    		start: '{{$customdday->day}}T{{$customdday->lunch_start}}',
			    		end: '{{$customdday->day}}T{{$customdday->lunch_end}}',
			    		allDay: false,
			    		color:"#cccccc "
			    	},
			    	{
			    		title: 'Turno 3',
			    		start: '{{$customdday->day}}T{{$customdday->starttime_pm}}',
			    		end: '{{$customdday->day}}T{{$customdday->endtime_pm}}',
			    		allDay: false,
			    		color:"#cccccc"
			    	},
			    @else

			  	{
			  		title: 'DIA FERIADO',
			  		start: '{{$customdday->day}}',
			  		end: '{{$customdday->day}}',
			  		color:"#f35958"
			  	},
			    @endif
			  @endforeach
        @endif

        @if($configday)
  		    @if($configday->is_day_off!=1)    
			      <?php
             $dt = Carbon::now();
              $dt = $dt->toDateString(); 
			      ?>
  
			    	{
			    		title: 'Turno 1',
			    		start: '{{$dt}}T{{$configday->starttime_am}}',
			    		end: '{{$dt}}T{{$configday->endtime_am}}',
			    		allDay: false,
			    		color:"#cccccc"
			    	},
			    	{
			    		title: 'Turno 2',
			    		start: '{{$dt}}T{{$configday->lunch_start}}',
			    		end: '{{$dt}}T{{$configday->lunch_end}}',
			    		allDay: false,
			    		color:"#cccccc "
			    	},
			    	{
			    		title: 'Turno 3',
			    		start: '{{$dt}}T{{$configday->starttime_pm}}',
			    		end: '{{$dt}}T{{$configday->endtime_pm}}',
			    		allDay: false,
			    		color:"#cccccc"
			    	},
			    @else
			    	<?php
             $dt = Carbon::now();
			      ?>
			    	{
			    		title: 'DIA LIBRE',
			    		start: '{{$dt}}',
			    		end: '{{$dt}}',
			    		color:"#f35958"
			    	},
			    @endif
        @endif

			],
			drop: function(date, allDay) { 
                var  copiedEventObject = {
                title: $(this).text(),
                editable: true,
                start: date,
                allDay: allDay,
                color: $(this).css('background-color')
            };
				$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
				$(this).remove();
			}
		});
		$(".change-view").click(function(){
			 var data=$(this).data();
			$('#calendar').fullCalendar( 'changeView', data.view ); 
		});
		$(".change-today").click(function(){
			$('#calendar').fullCalendar( 'today' );
		}); 
		$(".change").click(function(){
			  var data=$(this).data();
			$('#calendar').fullCalendar( data.change );
		});
		
	});


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