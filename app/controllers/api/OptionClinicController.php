<?php namespace Api\V1;
use DB,Input,Response;
use MgsAppointment,User,Patient,Country,City, Doctor,Insurance,Business,Agenda,Configday,CustomDay,Appointment;

class OptionClinicController extends \Controller { 


	public function __construct()
	{
		$this->beforeFilter('api.doctor');
	}

	 
	public function anyIndex()
	{ 
		return "ok";
	}

	public function anyCountDoctor($id)
	{ 
		 return Helpers::countDoctor($id);
	}

	public function anyListDoctor($id)
	{ 
		 return Helpers::listDoctor($id);
	}

	public function anyListAppointments($id)
	{ 
		 return Helpers::listAppointments($id);
	}

	public function anyAppointment($id)
	{ 
		 return Helpers::appointmentDoc($id);
	}

	public function anyInProgressAppointment()
	{ 		 
		 return Helpers::patientInProgress();
	}

	public function anySendMenssage()
	{
		$mgs= Input::get('mgs');
		$doctor= Input::get('doctor');
		$appointment= Input::get('appointment');
		return Helpers::sendMenssage($doctor,$appointment,$mgs); 
	}
 
 
}