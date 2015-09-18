<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Patient extends Eloquent {

	protected $table = 'patients';
	public $timestamps = true;

	use SoftDeletingTrait;

	protected $dates = ['deleted_at'];

	public function appointments()
	{
		return $this->hasMany('Appointment');
	}

	public function doctors()
	{
		return $this->belongsToMany('Doctor');
	}

}