<?php

class Clinic extends Eloquent {

	protected $table = 'clinics';
	public $timestamps = true;

	public function user()
	{
		return $this->hasOne('User');
	}

	public function doctors()
	{
		return $this->hasMany('Doctor');
	}

	public static function getClinicId()
	{
		$user = Sentry::getUser();
		$clinic = Clinic::where('user_id', $user->id)->first();
	    return $clinic->id;
	}

}