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
                <li class="active">Editar Perfil</li>
            </ul>
        </div>
        <!-- page heading end-->

        <!--body wrapper start-->
        <div class="wrapper">
            <div class="row">
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading">
                           Editar Perfil
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
                                {{ Form::model($doctor, ['class' => 'cmxform form-horizontal adminex-form', 'files' => 'true', 'method' => 'PATCH', 'route' => ['doctor.profile.update', $doctor->id]]) }}
                                    <?php $user=User::find($doctor->user_id);?>

                                    <div class="form-group  col-ms-12">
                                        <div class="col-md-offset-2 col-md-10">
                                            {{ Form::submit('Guardar', ['class' => 'btn btn-primary']) }}
                                            {{ Form::reset('Cancelar', ['class' => 'btn btn-default']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-ms-12">
                                        <label for="firstname" class="control-label col-md-2">Username</label>
                                        <div class="col-md-6">
                                            <label for="firstname" class="control-label col-md-2">{{$user->username}}</label>
                                        </div>
                                    </div>

                                    <div class="form-group col-ms-6">
                                        <label for="firstname" class="control-label col-md-2">Nombre</label>
                                        <div class="col-md-4">
                                            {{ Form::text('first_name',$user->first_name, ['class' => 'form-control']) }}
                                        </div>
                                        <div class="col-md-4">
                                            {{ Form::text('last_name',$user->last_name, ['class' => 'form-control']) }}
                                        </div>
                                    </div>


                                    <div class="form-group col-ms-12">
                                        <label for="firstname" class="control-label col-md-2">Correo Electronico</label>
                                        <div class="col-md-6">
                                            {{ Form::text('email',$user->email, ['class' => 'form-control']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-ms-12">
                                        <label for="firstname" class="control-label col-md-2">Telefono Celular</label>
                                        <div class="col-md-4">
                                            @if(!$profile->isEmpty)
                                            {{ Form::text('phone', $profile->phone, ['class' => 'form-control']) }}
                                            @else
                                            {{ Form::text('phone', NULL, ['class' => 'form-control']) }}
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group col-ms-12">
                                        <label for="username" class="control-label col-md-2">Espacialidad</label>
                                        <div class="col-md-10">
                                            {{ Form::text('specialty_id', $specialty, ['class' => 'autoc form-control', 'data-role'=>'tagsinput']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-ms-12">
                                        <label for="firstname" class="control-label col-md-2">Tiempo de Duracion de una cita (Minutos)</label>
                                        <div class="col-md-6">
                                            {{ Form::number('dating_duration',$agenda->dating_duration, ['class' => 'form-control']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-ms-12">
                                        <label for="firstname" class="control-label col-md-2">Contraseña</label>
                                        <div class="col-md-4">
                                            <a href="#" id="password" data-type="text" data-pk="{{$doctor->user_id}}" data-placement="right" data-placeholder="Required" data-title="Enter New Password"></a>
                                        </div>
                                    </div>

                                    <div class="form-group  col-ms-12">
                                            <label class="control-label col-md-2">Seleccione una Foto</label><br>                                        
                                        <div class="col-sm-10">
                                            <div class="fileinput fileinput-exists" data-provides="fileinput">
                                                <input type="hidden" value="" name="">
                                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px; line-height: 150px;">
                                                            <img src="@if($profile->picture != ''){{url($profile->picture)}}@else http://agenda.dev/assets/doctor/images/profile_pic/default.png @endif" />
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
<script type="text/javascript" src="{{url('assets/plugins/typeahead/typeahead.bundle.min.js')}}"></script>
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