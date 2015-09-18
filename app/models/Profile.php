<?php

class Profile extends Eloquent {

	protected $table = 'profiles';
	public $timestamps = true;

	public function user()
	{
		return $this->hasOne('User');
	}

	public function address()
	{
		return $this->hasOne('Address');
	}
    
    public static function  picture()
    {
      $user = Sentry::getUser();
      $profile = Profile::where('user_id', $user->id)->first();
      return $profile->picture;
    }
}