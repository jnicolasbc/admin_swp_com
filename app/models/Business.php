<?php

class Business extends Eloquent {

	protected $table = 'business_clinics';
	public $timestamps = false;

	public function cities()
	{
		return $this->hasMany('City');
	}

	public function agenda()
	{
		return $this->belongsTo('Agenda');
	}

    public static function doctorNegocios()
    {
        $user    = Sentry::getUser();
        $doctors = Doctor::where('user_id',$user->id)->first();
        $business = Business::where('doctor_id', $doctors->id)->get();
        if($business)
           return $business;
        else
           return false;
    }
}