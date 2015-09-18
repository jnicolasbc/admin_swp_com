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
						<form>
							<table class="table table-striped" id="table-example">
								<thead>
									<tr>
										<th class="text-center">Imagen</th>
										<th class="text-center">Name</th>
										<th class="text-center">Email</th>
										<th class="text-center">Phone</th>
										<th class="text-center">Citas pendientes/Historia de citas</th>
									</tr>
								</thead>
								<tbody align="center"> 																				
									@foreach($patients as $d)
									<?php 
									    $user    = User::find($d->user_id);
									    $profile = Profile::where('user_id', $user->id)->first();
									?>
									<tr class="odd gradeX">	
										<td><img class="circle profile-table" src="@if($profile->picture!="") {{url($profile->picture)}} @else http://agenda.dev/assets/doctor/images/profile_pic/default.png @endif" alt=""></td>
										<td>{{$user->getFullName()}}</td>																					
										<td>{{$user->email}}</td>
										<td>{{$profile->phone}}</td>
										<td><a href="{{url('/doctor/agenda/'.$d->agenda_id.'/patient/'.$d->patient_id.'/appointments-pending')}}" type="button" class="btn btn-info btn-transparent" data-toggle="tooltip" data-placement="left" title="Citas Pendientes"><i class="fa fa-clock-o"></i> Pendientes</a>
										    <a href="{{url('/doctor/agenda/'.$d->agenda_id.'/patient/'.$d->patient_id.'/appointments-history')}}" type="button" class="btn btn-info btn-transparent" data-toggle="tooltip" data-placement="left" title="Historia de citas"><i class="fa fa-clock-o"></i> Historia</a></td>
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