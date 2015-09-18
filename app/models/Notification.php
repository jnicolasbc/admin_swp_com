<?php

class Notification extends Eloquent {

	protected $table = 'notifications';
	public $timestamps = true;

	public function user()
	{
		return $this->belongsTo('User');
	}

}