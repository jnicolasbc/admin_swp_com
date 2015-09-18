<?php

class Billing extends Eloquent {

	protected $table = 'billings';
	public $timestamps = true;

	public function user()
	{
		return $this->belongsTo('User');
	}

}