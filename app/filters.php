<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/
$path = storage_path().'/logs/query.log';

App::before(function($request) use($path) {
    $start = PHP_EOL.'=| '.$request->method().' '.$request->path().' |='.PHP_EOL;
  File::append($path, $start);
});

Event::listen('illuminate.query', function($sql, $bindings, $time) use($path) {
    // Uncomment this if you want to include bindings to queries
    //$sql = str_replace(array('%', '?'), array('%%', '%s'), $sql);
    //$sql = vsprintf($sql, $bindings);
    $time_now = (new DateTime)->format('Y-m-d H:i:s');;
    $log = $time_now.' | '.$sql.' | '.$time.'ms'.PHP_EOL;
  File::append($path, $log);
});

App::before(function($request)
{	 
	    $cookie_lang = Cookie::get('language');
	    $browser_lang = substr(Request::server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
	   	if(!empty($cookie_lang) AND in_array($cookie_lang, Config::get('app.languages')))
	    {
	        App::setLocale($cookie_lang);
	    }
	    else if(!empty($browser_lang) AND in_array($browser_lang, Config::get('app.languages')))
	    {
	        if($browser_lang != $cookie_lang)
	        {
	            Cookie::forever('language',$browser_lang);
	            Session::put('language', $browser_lang);
	        }
	        App::setLocale($browser_lang);
	    }
	    else
	    {
	        App::setLocale(Config::get('app.locale'));
	    }
});
	 
App::after(function($request, $response)
{
	$lang = Session::get('language');
	if(!empty($lang))
	{
	    $response->withCookie(Cookie::forever('language',$lang));
	}
});


Route::filter('auth', function()
{
	if (!Sentry::check()) return Redirect::guest('/');
});

Route::filter('api.patient', function()
{
	if (!Cache::has('_token_'))
	{
		 return Api\V1\Helpers::Mgs("Api No Acesss. Api. Token");    
	}
});

Route::filter('api.cache', function()
{
	if (!Cache::has('_token_'))
	{
		Sentry::logout();
		return Api\V1\Helpers::Mgs("Api No Acesss.");
	}	
});

Route::filter('admin', function()
{
	$user = Sentry::getUser();
    $admin = Sentry::findGroupByName('Admins');

    if (!$user->inGroup($admin))
    {
    	return Redirect::to('/');
    }
});

Route::filter('clinic', function()
{
	$user = Sentry::getUser();
    $company = Sentry::findGroupByName('Clinics');
    $recep = Sentry::findGroupByName('Receptionist');
    
    if (!$user->inGroup($company) or !$user->inGroup($recep))
    {
    	return Redirect::to('/');
    }
});

Route::filter('doctor', function()
{
	$user = Sentry::getUser();
    $company = Sentry::findGroupByName('Doctors');

    if (!$user->inGroup($company))
    {
    	return Redirect::to('/');
    }
});


Route::filter('standardUser', function()
{
	$user = Sentry::getUser();
    $users = Sentry::findGroupByName('Patients');

    if (!$user->inGroup($users))
    {
    	return Redirect::to('/');
    }
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Sentry::check())
	{
		// Logged in successfully - redirect based on type of user
		$user = Sentry::getUser();
	    $admin = Sentry::findGroupByName('Admins');
	    $users = Sentry::findGroupByName('Patients');
	    $company = Sentry::findGroupByName('Clinics');
	    $doctor = Sentry::findGroupByName('Doctors');
	    $reception = Sentry::findGroupByName('Receptionist');

	    $u1= url().'/admin';
	    if ($user->inGroup($admin)) return Redirect::to($u1);
	    elseif ($user->inGroup($company)) return Redirect::intended(url().'/clinic');
	    elseif ($user->inGroup($reception)) return Redirect::intended(url().'/clinic');	    
	    elseif ($user->inGroup($doctor)) return Redirect::intended(url().'/doctor');	    
	    elseif ($user->inGroup($users)) return Redirect::intended(url().'/');
	}
});

// Route::filter('guest', function()
// {
// 	if (Auth::check()) return Redirect::to('/');
// });

Route::filter('redirectAdmin', function()
{
	if (Sentry::check())
	{
		$user = Sentry::getUser();
	    $admin = Sentry::findGroupByName('Admins');

	    if ($user->inGroup($admin)) return Redirect::intended('admin');
	}
});

Route::filter('redirectClinic', function()
{
	if (Sentry::check())
	{
		$user = Sentry::getUser();
	    $company = Sentry::findGroupByName('Clinics');
	    $reception = Sentry::findGroupByName('Receptionist');

	    if ($user->inGroup($company) or $user->inGroup($reception)) 
	    	return Redirect::intended('clinic');
	}
});

Route::filter('redirectDoctor', function()
{
	if (Sentry::check())
	{
		$user = Sentry::getUser();
	    $company = Sentry::findGroupByName('Doctors');

	    if ($user->inGroup($company)) return Redirect::intended('doctor');
	}
});



Route::filter('currentUser', function($route)
{

    if (!Sentry::check()) return Redirect::home();

    if (Sentry::getUser()->id != $route->parameter('profiles'))
    {
        return Redirect::home();
    }
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
