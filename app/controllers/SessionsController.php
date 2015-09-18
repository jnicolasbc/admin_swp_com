<?php

use basicAuth\formValidation\LoginForm;

class SessionsController extends \BaseController {

	protected $loginForm;

	function __construct(LoginForm $loginForm)
	{
		$this->loginForm = $loginForm;
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('front.sessions.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$this->loginForm->validate($input = Input::only('email', 'password'));

		try
		{
			Sentry::authenticate($input,true);
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		   	 
		   	$mjs = array(
		    	'success'=>false,
		    	'mgs'=>trans('main.mgs_invalid_credential'),
		    	'url'=>''
		    );
	    	return Response::json($mjs);
		}
		catch (\Cartalyst\Sentry\Users\UserNotActivatedException $e)
		{		   	
	   		$mjs = array(
		    	'success'=>false,
		    	'mgs'=>trans('main.user_not_activated'),
		    	'url'=>''
		    );
	    	return Response::json($mjs);
		}
		catch(\Cartalyst\Sentry\Throttling\UserSuspendedException $e) 
		{
			$mjs = array(
		    	'success'=>false,
		    	'mgs'=>trans('main.user_suspended'),
		    	'url'=>''
		    );
	    	return Response::json($mjs);
		}

		// Logged in successfully - redirect based on type of user
		$user = Sentry::getUser();
	    $admin = Sentry::findGroupByName('Admins');
	    $users = Sentry::findGroupByName('Patients');
	    $doctors = Sentry::findGroupByName('Doctors');
	    $company = Sentry::findGroupByName('Clinics');
	    $recepcion = Sentry::findGroupByName('Receptionist');
	    
	    if ($user->inGroup($admin)){
	    	 $mjs = array(
		    	'success'=>true,
		    	'mgs'=>trans('main.mgs_access'),
		    	'url'=>url().'/admin'
		    );
	    	 return Response::json($mjs);	    	
	    }
	    elseif ($user->inGroup($company) or $user->inGroup($recepcion)){ 
	    	 $mjs = array(
		    	'success'=>true,
		    	'mgs'=>trans('main.mgs_access'),
		    	'url'=>url().'/clinic'
		    );
	    	 return Response::json($mjs);
	    }
	    elseif ($user->inGroup($doctors)){ 
	    	 $mjs = array(
		    	'success'=>true,
		    	'mgs'=>trans('main.mgs_access'),
		    	'url'=>url().'/doctor'
		    );
	    	 return Response::json($mjs);
	    }
	    elseif ($user->inGroup($users)){ 
	    	return Redirect::to(url());
	    }


	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id=null)
	{
		Sentry::logout();

		return Redirect::home();
	}

}