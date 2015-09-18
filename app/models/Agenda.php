<?php

class Agenda extends Eloquent {

	protected $table = 'agendas';
	public $timestamps = true;

	public function configDay()
	{
		return $this->hasOne('Configday');
	}

	public function customDay()
	{
		return $this->hasOne('CustomDay');
	}

	public function business()
	{
		return $this->hasOne('Business');
	}

	public function appointments()
	{
		return $this->belongsToMany('Appointment');
	}

	public static function getVeryAgenda($agenda_id)
	{
		$user = Sentry::getUser();
		$doctor = Doctor::where('user_id', $user->id)->first();
		$agenda = Agenda::find($agenda_id);
		if($agenda->doctor_id == $doctor->id)
	       return true;
	    else
	       return false;
	}

}