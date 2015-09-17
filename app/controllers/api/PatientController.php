<?php namespace Api\V1;
use Patient;
class PatientController extends \Controller { 


	public function __construct()
	{
		$this->beforeFilter('api.patient');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{



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


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
		
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		

	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function showMyAppointments($id)
	{
		return $id." . ";
		$citas = Appointment::find($id);
	}
	
}