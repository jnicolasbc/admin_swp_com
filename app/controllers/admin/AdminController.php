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



}