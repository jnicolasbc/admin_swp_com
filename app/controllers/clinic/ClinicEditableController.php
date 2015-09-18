<?php

class ClinicEditableController extends \BaseController {


	/**
	 * Display a listing of the resource.
	 *

	 * @return Response
	 */
	public function postPassClinic()
	{
		$id = Input::get('pk');
		$pass = Input::get('value');
		$user = Sentry::findUserById($id);
	    $user->password = $pass;
	    if ($user->save())
	    {
	        return 'ok';
	    }
	    else
	    {
	        return 'no';
	        
	    }
		
	}



}