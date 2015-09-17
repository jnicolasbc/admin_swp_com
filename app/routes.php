<?php
use \Api\V1\Helpers as H;

$lang_c=Cookie::get('language');	   
$l = (is_null($lang_c)) ? Config::get('app.locale') : Cookie::get('language');
App::setLocale($l);

# CSRF Protection
Route::when('admin/*', 'csrf', ['POST', 'PUT', 'PATCH', 'DELETE']);




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

Route::get('/truncate',function()
{	
	DB::table('users')->truncate();
	DB::table('profiles')->truncate();
	DB::table('clinics')->truncate();
	DB::table('addresses')->truncate();
	DB::table('users_groups')->truncate();
	DB::table('user_payment')->truncate();
	DB::table('payment')->truncate();
	return 'Ok';
});

Route::get('/email',function()
{	
	$subject = 'Activacion | smartdoctorappointments.com';
    $name  = 'Rolando';
    $email = 'el_del_74@hotmail.es';
     $code_activation = str_random(15);
    $data = array(
       'id'   => 1,
       'name' => 'Rolando Arias',
       'code' => $code_activation
    );

    Mail::send('email.activation',  $data, function($message) use ($name, $email, $subject)
    {
        $message->to($email, $name);
        $message->subject($subject);
    });
});

# Paginas Webs
Route::group(['prefix' => '/'], function()
{
	Route::get('/', ['as' => 'home', 'uses' => 'SessionsController@create']);
});

# Visitantes
Route::group(['prefix' => '/','before' => 'guest'], function()
{
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

});

# Autentificacion
Route::get('logout', ['as' => 'logout', 'uses' => 'SessionsController@destroy']);
Route::resource('sessions', 'SessionsController' , ['only' => ['create','store','destroy']]);

# Solo Clinics
Route::group(['prefix' => 'clinic','before' => 'auth'], function()
{
	Route::get('/', ['as' => 'Agenda_Today', 'uses' => 'ClinicController@getAgendasDay']);
	Route::resource('doctors', 'ClinicDoctorsController'); 
	Route::controller('editable','ClinicEditableController');
	Route::resource('users','ClinicUsersController');
    Route::post('patients-add', 'ClinicDoctorsController@postaddPatient');
    Route::get('patients', 'ClinicController@getPatients');
    Route::get('admin-profile', 'ClinicController@getAdminProfile');
    Route::post('admin-profile/save', 'ClinicController@postAdminProfile');
    Route::get('agendas', 'ClinicController@getAgendas');
    Route::get('agendas-day', 'ClinicController@getAgendasDay');  
    Route::post('patients/save', 'ClinicDoctorsController@postaddAppointments');
    Route::get('confirmation-appointments', 'ClinicController@getConfirmationAppointments');
    Route::get('confirmation-appointment', 'ClinicController@getConfirmationAppointment');
    Route::get('cancel-appointment', 'ClinicController@getCancelAppointment');
    Route::get('cancels-appointments', 'ClinicController@getCancelsAppointments');
    Route::get('doctor-status', 'ClinicDoctorsController@getDoctorStatus');
    Route::get('doctor-confirm', 'ClinicDoctorsController@getDoctorConfirm');

    Route::get('agenda/{doctor_id}/patients', 'ClinicDoctorsController@getDoctorPatients');
    
    Route::get('agendas-day/doctor/{id}', 'ClinicDoctorsController@getDoctorAgenda');
    Route::get('agenda/appointments/{id}', 'ClinicDoctorsController@getDoctorAppointments');
    Route::get('doctor/appointments/editar', 'ClinicDoctorsController@getEditAppointments');
    Route::get('doctor/appointment/editar-ajax', 'ClinicDoctorsController@getEditAjaxAppointments');
	Route::resource('doctor/custom-days', 'ClinicDoctorsCustomDaysController');
	Route::get('doctor/{id}/config-days', 'ClinicDoctorsController@getConfigDay');
	Route::post('doctor/config-days/save', 'ClinicDoctorsController@postConfigDaySave');
	Route::post('doctor/config-days/edit', 'ClinicDoctorsController@postConfigDayEdit');
	Route::get('doctor/{doctor_id}/patient/{patien_id}/appointments-pending', 'ClinicDoctorsController@getPatientAppointments');
	Route::get('doctor/patient/appointments-pending/editar', 'ClinicDoctorsController@getDoctorPatientsAppointmentsEdit');
	Route::get('doctor/{doctor_id}/patient/{patien_id}/appointments-history', 'ClinicDoctorsController@getPatientAppointmentsHistory');
    /*-----------------------------------Payment----------------------------------------*/
	Route::get('payment/history', 'ClinicPaymentController@getClinicHistory');
    /*-----------------------------------Payment----------------------------------------*/
	Route::get('config-data', 'ClinicController@getConfig');
	Route::post('config-data/save', 'ClinicController@postConfigSave');
});

