<?php namespace Api\V1;
use DB,Input,Response;
use MgsAppointment,User,Patient,Country,City, Doctor,Insurance,Business,Agenda,Configday,CustomDay,Appointment;

class OptionDoctorController extends \Controller { 


	public function __construct()
	{
		$this->beforeFilter('api.doctor');
	}

	 
	public function anyIndex()
	{ 
		return "ok";
	}

	public function anyCountDoctor()
	{ 
		return "ok-doctor";
	}

 
 
}