<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Cartalyst\Sentry\Users\Eloquent\User implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}

	public function getFullName()
	{
		return $this->first_name." ".$this->last_name;
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public static function boot()
    {
        self::$hasher = new Cartalyst\Sentry\Hashing\NativeHasher;
    }

    public function isCurrent()
    {
        if (!Sentry::check()) return false;

        return Sentry::getUser()->id == $this->id;
    }

    public function doctor()
	{
		return $this->belongsTo('Doctor');
	}

	public function clinic()
	{
		return $this->belongsTo('Clinic');
	}

	public function Profile()
	{
		return $this->belongsTo('Profile');
	}

	public function billings()
	{
		return $this->hasMany('Billing');
	}

	public function notifications()
	{
		return $this->hasMany('Notification');
	}

}
