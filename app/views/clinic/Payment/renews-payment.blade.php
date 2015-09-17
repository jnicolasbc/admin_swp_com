@extends('clinic.master-renueva')

@section('title', 'Admin Dashboard')@stop

@section('content')

	@if (Session::has('flash_message'))
			<p>{{ Session::get('flash_message') }}</p>
	@endif


	<div class="jumbotron">
		<h1>Renuava tu plan</h1>
		<p>No tienes un plan activo en este momento</p>
	</div>


@stop