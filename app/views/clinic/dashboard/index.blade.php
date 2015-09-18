@extends('clinic.master')

@section('title', 'Admin Dashboard')@stop

@section('content')

	@if (Session::has('flash_message'))
			<p>{{ Session::get('flash_message') }}</p>
	@endif


	<div class="jumbotron">
		<h1>Admin Page Clinics</h1>
		<p>This page is for CLinics only!</p>
	</div>


@stop