# Solo Doctores
Route::group(['prefix' => 'doctor','before' => 'auth|doctor'], function()
{
	Route::get('/', ['as' => 'doctor_dashboard', 'uses' => 'DoctorController@getHome']);
	Route::resource('patients', 'PatientsController');
	Route::resource('appointments', 'AppointmentsController');
	Route::get('agenda-day', 'AppointmentsController@getAgendaDay');
	Route::get('patient/{patien_id}/appointments-pending', 'PatientsController@getPatientAppointments');
	Route::get('patient/appointments-pending/editar', 'ClinicDoctorsController@getDoctorPatientsAppointmentsEdit');
	Route::get('patient/{patien_id}/appointments-history', 'PatientsController@getPatientAppointmentsHistory');
    Route::get('appointment/editar-ajax', 'ClinicDoctorsController@getEditAjaxAppointments');
	Route::get('appointment/mesage', 'AppointmentsController@getmessage');
    /*-----------------------------------Configuracion de Horarios----------------------------------------*/
    Route::get('config-days', 'PatientsController@getConfigDay');
	Route::post('config-days/save', 'PatientsController@postConfigDaySave');
	Route::post('config-days/edit', 'PatientsController@postConfigDayEdit');
	/*-----------------------------------Configuracion de dias especiales----------------------------------------*/
	Route::resource('custom-days', 'DoctorsCustomDaysController');
    /*-----------------------------------perfil----------------------------------------*/
	Route::resource('profile', 'DoctorController');
    /*-----------------------------------configuracion----------------------------------------*/
	Route::get('config-data', 'DoctorController@getConfig');
	Route::get('config-data/save', 'DoctorController@getConfigSave');
    /*-----------------------------------Payment----------------------------------------*/
	Route::get('payment/history', 'PaymentController@getDoctorHistory');
	/*----------------------------confirmacion y o no aceptacion de citas---------------*/
	Route::get('confirmation-appointments', 'AppointmentsController@getConfirmationAppointments');
    Route::get('confirmation-appointment', 'AppointmentsController@getConfirmationAppointment');
    Route::get('cancel-appointment', 'AppointmentsController@getCancelAppointment');
    Route::get('cancels-appointments', 'AppointmentsController@getCancelsAppointments');

    ///////////////////////////////Doctor Individual-------------/////////////////
    Route::resource('agendas', 'AgendasController'); 
    Route::get('agenda/{id}/patients', 'AgendasController@getAgendaPatients'); 
    Route::get('agenda/{agenda_id}/patient/{patient_id}/appointments-pending', 'AgendasController@getAppointmentsPending'); 
    Route::get('agenda/{agenda_id}/patient/{patient_id}/appointments-history', 'AgendasController@getAppointmentsHistory'); 
    Route::get('agenda/{agenda_id}/today', 'AgendasController@getAppointmentsToday'); 
    Route::get('agenda/{agenda_id}/appointments', 'AgendasController@getAppointments'); 

});

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
