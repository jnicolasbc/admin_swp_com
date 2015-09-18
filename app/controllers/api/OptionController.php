<?php namespace Api\V1;
use Doctor,Input;

class OptionController extends \Controller { 

	public function __construct()
	{
	}

	public function createPatientAso()
	{
			return Helpers::createPatientAso();
	}

	public function updatePatientAso()
	{
			return Helpers::updatePatientAso();
	}
	
	public function listPatientAso()
	{
			return Helpers::listPatientAso();
	}

	public function patientAso($id)
	{
			return Helpers::patientAso($id);
	}

	
	#Especialidad
	public function specialty()
	{		
		return Helpers::specialty();
	}

	public function specialty2()
	{		
		return Helpers::specialty2();
	}

	public function specialty3()
	{		
		return Helpers::specialty3();
	}

	public function insurance()
	{		
		$pais = Input::get('country');		
		return Helpers::insurance($pais);
	}
	public function insurance2()
	{		
		$pais = Input::get('country');		
		return Helpers::insurance2($pais);
	}

	public function insurance3()
	{		
		$pais = Input::get('country');		
		return Helpers::insurance3($pais);
	}

	public function searchDoctor()
	{	
		$pais = Input::get('country');
		$ciudad = Input::get('city');
		$especialidad = Input::get('specialty');
		$seguro = Input::get('insurance');
		$nombre = Input::get('name');
		$patient_id = Input::get('patient');
		return Helpers::searchDoctor($pais,$ciudad,$especialidad,$seguro,$nombre,$patient_id);
	}

	public function searchDoctorLocal()
	{	
		$doctor_id = Input::get('id');
		return Helpers::searchDoctorLocal($doctor_id);
	}

	public function doctorFavorites()
	{	
		$patient_id = Input::get('id');
		return Helpers::doctorFavorites($patient_id);
	}

	public function doctorFavoritesAdd()
	{	
		$patient_id = Input::get('patient_id');
		$business_id = Input::get('business_id');
		return Helpers::doctorFavoritesAdd($patient_id,$business_id);
	}

	public function doctorFavoritesDel()
	{	
		$patient_id = Input::get('patient_id');
		$business_id = Input::get('business_id');
		return Helpers::doctorFavoritesDel($patient_id,$business_id);
	}
	

	public function showMyAppointments()
	{
		$date= Input::get('date');
		$doctor= Input::get('doctor');
		$bussines= Input::get('bussines');
		return Helpers::appointmentTimes($date, $doctor, $bussines); 
	}

	
}