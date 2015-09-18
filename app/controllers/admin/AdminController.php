<?php

class AdminController extends \BaseController {


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getHome()
	{
		return View::make('admin.dashboard.index');
	}

	public function getClinic()
	{
		return View::make('admin.clinic.index');
	}

	public function create_admin(){
		Sentry::getUserProvider()->create(array(
	        'email'    => 'admin@skywebplus.com',
	        'password' => '123',
	        'username' => 'diego10',
	        'first_name' => 'Diego',
	        'last_name' => 'Serras',
	        'activated' => 1,
	    ));	
	}

}