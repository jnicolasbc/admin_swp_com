<?php namespace Api\V1;
use Doctor;
class DoctorController extends \Controller { 

	public function __construct()
	{
		$this->beforeFilter('api.cache');
		$this->beforeFilter('api.patient');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Helpers::indexDoctor();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
		$p = Patient::find($id);
		if($p){
			return Helpers::Show($p);
		}else{
			return Helpers::Mgs("Error $id Incorrecto");
		}
	}
}