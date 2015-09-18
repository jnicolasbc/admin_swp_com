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
                {{Form::open(array('class' => 'cmxform form-horizontal adminex-form', 'action' => 'ClinicDoctorsController@postConfigDaySave', 'files' => 'true'))}}
                {{ Form::hidden("agenda", $agenda) }}
                <section class="panel">
                    <div class="form-group  col-ms-12">
                         <div class="col-md-offset-2 col-md-10">
                             {{ Form::submit('Guardar', ['class' => 'btn btn-primary']) }}
                             {{ Form::reset('Cancelar', ['class' => 'btn btn-default']) }}
                         </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped" id="table-example">
                            <thead>
                                <tr>
                                    <th class="text-center">Dia</th>
                                    <th class="text-center">Inicio Turno 1</th>
                                    <th class="text-center">Fin Turno 1</th>
                                    <th class="text-center">Inicio Turno 2</th>
                                    <th class="text-center">Fin Turno 2</th>
                                    <th class="text-center">Inicio Turno 3</th>
                                    <th class="text-center">Fin Turno 3</th>
                                    <th class="text-center">Dia no laboral </th>
                                </tr>
                            </thead>
                            <tbody align="center">
                                @if($configDay0)
                                <tr class="odd gradeX">
                                    <td>Domingo</td>
                                    <td>{{ Form::text("starttime_am", CustomDay::addespacios($configDay0->starttime_am), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_am", CustomDay::addespacios($configDay0->endtime_am), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_start", CustomDay::addespacios($configDay0->lunch_start), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_end", CustomDay::addespacios($configDay0->lunch_end), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("starttime_pm", CustomDay::addespacios($configDay0->starttime_pm), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_pm", CustomDay::addespacios($configDay0->endtime_pm), ['class' => 'timepicker form-control']) }}</td>
                                    <td>
                                        <div>       
                                            <ul class="iCheck"  data-color="red">
                                                <li><input type="checkbox"  value="1" name="is_day_off" parsley-mincheck="2" parsley-error-container="div#check-com-error" @if($configDay0->is_day_off==1) checked @endif></li>
                                            </ul>
                                            <div id="check-com-error"></div>
                                        </div>
                                    </td>                               
                                </tr>
                                @else
                                <tr class="odd gradeX">
                                    <td>Domingo</td>
                                    <td>{{ Form::text("starttime_am", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_am", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_start", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_end", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("starttime_pm", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_pm", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>
                                        <div>       
                                            <ul class="iCheck"  data-color="red">
                                                <li><input type="checkbox"  value="1" name="is_day_off" parsley-mincheck="2" parsley-error-container="div#check-com-error"></li>
                                            </ul>
                                            <div id="check-com-error"></div>
                                        </div>
                                    </td>                               
                                </tr>
                                @endif
                                @if($configDay1)
                                <tr class="odd gradeX">
                                    <td>Lunes</td>
                                    <td>{{ Form::text("starttime_am_1", CustomDay::addespacios($configDay1->starttime_am), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_am_1", CustomDay::addespacios($configDay1->endtime_am), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_start_1", CustomDay::addespacios($configDay1->lunch_start), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_end_1", CustomDay::addespacios($configDay1->lunch_end), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("starttime_pm_1", CustomDay::addespacios($configDay1->starttime_pm), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_pm_1", CustomDay::addespacios($configDay1->endtime_pm), ['class' => 'timepicker form-control']) }}</td>
                                    <td>
                                        <div>       
                                            <ul class="iCheck"  data-color="red">
                                                <li><input type="checkbox"  value="1" name="is_day_off_1" parsley-mincheck="2" parsley-error-container="div#check-com-error" @if($configDay1->is_day_off==1) checked @endif>
                                            </ul>
                                            <div id="check-com-error"></div>
                                        </div>
                                    </td>                               
                                </tr>
                                @else
                                <tr class="odd gradeX">
                                    <td>Lunes</td>
                                    <td>{{ Form::text("starttime_am_1", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_am_1", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_start_1", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_end_1", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("starttime_pm_1", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_pm_1", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>
                                        <div>       
                                            <ul class="iCheck"  data-color="red">
                                                <li><input type="checkbox"  value="1" name="is_day_off_1" parsley-mincheck="2" parsley-error-container="div#check-com-error">
                                            </ul>
                                            <div id="check-com-error"></div>
                                        </div>
                                    </td>                               
                                </tr>
                                @endif
                                @if($configDay2)
                                <tr class="odd gradeX">
                                    <td>Martes</td>
                                    <td>{{ Form::text("starttime_am_2", CustomDay::addespacios($configDay2->starttime_am), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_am_2", CustomDay::addespacios($configDay2->endtime_am), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_start_2", CustomDay::addespacios($configDay2->lunch_start), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_end_2", CustomDay::addespacios($configDay2->lunch_end), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("starttime_pm_2", CustomDay::addespacios($configDay2->starttime_pm), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_pm_2", CustomDay::addespacios($configDay2->endtime_pm), ['class' => 'timepicker form-control']) }}</td>
                                    <td>
                                        <div>       
                                            <ul class="iCheck"  data-color="red">
                                                <li><input type="checkbox"  value="1" name="is_day_off_2" parsley-mincheck="2" parsley-error-container="div#check-com-error" @if($configDay2->is_day_off==1) checked @endif>
                                            </ul>
                                            <div id="check-com-error"></div>
                                        </div>
                                    </td>                               
                                </tr>
                                @else
                                <tr class="odd gradeX">
                                    <td>Martes</td>
                                    <td>{{ Form::text("starttime_am_2", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_am_2", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_start_2", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_end_2", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("starttime_pm_2", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_pm_2", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>
                                        <div>       
                                            <ul class="iCheck"  data-color="red">
                                                <li><input type="checkbox"  value="1" name="is_day_off_2" parsley-mincheck="2" parsley-error-container="div#check-com-error">
                                            </ul>
                                            <div id="check-com-error"></div>
                                        </div>
                                    </td>                               
                                </tr>
                                @endif
                                @if($configDay3)
                                <tr class="odd gradeX">
                                    <td>Miercoles</td>
                                    <td>{{ Form::text("starttime_am_3", CustomDay::addespacios($configDay3->starttime_am), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_am_3", CustomDay::addespacios($configDay3->endtime_am), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_start_3", CustomDay::addespacios($configDay3->lunch_start), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_end_3", CustomDay::addespacios($configDay3->lunch_end), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("starttime_pm_3", CustomDay::addespacios($configDay3->starttime_pm), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_pm_3", CustomDay::addespacios($configDay3->endtime_pm), ['class' => 'timepicker form-control']) }}</td>
                                    <td>
                                        <div>       
                                            <ul class="iCheck"  data-color="red">
                                                <li><input type="checkbox"  value="1" name="is_day_off_3" parsley-mincheck="2" parsley-error-container="div#check-com-error" @if($configDay3->is_day_off==1) checked @endif>
                                            </ul>
                                            <div id="check-com-error"></div>
                                        </div>
                                    </td>                               
                                </tr>
                                @else
                                <tr class="odd gradeX">
                                    <td>Miercoles</td>
                                    <td>{{ Form::text("starttime_am_3", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_am_3", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_start_3", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_end_3", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("starttime_pm_3", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_pm_3", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>
                                        <div>       
                                            <ul class="iCheck"  data-color="red">
                                                <li><input type="checkbox"  value="1" name="is_day_off_3" parsley-mincheck="2" parsley-error-container="div#check-com-error">
                                            </ul>
                                            <div id="check-com-error"></div>
                                        </div>
                                    </td>                               
                                </tr>
                                @endif
                                @if($configDay4)
                                <tr class="odd gradeX">
                                    <td>Jueves</td>
                                    <td>{{ Form::text("starttime_am_4", CustomDay::addespacios($configDay4->starttime_am), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_am_4", CustomDay::addespacios($configDay4->endtime_am), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_start_4", CustomDay::addespacios($configDay4->lunch_start), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_end_4", CustomDay::addespacios($configDay4->lunch_end), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("starttime_pm_4", CustomDay::addespacios($configDay4->starttime_pm), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_pm_4", CustomDay::addespacios($configDay4->endtime_pm), ['class' => 'timepicker form-control']) }}</td>
                                    <td>
                                        <div>       
                                            <ul class="iCheck"  data-color="red">
                                                <li><input type="checkbox"  value="1" name="is_day_off_4" parsley-mincheck="2" parsley-error-container="div#check-com-error" @if($configDay4->is_day_off==1) checked @endif>
                                            </ul>
                                            <div id="check-com-error"></div>
                                        </div>
                                    </td>                               
                                </tr>
                                @else
                                <tr class="odd gradeX">
                                    <td>Jueves</td>
                                    <td>{{ Form::text("starttime_am_4", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_am_4", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_start_4", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_end_4", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("starttime_pm_4", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_pm_4", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>
                                        <div>       
                                            <ul class="iCheck"  data-color="red">
                                                <li><input type="checkbox"  value="1" name="is_day_off_4" parsley-mincheck="2" parsley-error-container="div#check-com-error">
                                            </ul>
                                            <div id="check-com-error"></div>
                                        </div>
                                    </td>                               
                                </tr>
                                @endif
                                @if($configDay5)
                                <tr class="odd gradeX">
                                    <td>Viernes</td>
                                    <td>{{ Form::text("starttime_am_5", CustomDay::addespacios($configDay5->starttime_am), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_am_5", CustomDay::addespacios($configDay5->endtime_am), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_start_5", CustomDay::addespacios($configDay5->lunch_start), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_end_5", CustomDay::addespacios($configDay5->lunch_end), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("starttime_pm_5", CustomDay::addespacios($configDay5->starttime_pm), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_pm_5", CustomDay::addespacios($configDay5->endtime_pm), ['class' => 'timepicker form-control']) }}</td>
                                    <td>
                                        <div>       
                                            <ul class="iCheck"  data-color="red">
                                                <li><input type="checkbox"  value="1" name="is_day_off_5" parsley-mincheck="2" parsley-error-container="div#check-com-error" @if($configDay5->is_day_off==1) checked @endif>
                                            </ul>
                                            <div id="check-com-error"></div>
                                        </div>
                                    </td>                               
                                </tr>
                                @else
                                <tr class="odd gradeX">
                                    <td>Viernes</td>
                                    <td>{{ Form::text("starttime_am_5", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_am_5", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_start_5", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_end_5", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("starttime_pm_5", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_pm_5", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>
                                        <div>       
                                            <ul class="iCheck"  data-color="red">
                                                <li><input type="checkbox"  value="1" name="is_day_off_5" parsley-mincheck="2" parsley-error-container="div#check-com-error">
                                            </ul>
                                            <div id="check-com-error"></div>
                                        </div>
                                    </td>                               
                                </tr>
                                @endif
                                @if($configDay6)
                                <tr class="odd gradeX">
                                    <td>Sabado</td>
                                    <td>{{ Form::text("starttime_am_6", CustomDay::addespacios($configDay6->starttime_am), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_am_6", CustomDay::addespacios($configDay6->endtime_am), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_start_6", CustomDay::addespacios($configDay6->lunch_start), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_end_6", CustomDay::addespacios($configDay6->lunch_end), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("starttime_pm_6", CustomDay::addespacios($configDay6->starttime_pm), ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_pm_6", CustomDay::addespacios($configDay6->endtime_pm), ['class' => 'timepicker form-control']) }}</td>
                                    <td>
                                        <div>       
                                            <ul class="iCheck"  data-color="red">
                                                <li><input type="checkbox"  value="1" name="is_day_off_6" parsley-mincheck="2" parsley-error-container="div#check-com-error" @if($configDay6->is_day_off==1) checked @endif></li>
                                            </ul>
                                            <div id="check-com-error"></div>
                                        </div>
                                    </td>                               
                                </tr>
                                @else
                                <tr class="odd gradeX">
                                    <td>Sabado</td>
                                    <td>{{ Form::text("starttime_am_6", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_am_6", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_start_6", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("lunch_end_6", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("starttime_pm_6", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>{{ Form::text("endtime_pm_6", NULL, ['class' => 'timepicker form-control']) }}</td>
                                    <td>
                                        <div>       
                                            <ul class="iCheck"  data-color="red">
                                                <li><input type="checkbox"  value="1" name="is_day_off_6" parsley-mincheck="2" parsley-error-container="div#check-com-error"></li>
                                            </ul>
                                            <div id="check-com-error"></div>
                                        </div>
                                    </td>                               
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </section>
                {{ Form::close() }}
            </div>
        </div>
		<!-- //content > row-->
	</div>
	<!-- //content-->
@stop
@section('js-script')
	$(function() {
        $('.timepicker').timepicki({
            start_time: ["06", "00"],
       		show_meridian:false,
       		min_hour_value:0,
       		max_hour_value:23,
       		step_size_minutes:15,
       		overflow_minutes:true,
       	});
	});
@stop 