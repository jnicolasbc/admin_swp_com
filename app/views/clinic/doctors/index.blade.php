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
																						<th class="text-center">Phone</th>
																						<th class="text-center">Status</th>
                                            <th class="text-center">Confirmacion Automatica</th>
																						<th class="text-center">Horarios/Calendario</th>
                                            <th class="text-center">Editar/Eliminar</th>
																				</tr>
																		</thead>
																		<tbody align="center"> 																				
																				@foreach($doctors as $d)
																					<?php
																					$doc =  User::find($d->user_id);
                                          $profile = Profile::where('user_id', $d->user_id)->first();
                                          $age = Agenda::where('doctor_id', $d->id)->first();
																					?>
																					<tr class="odd gradeX">	
																						<td><img class="circle profile-table" src="@if($profile->picture!="") {{url($profile->picture)}} @else http://agenda.dev/assets/doctor/images/profile_pic/default.png @endif" alt=""></td>																					
																						<td>{{$doc->getFullName()}}</td>																					
																						<td>{{$doc->email}}</td>																					
																						<td>{{$profile->phone}}</td>																					
																						<td>
																							@if($d->state!=0)
								                              <div class="col-sm-4 iSwitch flat-switch">
																								<div class="switch switch-small">
																									<input type="checkbox" class="checkAjax" data-toggle="tooltip" data-placement="left"  title="Activado" state="{{$d->id}}" checked>
																								</div>																												
																							</div><!-- //col-sm-4 -->
																							@else
												                      <div class="col-sm-4 iSwitch flat-switch">
																								<div class="switch switch-small">
																									<input type="checkbox" class="checkAjax" data-toggle="tooltip" data-placement="left"  title="Desactivado" state="{{$d->id}}" >
																								</div>																												
																							</div><!-- //col-sm-4 -->
																							@endif
																						</td>
                                            <td>
                                              @if($age->appointment_state!=1)
                                              <div class="col-sm-4 iSwitch flat-switch">
                                                <div class="switch switch-small">
                                                  <input type="checkbox" class="checkAjax2" data-toggle="tooltip" data-placement="left"  title="Activado" confirm="{{$age->id}}" >
                                                </div>                                                        
                                              </div><!-- //col-sm-4 -->
                                              @else
                                              <div class="col-sm-4 iSwitch flat-switch">
                                                <div class="switch switch-small">
                                                  <input type="checkbox" class="checkAjax2" data-toggle="tooltip" data-placement="left"  title="Desactivado" confirm="{{$age->id}}" checked>
                                                </div>                                                        
                                              </div><!-- //col-sm-4 -->
                                              @endif
                                            </td>

                                            <td>
                                              <div class="col-md-12">
                                                <div class="col-md-3">
                                                      <a style="  width: 52px;" class="btn btn-info btn-transparent" href="{{url('/clinic/doctor/'.$d->id.'/config-days')}}" data-toggle="tooltip" data-placement="left" title="Horarios">
                                                          <i class="fa fa-clock-o"></i> 
                                                      </a>
                                                </div>
                                                <div class=" col-md-3">
                                                      <a style="  width: 52px; margin-left: 15px;" class="btn btn-info btn-transparent" href="{{url('/clinic/doctor/custom-days/'.$d->id)}}" data-toggle="tooltip" data-placement="left" title="Calendario">
                                                          <i class="fa  fa-calendar"></i>
                                                      </a>                                                    
                                                </div>  
                                              </div>                                                                                          
                                            </td> 

																						<td>
																							<a href="{{url('/clinic/doctors/'.$d->id.'/edit')}}" type="button" class="btn btn-info btn-transparent" data-toggle="tooltip" data-placement="left" title="Editar"><i class="fa fa-pencil-square-o"></i></a>
																							<button class="btn btn-info btn-transparent" type="submit" data-toggle="tooltip" data-placement="left" title="Eliminar" onclick="var notice = new PNotify({
                                  text: $('#form_notice_{{$d->id}}').html(),
                                  icon: false,
                                  width: 'auto',
                                  hide: false,
                                  addclass: 'custom',
                                  icon: 'picon picon-32 picon-edit-delete',
                                  opacity: .8,
                                  nonblock: {
                                      nonblock: true
                                  },
                                  animation: {
                                      effect_in: 'show',
                                      effect_out: 'slide'
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
                                            });"><i class="glyphicon glyphicon-remove"></i></button>
                                    <div id="form_notice_{{$d->id}}" style="display: none;">
                                      {{ Form::open(array('method'=>'get', 'class'=>'pf-form pform_custom','url' => 'clinic/doctors/'.$d->id)) }}
                                        {{ Form::hidden("_method", "DELETE") }}
                                        <div class="pf-element pf-heading">
                                          <h3>Confirme que desea eliminar este doctor!</h3>
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
		 
        $('.checkAjax').change(function() {
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

        $('.checkAjax2').change(function() {
            var $input = $(this);  
            var check = $input.prop( "checked" );
            if(check){
                $.ajax({
                    type: 'get',
                    dataType: "json",
                    url: "{{url('/clinic/doctor-confirm')}}",
                    data: {id: $input.attr("confirm")},
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
                    url: "{{url('/clinic/doctor-confirm')}}",
                    data: {id: $input.attr("confirm")},
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