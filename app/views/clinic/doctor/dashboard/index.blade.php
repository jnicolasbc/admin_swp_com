@extends('clinic.doctor.master')

@section('title', 'Doctor Dashboard')

@section('content')

	@if (Session::has('flash_message'))
			<p>{{ Session::get('flash_message') }}</p>
	@endif


	<div class="jumbotron">
		<h1>Company Page Doctor Hola Johnnn</h1>
		<p>This page is for Doctor only!</p>
	</div>


@stop