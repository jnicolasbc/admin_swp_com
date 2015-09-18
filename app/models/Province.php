<?php

class Province extends Eloquent {

	protected $table = 'provinces';
	public $timestamps = false;

	public function cities()
	{
		return $this->hasMany('City');
	}

	public function addresses()
	{
		return $this->hasMany('Address');
	}

}