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
                           Configuracion
                        </header>
                        <div class="panel-body">
                            <div class="form">
                                {{Form::open(array('method'=>'get', 'class' => 'cmxform form-horizontal adminex-form', 'action' => 'DoctorController@getConfigSave', 'files' => 'true'))}}
                                    <div class="form-group  col-ms-12">
                                        <div class="col-md-offset-2 col-md-10">
                                            {{ Form::submit('Guardar', ['class' => 'btn btn-primary']) }}
                                            {{ Form::reset('Cancelar', ['class' => 'btn btn-default']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-ms-12">
                                        <label for="username" class="control-label col-md-2">Aseguradoras</label>
                                        <div class="col-md-10">
                                            @if(isset($option))
                                            {{ Form::text('insurance', $option->key, ['class' => 'autoc form-control', 'data-role'=>'tagsinput']) }}
                                            @else
                                            {{ Form::text('insurance', NULL, ['class' => 'autoc form-control', 'data-role'=>'tagsinput']) }}
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group col-ms-12">
                                        <label for="firstname" class="control-label col-md-2">Idioma</label>
                                        <div class="col-md-10">
                                              @if(isset($optionLang))
                                            {{ Form::select('lang', array('es'=>'Español', 'en'=>'Ingles'), $optionLang->key, ['class' => 'form-control']) }} 
                                            @else
                                            {{ Form::select('lang', array('es'=>'Español', 'en'=>'Ingles'), NULL, ['class' => 'form-control']) }} 
                                            @endif 
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
<script type="text/javascript" src="{{url('assets/plugins/tagInput/bootstrap-tagsinput.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.10.4/typeahead.bundle.min.js"></script>

@stop

@section('js-script')
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