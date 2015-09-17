@extends('clinic.master')

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
	<div id="content">
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">								 
					
				</section>
			</div>
		</div>
		<div class="row">
           <div class="col-lg-12">
		            <!--
		            ///////////////////////////////////////////////////////////////////
		            //////////     MODAL MESSAGES     //////////
		            ///////////////////////////////////////////////////////////////
		            -->
		            <div id="md-add-event" class="modal fade md-slideUp" tabindex="-1" data-width="450"  data-header-color="inverse">
		            	<div class="modal-header">
		            			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
		            			<h4 class="modal-title"><i class="fa fa-filter"></i> Filtra por</h4>
		            	</div>
		            	<!-- //modal-header-->
		            	<div class="modal-body" style="padding-bottom:0">
		            		{{Form::open(array('method'=>'GET','class' => 'cmxform form-horizontal adminex-form', 'action' => 'ClinicController@getPatients', 'files' => 'true'))}}
		            			
		            			<div class="form-group ">
                                    <label class="control-label">Doctor</label>
                                     <?php 
                                     $user        = Sentry::getUser();
                                     $docs = DB::table('clinics')
                                                    ->join('doctors', 'clinics.id', '=', 'doctors.clinic_id')                                         
                                                    ->join('users', 'doctors.user_id', '=', 'users.id')
                                                    ->where('clinics.user_id',  $user->id)
                                                    ->groupBy('doctors.id')
                                                    ->select('users.first_name as name_as', 'doctors.id as idDoctor')
                                                    ->get(); 

                                    ?>
                                    <select class="form-control"  name="doctor">
                                        <option  value="" selected>Seleccione un Doctor</option>
                                        @foreach($docs as $doc)
                                         <option  value="{{$doc->idDoctor}}">{{$doc->name_as}}</option>
                                        @endforeach 
                                    </select>  
                                </div>
                                <label class="control-label">Motivo Consulta </label>
                                <div class="form-group">
		            				{{ Form::text('motivo', NULL,['id'=>'motivo', 'class' => 'form-control'])}}
		            			</div>
                                <label class="control-label">Especialidad Medica</label>
                                <div class="form-group ">
                                         <?php $spec = Specialty::all(); ?>
                                     <select class="form-control"  name="specialty">
                                        <option  value="" selected>Seleccione una Especialidad</option>
                                        @foreach($spec as $spe)
                                         <option  value="{{$spe->id}}">{{$spe->name_es}}</option>
                                        @endforeach 
                                    </select>     
                                </div>

                                <label class="control-label">Fecha Consulta </label><br>
                                <label class="control-label">Desde </label>
		            	        <div class='form-group input-group date datetimepicker2'>
                                    <input parsley-required="true" name="star" type='text' placeholder="Día-Mes-Año" class="form-control" />
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                                <label class="control-label">Hasta </label>
                                <div class='form-group input-group date datetimepicker2' >
                                    <input parsley-required="true" name="end" type='text' placeholder="Día-Mes-Año" class="form-control" />
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </div>

		            			<div class="form-group ">
                                    <label class="control-label">Aseguradora</label>
                                    <?php $ases = Insurance::all(); ?>
                                    <select class="form-control"  name="seguro">
                                      <option  value="" selected>Seleccione una Aseguradora</option>
                                     @foreach($ases as $ase)
                                      <option  value="{{$ase->id}}">{{$ase->name}}</option>
                                     @endforeach 
                                    </select>
                                </div>

		            			<div class="form-group">
		            				<hr>
		            				<button type="submit" class="btn btn-theme"><i class="fa fa-filter"></i> Filtrar</button>
		            			</div>

		            		{{ Form::close() }}
		            	</div>
		            	<!-- //modal-body-->
		            </div>
		            <!-- //modal-->
                </div>

			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-body">
							<a class="btn btn-inverse" data-toggle="modal" href="#md-add-event"><i class="fa fa-filter"></i> Filtros</a>
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
                                        $patient = Patient::find($d->patient_id);
									    $user    = User::find($patient->user_id);
									    $profile = Profile::where('user_id', $user->id)->first();
									?>
									<tr class="odd gradeX">	
										<td><img class="circle profile-table" src="@if($profile->picture!="") {{url($profile->picture)}} @else http://agenda.dev/assets/doctor/images/profile_pic/default.png @endif" alt=""></td>
										<td>{{$user->getFullName()}}</td>																					
										<td>{{$user->email}}</td>
										<td>{{$profile->phone}}</td>
										<td><a href="{{url('/clinic/doctor/'.$d->doctor_id.'/patient/'.$d->patient_id.'/appointments-pending')}}" type="button" class="btn btn-info btn-transparent" data-toggle="tooltip" data-placement="left" title="Citas Pendientes"><i class="fa fa-clock-o"></i> Pendientes</a>
										    <a href="{{url('/clinic/doctor/'.$d->doctor_id.'/patient/'.$d->patient_id.'/appointments-history')}}" type="button" class="btn btn-info btn-transparent" data-toggle="tooltip" data-placement="left" title="Historia de citas"><i class="fa fa-clock-o"></i> Historia</a></td>
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
         $('.datetimepicker2').datetimepicker({
                    locale: '{{H::lang()}}',
                    format: 'D-M-YYYY'
                })
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