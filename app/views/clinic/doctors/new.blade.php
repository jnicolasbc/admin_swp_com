@extends('clinic.master')

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
                <li class="active">Agregar Medicos</li>
            </ul>
        </div>
        <!-- page heading end-->

        <!--body wrapper start-->
        <div class="wrapper">
            <div class="row">
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading">
                           Agregar Medicos
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
                                {{Form::open(array('class' => 'cmxform form-horizontal adminex-form', 'action' => 'ClinicDoctorsController@store', 'files' => 'true'))}}
                                    <div class="form-group  col-ms-12">
                                        <div class="col-md-offset-2 col-md-10">
                                            {{ Form::submit('Guardar', ['class' => 'btn btn-primary']) }}
                                            {{ Form::reset('Cancelar', ['class' => 'btn btn-default']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-ms-12">
                                        <label for="firstname" class="control-label col-md-2">Nombre y apellido</label>
                                        <div class="col-md-5">
                                            {{ Form::text('first_name', NULL, ['class' => 'form-control', 'placeholder'=>'Nombre']) }}
                                        </div>
                                        <div class="col-md-5">
                                            {{ Form::text('last_name', NULL, ['class' => 'form-control', 'placeholder'=>'Apellido']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-ms-12">
                                        <label for="username" class="control-label col-md-2">Nombre de Usuario</label>
                                        <div class="col-md-10">
                                            {{ Form::text('username', NULL, ['class' => 'form-control', 'placeholder'=>'Ej. nombre15']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-ms-12">
                                        <label for="firstname" class="control-label col-md-2">Correo Electronico</label>
                                        <div class="col-md-10">
                                            {{ Form::text('email', NULL, ['class' => 'form-control', 'placeholder'=>'Ej. nombre15@ejemplo.com']) }}
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label for="password" class="control-label col-lg-2">Especialidad</label>
                                        <div class="col-lg-10">
                                             <input id="specialty_id" name="specialty_id" type="text" class="form-control"  parsley-required="true" data-parsley-minlength="6" placeholder="Specialties" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false">
                                        </div>
                                    </div>

                                    <div class="form-group col-ms-12">
                                        <label for="firstname" class="control-label col-md-2">Telefono Celular</label>
                                        <div class="col-md-10">
                                            {{ Form::text('phone', NULL, ['class' => 'form-control', 'placeholder'=>'8888888888']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-ms-12">
                                        <label for="firstname" class="control-label col-md-2">lenguaje</label>
                                        <div class="col-md-10">
                                           {{ Form::select('lang', array('0' => 'selecione un Idioma', 'es' => 'Español', 'en' => 'Ingles'), '0', ['class' => 'form-control'])}}
                                        </div>
                                    </div>

                                    <div class="form-group col-ms-12">
                                        <label for="firstname" class="control-label col-md-2">Contraseña</label>
                                        <div class="col-md-10">
                                            {{ Form::password('password', NULL, ['class' => 'form-control']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-ms-12">
                                        <label for="firstname" class="control-label col-md-2">Confirme la Contraseña</label>
                                        <div class="col-md-10">
                                            {{ Form::password('password_confirmation', NULL, ['class' => 'form-control']) }}
                                        </div>
                                    </div>

                                    <div class="form-group  col-ms-12">
                                            <label class="control-label col-md-2">Seleccione una Foto</label><br>                                        
                                        <div class="col-sm-10">
                                            <div class="fileinput fileinput-exists" data-provides="fileinput">
                                                <input type="hidden" value="" name="">
                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px; line-height: 150px;">
                                                    <img src="http://agenda.dev/assets/doctor/images/profile_pic/default.png" />
                                                </div>
                                                <div> 
                                                    <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span>
                                                            <input type="file" name="picture">
                                                    </span> 
                                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a> 
                                                </div>
                                            </div>
                                            <br>
                                            <span class="label label-danger ">NOTA!</span>
                                            <span>Debe seleccionar solo archivos de formato jpg, png, gif.</span>
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
<script src="{{url('assets/plugins/typeahead/typeahead.jquery.min.js')}}" type="text/javascript" ></script>
<script src="{{url('assets/plugins/typeahead/bloodhound.min.js')}}" type="text/javascript" ></script>
<script type="text/javascript" src="http://timschlechter.github.io/bootstrap-tagsinput/examples/lib/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
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
 var my_insu = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: 'http://104.236.201.9/proyectos/agendamedica/api/specialty3'
    });
    my_insu.initialize();
    var elt = $('#specialty_id');
    
    elt.tagsinput({
        itemValue: 'value',
        itemText: 'text',
        typeaheadjs: {
          name: 'my_insu',
          displayKey: 'text',
          source: my_insu.ttAdapter()
        }
    });

@stop