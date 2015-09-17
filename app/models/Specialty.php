<?php

class Specialty extends Eloquent {

	protected $table = 'specialties';
	public $timestamps = true;

	public function doctors()
	{
		return $this->hasMany('Doctor');
	}

	
}