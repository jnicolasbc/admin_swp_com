<?php

class Address extends Eloquent {

	protected $table = 'addresses';
	public $timestamps = false;

	public function city()
	{
		return $this->belongsTo('City');
	}

}