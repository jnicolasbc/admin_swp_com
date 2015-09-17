<?php

class Doctor extends Eloquent {

	protected $table = 'doctors';
	public $timestamps = true;

	public function user()
	{
		return $this->hasOne('User');
	}

	public function appointments()
	{
		return $this->hasMany('Appointment');
	}

	public function business()
	{
		return $this->hasMany('Business');
	}

	public function patients()
	{
		return $this->belongsToMany('Patient');
	}

	public function specialty()
	{
		return $this->belongsTo('Specialty');
	}

	public static function doctorLogin()
    {
        $user    = Sentry::getUser();
        $doctors = Doctor::where('user_id',$user->id)->first();
        return $doctors->id;
    }

    public static function VeryTypeDoctor()
    {
        if($user = Sentry::getUser()){
            $doctors = Doctor::where('user_id',$user->id)->first();
            if($doctors){
                if($doctors->clinic_id > 0)
                    return true;
            }
        }
    }

}