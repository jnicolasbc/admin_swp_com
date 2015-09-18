<?php
use \Api\V1\Helpers as H; 

$lang_c=Cookie::get('language');	   
$l = (is_null($lang_c)) ? Config::get('app.locale') : Cookie::get('language');
App::setLocale($l);

# CSRF Protection
Route::when('admin/*', 'csrf', ['POST', 'PUT', 'PATCH', 'DELETE']);


Route::get('create', 'AdminController@create_admin');

Route::get('lang/en', function ()
{   
    Cookie::forever('language','en'); 
    App::setLocale('en'); 
    Session::put('language', 'en');
    return Redirect::to('/');
});

Route::get('lang/es', function ()
{   
    Cookie::forever('language','es');  
    App::setLocale('es'); 
    Session::put('language', 'es');    
    return Redirect::to('/');
});





# Paginas Webs
Route::group(['prefix' => '/'], function()
{
	Route::get('/', ['as' => 'home', 'uses' => 'SessionsController@create']);
});

# Visitantes

	Route::get('/register', 'RegistrationController@create');
	Route::post('/register', ['as' => 'registration.store', 'uses' => 'RegistrationController@store']);
	Route::get('forgot_password', 'RemindersController@getRemind');
	Route::post('forgot_password','RemindersController@postRemind');
	Route::get('reset_password/{token}', 'RemindersController@getReset');
	Route::post('reset_password/{token}', 'RemindersController@postReset');
	
	Route::any('cities/{id}', 'PagesController@Cities');
	Route::any('countries/', 'PagesController@Countries');
	Route::any('insurances/', 'PagesController@Incuranses');

	Route::controller('action', 'PagesController');	



# Autentificacion
Route::get('logout', ['as' => 'logout', 'uses' => 'SessionsController@destroy']);
Route::resource('sessions', 'SessionsController' , ['only' => ['create','store','destroy']]);



Route::get('test', function ()
{   
	$in_start = date('Y-m-d', Input::get('start'));
	$date = $in_start;
	$states = array('confirmed', 'delayed', 'pending', 'in-progress', 'served', 'old');
	$appointment = Appointment::where('day',$date)
								->whereIn('state',$states)
								->get();
	dd(count($appointment));
});

# Solo Pacientes Pacientes
Route::group(['before' => 'auth|standardUser'], function()
{
	Route::get('userProtected', 'StandardUserController@getUserProtected');
	Route::resource('profiles', 'UsersController', ['only' => ['show', 'edit', 'update']]);
});

# Solo Admin
Route::group(['prefix' => '/admin','before' => 'auth|admin'], function()
{
	Route::get('/', ['as' => 'admin_dashboard', 'uses' => 'AdminController@getHome']);
	Route::get('/clinic', ['as' => 'admin_patient', 'uses' => 'AdminController@getClinic']);
    Route::resource('profiles', 'AdminUsersController', ['only' => ['index', 'show', 'edit', 'update', 'destroy']]);
	Route::get('/send', ['as' => 'admin_send', 'uses' => 'GcmController@send']);

});

App::missing(function($exception)
{
	  	if (Request::is('api/*'))
		{
		    return H::Mgs("Url Not Access");
		}else{
	    	return Response::view('errors.missing', array(), 404);
		}
});




 /*
 https://laracasts.com/lessons/caching-essentials
http://104.236.201.9/agendaMedica/api/appointment

 */
