<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Group extends Eloquent {

	protected $table = 'groups';
	public $timestamps = true;

	use SoftDeletingTrait;

	protected $dates = ['deleted_at'];

	public function users()
	{
		return $this->belongsToMany('User', 'users_groups');
	}

}