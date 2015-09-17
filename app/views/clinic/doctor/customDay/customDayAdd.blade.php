@extends('clinic.doctor.master')

@section('title', 'Doctor Dashboard')

@section('content')
        <!-- page heading start-->
        <div class="page-heading">
            <h3>
                Editar Medicos
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="#">Inicio</a>
                </li>
                <li class="active">Editar Medicos</li>
            </ul>
        </div>
        <!-- page heading end-->

        <!--body wrapper start-->
        <div class="wrapper">
            <div class="row">
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading">
                           Editar Medicos
                        </header>
                        @if($errors->has())
                            <div class="alert-box alert">
                               <!--recorremos los errores en un loop y los mostramos-->
                               @foreach ($errors->all('<p style="  background-color: rgb(216, 47, 47); color: white;">:message</p>') as $message)
                                        {{ $message }}
                               @endforeach
                            </div>
                        @endif
                        <div class="panel-body">
                            <div class="form">
                                {{Form::open(array('class' => 'cmxform form-horizontal adminex-form', 'action' => 'DoctorsCustomDaysController@store', 'files' => 'true'))}}
                                    <div class="form-group  col-ms-12">
                                        <div class="col-md-offset-2 col-md-10">
                                            {{ Form::submit('Guardar', ['class' => 'btn btn-primary']) }}
                                            {{ Form::reset('Cancelar', ['class' => 'btn btn-default']) }}
                                        </div>
                                    </div>
                                    {{ Form::hidden("id", $agendaID) }}
                                    <div class="form-group">
										<label class="control-label">Dia</label>
										<div class="row">
												<div class="col-md-6"><input type="text" class="form-control" name="day" parsley-type="dateIso" parsley-required="true" placeholder="YYYY-MM-DD"></div>
										</div>
									</div>

									<div class="form-group">
											<label class="control-label">Inicio Mañana  </label>
											<div>
													<div class="row">
															<div class=" col-lg-6">
																	<input type="text" class="timepicker form-control" name="startmorning">
															</div>
													</div>
											</div>
									</div><!-- //form-group-->

                                    <div class="form-group">
											<label class="control-label">Fin Mañana </label>
											<div>
													<div class="row">
															<div class="timepicker col-lg-6" >
																	<input type="text" class="timepicker form-control" name="endmornig">
				 											</div>
													</div>
											</div>
									</div><!-- //form-group-->

                                    <div class="form-group">
											<label class="control-label">Inicio Almuerzo </label>
											<div>
													<div class="row">
															<div class="col-lg-6" data-picker-position="bottom-left"  data-date-format="dd MM yyyy - HH:ii p" >
																	<input type="text" class="timepicker form-control" name="startlaunch">
															</div>
													</div>
											</div>
									</div><!-- //form-group-->

                                    <div class="form-group">
											<label class="control-label">Fin Almuerzo </label>
											<div>
													<div class="row">
															<div class="col-lg-6" data-picker-position="bottom-left"  data-date-format="dd MM yyyy - HH:ii p" >
																	<input type="text" class="timepicker form-control" name="endlaunch">
													</div>
											</div>
									</div><!-- //form-group-->

                                    <div class="form-group">
											<label class="control-label">Inicio Tarde </label>
											<div>
													<div class="row">
															<div class="col-lg-6" data-picker-position="bottom-left"  data-date-format="dd MM yyyy - HH:ii p" >
																	<input type="text" class="timepicker form-control" name="starttarde">
															</div>
													</div>
											</div>
									</div><!-- //form-group-->

                                    <div class="form-group">
											<label class="control-label">Fin Tarde </label>
											<div>
													<div class="row">
															<div class="col-lg-6" data-picker-position="bottom-left"  data-date-format="dd MM yyyy - HH:ii p" >
																	<input type="text" class="timepicker  form-control" name="endtarde">
															</div>
													</div>
											</div>
									</div><!-- //form-group-->

                                    <div class="form-group">
										<label class="control-label">Dia Feriado</label>
										<div>		
											<ul class="iCheck"  data-color="red">
												<li>
														<input type="checkbox"  value="1" name="state" parsley-mincheck="2" parsley-error-container="div#check-com-error">
														<label>Estado</label>
												</li>
											</ul>
											<div id="check-com-error"></div>
										</div>
									</div>
                                    <div class="form-group  col-ms-12">
                                        <div class="col-md-offset-2 col-md-10">
                                            {{ Form::submit('Guardar', ['class' => 'btn btn-primary']) }}
                                            {{ Form::reset('Cancelar', ['class' => 'btn btn-default']) }}
                                        </div>
                                    </div>

                                {{ Form::close() }}
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!--body wrapper end-->
@stop    
@section("js")
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script type="text/javascript" src="{{url('assets/plugins/typeahead/typeahead.bundle.min.js')}}"></script>
@stop

@section('js-script')
 $(function() {	
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
 });

  //defaults
  $.fn.editable.defaults.url = 'data/x-post.php';

    $('#password').editable({
        url: '{{url("clinic/editable/pass-clinic")}}',
        name: 'password',
        title: 'Enter New Password',
        validate: function(value) {
            if ($.trim(value) == '') return 'This field is required';
        }
    });


@stop