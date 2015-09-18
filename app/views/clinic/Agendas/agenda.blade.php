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
											<a href="{{url('/clinic/doctors/create')}}" class="btn btn-primary" style='margin-top: 10px; margin-left: 10px;'>
										    <i class="fa fa-pencil-square-o"></i> Nuevo
										    </a>
												<div class="panel-body">
														
																<table class="table table-striped" id="table-example">
																		<thead>
																				<tr>
																						<th class="text-center">Imagen</th>
																						<th class="text-center">Name</th>
																						<th class="text-center">Email</th>
																						<th class="text-center">Phonagenda
																						<th class="text-center">Citas/Pacientes</th>
																				</tr>
																		</thead>
																		<tbody align="center"> 																				
																				@foreach($doctors as $d)
																					<?php
																					$doc =  User::find($d->user_id);
                                          $profile = Profile::where('user_id', $d->user_id)->first();
																					?>
																					<tr class="odd gradeX">	
																						<td><img class="circle profile-table" src="@if($profile->picture!="") {{url($profile->picture)}} @else http://agenda.dev/assets/doctor/images/profile_pic/default.png @endif" alt=""></td>																					
																						<td>{{$doc->getFullName()}}</td>																					
																						<td>{{$doc->email}}</td>																					
																						<td>{{$profile->phone}}</td>																					

                                            <td>
                                              <div class="col-md-12">
                                                <div class="col-md-4">
                                                      <a style="  width: 52px;" class="btn btn-info btn-transparent" href="{{url('/clinic/agenda/'.$d->id.'/patients/')}}" data-toggle="tooltip" data-placement="left" title="Pacientes">
                                                          <i class="glyphicon glyphicon-user"></i> 
                                                      </a>                                                    
                                                </div>
                                                <div class="col-md-4">
                                                      <a style="  width: 52px;" class="btn btn-info btn-transparent" href="{{url('/clinic/agenda/appointments/'.$d->id)}}" data-toggle="tooltip" data-placement="left" title="Citas">
                                                          <i class="fa fa-folder-o"></i> 
                                                      </a>                                                    
                                                </div>
                                                <div class="col-md-4">
                                                   <a style="  width: 90px;" class="btn btn-info btn-transparent" href="{{url('clinic/agendas-day/doctor/'.$d->id)}}" data-toggle="tooltip" data-placement="left" title="Agenda">
                                                       <i class="glyphicon glyphicon-book"></i> Agenda
                                                   </a> 
                                                </div>
                                              </div>                                                                                          
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
     $('[data-toggle="tooltip"]').tooltip()
		 $( '.checkAjax' ).change(function() {
            var $input = $(this);  
            var check = $input.prop( "checked" );
            if(check){
                $.ajax({
                    type: 'get',
                    dataType: "json",
                    url: "{{url('/clinic/doctor-status')}}",
                    data: {id: $input.attr("state")},
                    success: function(data){
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
                    },
                    error: function(){
                        new PNotify({
                            title: "<p>Error al cambiar estado</p>",
                            text: "<p></p>",
                            addclass: 'custom',
                            icon: 'picon picon-32 picon-mail-mark-unread',
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
            }else{
               $.ajax({
                    type: 'get',
                    dataType: "json",
                    url: "{{url('/clinic/doctor-status')}}",
                    data: {id: $input.attr("state")},
                    success: function(data){
                       new PNotify({
                            title: "<p>Estado</p>",
                            text: "<p>Cambiado con exito</p>",
                            addclass: 'custom',
                            icon: 'picon picon-32 picon-list-remove',
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
                    },
                    error: function(){
                        new PNotify({
                            title: "<p>Error al cambiar estado</p>",
                            text: "<p></p>",
                            addclass: 'custom',
                            icon: 'picon picon-32 picon-mail-mark-unread',
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
            }
        });
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