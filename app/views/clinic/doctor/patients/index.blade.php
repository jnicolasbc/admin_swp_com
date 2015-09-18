@extends('clinic.doctor.master')

@section('title', 'Doctor Dashboard')

@section('content')

	<ol class="breadcrumb">
			<li><a href="{{url('/clinic')}}">Home</a></li>
			<li class="active">{{trans('doctor.see patients')}}</li>
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
												<!--<header class="panel-heading">
														<h2><strong>Lista</strong>Doctores </h2>
														<label class="color">Plugin for <strong>Bootstrap3</strong></label>
												</header>
												<div class="panel-tools fully color" align="right" data-toolscolor="#6CC3A0">
														<ul class="tooltip-area">
																<li><a href="javascript:void(0)" class="btn btn-collapse" title="Collapse"><i class="fa fa-sort-amount-asc"></i></a></li>
																<li><a href="javascript:void(0)" class="btn btn-reload"  title="Reload"><i class="fa fa-retweet"></i></a></li>
																<li><a href="javascript:void(0)" class="btn btn-close" title="Close"><i class="fa fa-times"></i></a></li>
														</ul>
												</div>-->
												<div class="panel-body">
														<form>
																<table class="table table-striped" id="table-example">
																		<thead>
																				<tr>
																						<th class="text-center">Imagen</th>
																						<th class="text-center">Name</th>
																						<th class="text-center">Email</th>
																						<th class="text-center">Phone</th>
																						<th class="text-center">Status</th>
																						<th class="text-center"></th>
																				</tr>
																		</thead>
																		<tbody align="center"> 																				
																				@foreach($patient as $d)
																					<?php 
																					$p = Patient::find($d->patient_id); 
                                                                                    $user = User::find($p->user_id);      
																					?>																				
																					<tr class="odd gradeX">	
																					    <td><img class="circle profile-table" src="{{url()."/".$user->avatar}}" alt=""></td>																					
																					    <td>{{$user->name}}</td>																					
																					    <td>{{$user->email}}</td>																					
																					    <td>{{$user->phone}}</td>																					
																					    <td>
																							@if($d->status!=0)
																								<ul class="iCheck" data-style="square" data-color="green" style="width:40px; margin: 0">
																									<li>
																										<div class="icheckbox_square-green checked"><input type="checkbox" checked="" id="iCheck-cp2-1" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>																											
																									</li>																										 
																								</ul>
																							@else
																								<ul class="iCheck" data-style="square" data-color="green" style="width:40px; margin: 0">
																									<li>
																										<div class="icheckbox_square-green"><input type="checkbox" id="iCheck-cp2-0" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>																											 
																									</li>																																																			 
																								</ul>
																							@endif
																						</td>																					
																						<td>
																							<button type="button" class="btn btn-info btn-transparent"><i class="fa fa-pencil-square-o"></i></button>
																							<button type="button" class="btn btn-theme-inverse btn-transparent"><i class="glyphicon glyphicon-remove"></i></button>																																														
																						</td>																					
																					</tr>
																				@endforeach																				
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
