<?php namespace Api\V1;
use DB,Input,Response;
use DoctorPatient,MgsAppointment,User,Patient,Country,City, Doctor,Insurance,Business,Agenda,Configday,CustomDay,Appointment;

class AppointmentController extends \Controller { 


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
	 
		
		if(Input::get('date')==''){
			$date = date('Y-m-d');
		}else{
			$date=Input::get('date');
		}
		$patient_id = Input::get('id');
		
		$citas = Appointment::where('patient_id',$patient_id)->get();
		#return Response::json($citas);
		/* Arreglar las conecciones de doctores unicos y docotres que perteneces aun clinica */
		$states = array('pending','confirmed');
		$datos = DB::table('appointments') 
			   ->Join('agendas', 'agendas.id', '=', 'appointments.agenda_id')		
			   ->Join('business_clinics', 'business_clinics.agenda_id', '=', 'agendas.id')
			   ->Join('addresses', 'addresses.id', '=', 'business_clinics.addresses_id')
			   ->Join('doctors', 'doctors.id', '=', 'business_clinics.doctor_id')
			   ->Join('patients', 'patients.id', '=', 'appointments.patient_id')
			   ->Join('users', 'users.id', '=', 'patients.user_id')
			   ->where('patients.id','=',$patient_id)
			   ->where('appointments.day','=',$date)			   			   
			   ->whereIN('appointments.state',$states)			   			   
			   ->select('patients.id as patient_id',
			   			DB::raw('CONCAT(first_name," ",last_name) as patient_name'),
			   			DB::raw('CONCAT(first_name,"_",last_name) as name_full'),
			   			'addresses.my_address as address',
			   			'appointments.id as appointments_id',
			   			'appointments.day as date',
			   			'users.id as user_id',
			   			'doctors.id as doctor_id',
			   			'appointments.state as state_appointment',
			   			'appointments.start_date as hour_appointment',
			   			'appointments.end_date as hour_stimate',
			   			'appointments.last_time as last_update')
			   ->orderBy('patients.id','Asc')			 
			   ->get();

		$c = count($datos);

			   $array=array();
			   foreach ($datos as $d) {
			   		$doc_user = Doctor::find($d->doctor_id)->user_id;
			   		$doc = User::find($doc_user);
			   		$doctor_name = $doc->first_name." ".$doc->last_name;
			   		$messages_all = DB::table('mgs_appointment')->where('appointment_id',$d->appointments_id)->get();
			   		list($asd1,$hour_appointment) = explode(' ',$d->hour_appointment);
			   		list($asd2,$hour_stimate) = explode(' ',$d->hour_stimate);
			   		list($asd3,$last_update) = explode(' ',$d->last_update);
			   		list($hours,$minutes,$s) = explode(':',$hour_appointment);
			   		list($hours2,$minutes2,$s2) = explode(':',$hour_stimate);
			   		list($hours3,$minutes3,$s3) = explode(':',$last_update);

			   		$hour_appointment=$hours.":".$minutes;
			   		$hour_stimate=$hours2.":".$minutes2;
			   		$last_update=$hours3.":".$minutes3;
			   		$name_full=$d->name_full;
			   		$citas = array(
			   				'patient_id'=>$d->patient_id,
			   				'patient_name'=>$d->patient_name,
			   				'appointments_id'=>$d->appointments_id,			   				
			   				'address'=>$d->address,
							'date'=>$d->date,
							'state_appointment'=>$d->state_appointment,
							'hour_appointment'=>$hour_appointment,
							'hour_stimate'=>$hour_stimate,
							'last_update'=>$last_update,
			   				'doctor_name'=>$doctor_name,
			   				'doctor_phone'=>'999-8989-200',
			   				'messages'=>$messages_all
			   			);
			   	
			   	$name_full = $d->patient_name;

			   	$citas_full = 'p_citas_'.$d->patient_id;
			   	$name_id = 'p_name_'.$d->patient_id;


			   	$array[$name_id]=$name_full;

			   
			   	if (array_key_exists($citas_full, $array)) {
					   	$array[$citas_full][]=$citas;  
					}else{
						$array[$citas_full][]=$citas; 
					}
				   
			   }
		$datos = DB::table('appointments') 
			   ->Join('agendas', 'agendas.id', '=', 'appointments.agenda_id')		
			   ->Join('business_clinics', 'business_clinics.agenda_id', '=', 'agendas.id')
			   ->Join('addresses', 'addresses.id', '=', 'business_clinics.addresses_id')
			   ->Join('doctors', 'doctors.id', '=', 'business_clinics.doctor_id')
			   ->Join('patients', 'patients.id', '=', 'appointments.patient_id')
			   ->Join('users', 'users.id', '=', 'patients.user_id')
			   ->Join('profiles', 'profiles.user_id', '=', 'users.id')
			   ->where('patients.main','=',$patient_id)
			   ->where('appointments.day','=',$date)	
			    ->whereIN('appointments.state',$states)
			   ->select('patients.id as patient_id',
			   			DB::raw('CONCAT(first_name," ",last_name) as patient_name'),
			   			DB::raw('CONCAT(first_name,"_",last_name) as name_full'),			   			
			   			'addresses.my_address as address',
			   			'appointments.id as appointments_id',
			   			'appointments.day as date',
			   			'doctors.id as doctor_id',
			   			'profiles.phone as doctor_phone',
			   			'appointments.state as state_appointment',
			   			'appointments.start_date as hour_appointment',
			   			'appointments.end_date as hour_stimate',
			   			'appointments.last_time as last_update')
			   ->orderBy('patients.id','Asc')			 
			   ->get();

		$cc = count($datos);

		 
		$array2=array();
			   foreach ($datos as $d) {
			   		$doc_user = Doctor::find($d->doctor_id)->user_id;
			   		$doc = User::find($doc_user);
			   		$doctor_name = $doc->first_name." ".$doc->last_name;
			   		$messages_all = DB::table('mgs_appointment')->where('appointment_id',$d->appointments_id)->get();
			   		list($asd1,$hour_appointment) = explode(' ',$d->hour_appointment);
			   		list($asd2,$hour_stimate) = explode(' ',$d->hour_stimate);
			   		list($asd3,$last_update) = explode(' ',$d->last_update);
			   		list($hours,$minutes,$s) = explode(':',$hour_appointment);
			   		list($hours2,$minutes2,$s2) = explode(':',$hour_stimate);
			   		list($hours3,$minutes3,$s3) = explode(':',$last_update);
			   		$hour_appointment=$hours.":".$minutes;
			   		$hour_stimate=$hours2.":".$minutes2;
			   		$last_update=$hours3.":".$minutes3;
			   		$name_full=$d->name_full;
			   		
			   		$citas = array(
			   			'patient_id'=>$d->patient_id,
			   			'patient_name'=>$d->patient_name,
			   			'appointments_id'=>$d->appointments_id,
			   			'address'=>$d->address,
						'date'=>$d->date,
						'state_appointment'=>$d->state_appointment,
						'hour_appointment'=>$hour_appointment,
						'hour_stimate'=>$hour_stimate,
						'last_update'=>$last_update,
			   			'doctor_name'=>$doctor_name,
			   			'doctor_phone'=>$d->doctor_phone,
			   			'messages'=>$messages_all
			   		);

				   	$name_full = $d->patient_name;

			   	$citas_full = 'p_citas_'.$d->patient_id;
			   	$name_id = 'p_name_'.$d->patient_id;
			   	$array2[$name_id]=$name_full;

			   
			   	if (array_key_exists($citas_full, $array2)) {					   	
						
						$array2[$citas_full][]=$citas;  
					}else{						
						$array2[$citas_full][]=$citas; 

	 			}			   
		}
		$h = $c+$cc;

		$array3=array();

		$array3 = array_merge($array,$array2); 

		$array_c = array();
		$array_c['count']=$h;

		$array5 = array();
		$array5 = array_merge($array_c,$array3);

		return Response::json($array5);
	}

	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 *
	 */
	public function store()
	{
		$bussines_id = Input::get('bussines');
		$patient_id = Input::get('patient_id');
		$day = Input::get('day');
		$reason = Input::get('reason');
		$start_day= $day." ".Input::get('start_date'); 
		$end_day= $day." ".Input::get('end_date'); 
		$buss = Business::find($bussines_id);
		$agenda_id = $buss->agenda_id;
		$doctor_id = $buss->doctor_id;
		
		$agenda = Agenda::find($agenda_id);

		$patient_doctor=DoctorPatient::where('patient_id',$patient_id)->where('doctor_id',$doctor_id)->first();
		
		if(!$patient_doctor){
			$add = new DoctorPatient;
			$add->patient_id=$patient_id;
			$add->doctor_id=$doctor_id;
			$add->save();
		}
		
		$cita = new Appointment;
		$cita->patient_id=$patient_id; 		
		$cita->agenda_id=$agenda_id; 
		$cita->day=$day; 
		$cita->start_date = $start_day;
		$cita->last_time  = $start_day;
		$cita->end_date=$end_day;
		$cita->reason=$reason;	
		if($agenda->appointment_state){
			$state = true;
			$cita->state='pending';
		}else{
			$state = false;
			$cita->state='confirmed';
		}	
		$cita->save();

		
		if($cita){
			$mgs = array('txt'=>'Success Appointment','state'=>$state);
		    return Helpers::Mgs($mgs);
		}else{
			$mgs = array('txt'=>'Error Save');
		    return Helpers::Mgs($mgs);
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		 
		$patient_id = Input::get('id');
		
		$datos = DB::table('appointments') 
			   ->Join('agendas', 'agendas.id', '=', 'appointments.agenda_id')		
			   ->Join('business_clinics', 'business_clinics.agenda_id', '=', 'agendas.id')
			   ->Join('doctors', 'doctors.id', '=', 'business_clinics.doctor_id')
			   ->Join('addresses', 'addresses.id', '=', 'business_clinics.addresses_id')
			   ->Join('patients', 'patients.id', '=', 'appointments.patient_id')
			   ->Join('users', 'users.id', '=', 'patients.user_id')
			   ->Join('profiles', 'profiles.user_id', '=', 'users.id')
			   ->where('appointments.id',$id)
			   ->select('patients.id as patient_id',
			   			DB::raw('CONCAT(first_name," ",last_name) as patient_name'),
			   			'addresses.my_address as address',
			   			'appointments.id as appointments_id',
			   			'appointments.day as date',
			   			'doctors.id as doctor_id',
			   			'profiles.phone as doctor_phone',
			   			'appointments.state as state_appointment',
			   			'appointments.start_date as hour_appointment',
			   			'appointments.end_date as hour_stimate',
			   			'appointments.last_time as last_update')
			   ->get();

			   $array=array();
			   foreach ($datos as $d) {
			   		$doc_user = Doctor::find($d->doctor_id)->user_id;
			   		$doc = User::find($doc_user);
			   		$doctor_name = $doc->first_name." ".$doc->last_name;
			   		$messages_all = DB::table('mgs_appointment')->where('appointment_id',$d->appointments_id)->get();
			   		list($asd1,$hour_appointment) = explode(' ',$d->hour_appointment);
			   		list($asd2,$hour_stimate) = explode(' ',$d->hour_stimate);
			   		list($asd3,$last_update) = explode(' ',$d->last_update);
			   		list($hours,$minutes,$s) = explode(':',$hour_appointment);
			   		list($hours2,$minutes2,$s2) = explode(':',$hour_stimate);
			   		list($hours3,$minutes3,$s3) = explode(':',$last_update);

			   		$hour_appointment=$hours.":".$minutes;
			   		$hour_stimate=$hours2.":".$minutes2;
			   		$last_update=$hours3.":".$minutes3;

			   			$citas = array(
			   				'patient_id'=>$d->patient_id,
			   				'patient_name'=>$d->patient_name,
			   				'address'=>$d->address,
							'date'=>$d->date,
							'state_appointment'=>$d->state_appointment,
							'hour_appointment'=>$hour_appointment,
							'hour_stimate'=>$hour_stimate,
							'last_update'=>$last_update,
			   				'doctor_name'=>$doctor_name,
			   				'doctor_phone'=>$d->doctor_phone,
			   				'messages'=>$messages_all
			   			);
			   	$array[]=$citas;
			   }
			   
		return Response::json($array);
		 
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
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		
	}

	public function historyBack()
	{	
		$date = date('Y-m-d');		
		$patient_id = Input::get('id');
		$pp = Patient::find($patient_id);
		$citas = Appointment::where('patient_id',$patient_id)->get();

		if($pp->main=='0'){
			$datos = DB::table('appointments') 
			   ->Join('agendas', 'agendas.id', '=', 'appointments.agenda_id')		
			   ->Join('business_clinics', 'business_clinics.agenda_id', '=', 'agendas.id')
			   ->Join('addresses', 'addresses.id', '=', 'business_clinics.addresses_id')
			   ->Join('doctors', 'doctors.id', '=', 'business_clinics.doctor_id')
			   ->Join('patients', 'patients.id', '=', 'appointments.patient_id')
			   ->Join('users', 'users.id', '=', 'patients.user_id')
			   ->Join('profiles', 'profiles.user_id', '=', 'users.id')
			   ->where('appointments.day','<',$date)			   
			   ->where('patients.id',$patient_id)
			   ->select('patients.id as patient_id',
			   			DB::raw('CONCAT(first_name," ",last_name) as patient_name'),
			   			'addresses.my_address as address',
			   			'appointments.id as appointments_id',
			   			'appointments.day as date',
			   			'doctors.id as doctor_id',
			   			'profiles.phone as doctor_phone',
			   			'appointments.state as state_appointment',
			   			'appointments.start_date as hour_appointment',
			   			'appointments.end_date as hour_stimate',
			   			'appointments.last_time as last_update')
			   ->get();
			  
			   $array=array();
			   foreach ($datos as $d) {
			   		$doc_user = Doctor::find($d->doctor_id)->user_id;
			   		$doc = User::find($doc_user);
			   		$doctor_name = $doc->first_name." ".$doc->last_name;
			   		$messages_all = DB::table('mgs_appointment')->where('appointment_id',$d->appointments_id)->get();
			   		list($asd1,$hour_appointment) = explode(' ',$d->hour_appointment);
			   		list($asd2,$hour_stimate) = explode(' ',$d->hour_stimate);
			   		list($asd3,$last_update) = explode(' ',$d->last_update);
			   		list($hours,$minutes,$s) = explode(':',$hour_appointment);
			   		list($hours2,$minutes2,$s2) = explode(':',$hour_stimate);
			   		list($hours3,$minutes3,$s3) = explode(':',$last_update);

			   		$hour_appointment=$hours.":".$minutes;
			   		$hour_stimate=$hours2.":".$minutes2;
			   		$last_update=$hours3.":".$minutes3;
			   		
			   		$citas = array(
			   				'appointment_id'=>$d->appointments_id,
			   				'patient_id'=>$d->patient_id,
			   				'patient_name'=>$d->patient_name,
			   				'address'=>$d->address,
							'date'=>$d->date,
							'state_appointment'=>$d->state_appointment,
							'hour_appointment'=>$hour_appointment,
							'hour_stimate'=>$hour_stimate,
							'last_update'=>$last_update,
			   				'doctor_name'=>$doctor_name,
			   				'doctor_phone'=>$d->doctor_phone,
			   				'messages'=>$messages_all
			   			);
			   	$array[]=$citas;
			   }
		}else{
			$datos = DB::table('appointments') 
			   ->Join('agendas', 'agendas.id', '=', 'appointments.agenda_id')		
			   ->Join('business_clinics', 'business_clinics.agenda_id', '=', 'agendas.id')
			   ->Join('addresses', 'addresses.id', '=', 'business_clinics.addresses_id')
			   ->Join('doctors', 'doctors.id', '=', 'business_clinics.doctor_id')
			   ->Join('patients', 'patients.id', '=', 'appointments.patient_id')
			   ->Join('users', 'users.id', '=', 'patients.user_id')
			   ->Join('profiles', 'profiles.user_id', '=', 'users.id')
			   ->where('appointments.day','<',$date)			   
			   ->where('patients.id',$patient_id)
			   ->OrWhere('patients.main',$patient_id)
			   ->select('patients.id as patient_id',
			   			DB::raw('CONCAT(first_name," ",last_name) as patient_name'),
			   			'addresses.my_address as address',
			   			'appointments.id as appointments_id',
			   			'appointments.day as date',
			   			'doctors.id as doctor_id',
			   			'profiles.phone as doctor_phone',			   			
			   			'appointments.state as state_appointment',
			   			'appointments.start_date as hour_appointment',
			   			'appointments.end_date as hour_stimate',
			   			'appointments.last_time as last_update')
			   ->get();
			   $array=array();
			   foreach ($datos as $d) {
			   		$doc_user = Doctor::find($d->doctor_id)->user_id;
			   		$doc = User::find($doc_user);
			   		$doctor_name = $doc->first_name." ".$doc->last_name;
			   		$messages_all = DB::table('mgs_appointment')->where('appointment_id',$d->appointments_id)->get();
			   		list($asd1,$hour_appointment) = explode(' ',$d->hour_appointment);
			   		list($asd2,$hour_stimate) = explode(' ',$d->hour_stimate);
			   		list($asd3,$last_update) = explode(' ',$d->last_update);
			   		list($hours,$minutes,$s) = explode(':',$hour_appointment);
			   		list($hours2,$minutes2,$s2) = explode(':',$hour_stimate);
			   		list($hours3,$minutes3,$s3) = explode(':',$last_update);

			   		$hour_appointment=$hours.":".$minutes;
			   		$hour_stimate=$hours2.":".$minutes2;
			   		$last_update=$hours3.":".$minutes3;
			   		
			   		$citas = array(
			   				'appointment_id'=>$d->appointments_id,
			   				'patient_id'=>$d->patient_id,
			   				'patient_name'=>$d->patient_name,
			   				'address'=>$d->address,
							'date'=>$d->date,
							'state_appointment'=>$d->state_appointment,
							'hour_appointment'=>$hour_appointment,
							'hour_stimate'=>$hour_stimate,
							'last_update'=>$last_update,
			   				'doctor_name'=>$doctor_name,
			   				'doctor_phone'=>$d->doctor_phone,
			   				'messages'=>$messages_all
			   			);
			   	$array[]=$citas;
			   }
		}
		
		#return Response::json($citas);
		
		return Response::json($array);
	}

	public function historyFuture()
	{
		$type = Input::get('id');
		$date = date('Y-m-d');
		$patient_id = Input::get('id');
		$citas = Appointment::where('patient_id',$patient_id)->get();
		$pp = Patient::find($patient_id);

		if($pp->main=='0'){
			$datos = DB::table('appointments') 
			   ->Join('agendas', 'agendas.id', '=', 'appointments.agenda_id')		
			   ->Join('business_clinics', 'business_clinics.agenda_id', '=', 'agendas.id')
			   ->Join('addresses', 'addresses.id', '=', 'business_clinics.addresses_id')
			   ->Join('doctors', 'doctors.id', '=', 'business_clinics.doctor_id')
			   ->Join('patients', 'patients.id', '=', 'appointments.patient_id')
			   ->Join('users', 'users.id', '=', 'patients.user_id')
			   ->Join('profiles', 'profiles.user_id', '=', 'users.id')
			   ->where('appointments.day','>',$date)			   
			   ->where('patients.id',$patient_id)
			   ->select('patients.id as patient_id',
			   			DB::raw('CONCAT(first_name," ",last_name) as patient_name'),
			   			'addresses.my_address as address',
			   			'appointments.id as appointments_id',
			   			'appointments.day as date',
			   			'doctors.id as doctor_id',
			   			'profiles.phone as doctor_phone',			   			
			   			'appointments.state as state_appointment',
			   			'appointments.start_date as hour_appointment',
			   			'appointments.end_date as hour_stimate',
			   			'appointments.last_time as last_update')
			   ->get();
			   $array=array();
			   foreach ($datos as $d) {
			   		$doc_user = Doctor::find($d->doctor_id)->user_id;
			   		$doc = User::find($doc_user);
			   		$doctor_name = $doc->first_name." ".$doc->last_name;
			   		$messages_all = DB::table('mgs_appointment')->where('appointment_id',$d->appointments_id)->get();
			   		list($asd1,$hour_appointment) = explode(' ',$d->hour_appointment);
			   		list($asd2,$hour_stimate) = explode(' ',$d->hour_stimate);
			   		list($asd3,$last_update) = explode(' ',$d->last_update);
			   		list($hours,$minutes,$s) = explode(':',$hour_appointment);
			   		list($hours2,$minutes2,$s2) = explode(':',$hour_stimate);
			   		list($hours3,$minutes3,$s3) = explode(':',$last_update);

			   		$hour_appointment=$hours.":".$minutes;
			   		$hour_stimate=$hours2.":".$minutes2;
			   		$last_update=$hours3.":".$minutes3;
			   		
			   		$citas = array(
			   				'appointment_id'=>$d->appointments_id,			   			
			   				'patient_id'=>$d->patient_id,
			   				'patient_name'=>$d->patient_name,
			   				'address'=>$d->address,
							'date'=>$d->date,
							'state_appointment'=>$d->state_appointment,
							'hour_appointment'=>$hour_appointment,
							'hour_stimate'=>$hour_stimate,
							'last_update'=>$last_update,
			   				'doctor_name'=>$doctor_name,
			   				'doctor_phone'=>$d->doctor_phone,
			   				'messages'=>$messages_all
			   			);
			   	$array[]=$citas;
			   }
		
		}else{

			$datos = DB::table('appointments') 
			   ->Join('agendas', 'agendas.id', '=', 'appointments.agenda_id')		
			   ->Join('business_clinics', 'business_clinics.agenda_id', '=', 'agendas.id')
			   ->Join('addresses', 'addresses.id', '=', 'business_clinics.addresses_id')
			   ->Join('doctors', 'doctors.id', '=', 'business_clinics.doctor_id')
			   ->Join('patients', 'patients.id', '=', 'appointments.patient_id')
			   ->Join('users', 'users.id', '=', 'patients.user_id')
			   ->Join('profiles', 'profiles.user_id', '=', 'users.id')
			   ->where('appointments.day','>',$date)			   
			   ->where('patients.id',$patient_id)
			   ->OrWhere('patients.main',$patient_id)
			   ->select('patients.id as patient_id',
			   			DB::raw('CONCAT(first_name," ",last_name) as patient_name'),
			   			'addresses.my_address as address',
			   			'appointments.id as appointments_id',
			   			'appointments.day as date',
			   			'doctors.id as doctor_id',
			   			'profiles.phone as doctor_phone',
			   			'appointments.state as state_appointment',
			   			'appointments.start_date as hour_appointment',
			   			'appointments.end_date as hour_stimate',
			   			'appointments.last_time as last_update')
			   ->get();
			   $array=array();
			   foreach ($datos as $d) {
			   		$doc_user = Doctor::find($d->doctor_id)->user_id;
			   		$doc = User::find($doc_user);
			   		$doctor_name = $doc->first_name." ".$doc->last_name;
			   		$messages_all = DB::table('mgs_appointment')->where('appointment_id',$d->appointments_id)->get();
			   		list($asd1,$hour_appointment) = explode(' ',$d->hour_appointment);
			   		list($asd2,$hour_stimate) = explode(' ',$d->hour_stimate);
			   		list($asd3,$last_update) = explode(' ',$d->last_update);
			   		list($hours,$minutes,$s) = explode(':',$hour_appointment);
			   		list($hours2,$minutes2,$s2) = explode(':',$hour_stimate);
			   		list($hours3,$minutes3,$s3) = explode(':',$last_update);

			   		$hour_appointment=$hours.":".$minutes;
			   		$hour_stimate=$hours2.":".$minutes2;
			   		$last_update=$hours3.":".$minutes3;
			   		
			   		$citas = array(
			   				'appointment_id'=>$d->appointments_id,			   			
			   				'patient_id'=>$d->patient_id,
			   				'patient_name'=>$d->patient_name,
			   				'address'=>$d->address,
							'date'=>$d->date,
							'state_appointment'=>$d->state_appointment,
							'hour_appointment'=>$hour_appointment,
							'hour_stimate'=>$hour_stimate,
							'last_update'=>$last_update,
			   				'doctor_name'=>$doctor_name,
			   				'doctor_phone'=>$d->doctor_phone,
			   				'messages'=>$messages_all
			   			);
			   	$array[]=$citas;
			   }
		}
		#return Response::json($citas);
		return Response::json($array);
	}

	public function stateAppo(){
		$id = Input::get('id');
		$cita = Appointment::find($id);
		$cita->state="canceled";
		$cita->save();

		$a= array('mgs'=>'All Ok');
		return Response::json($a);
	}

	public function updateState(){
		$id = Input::get('id');
		$state = Input::get('state');
		$cita = Appointment::find($id);
		$cita->state=$state;
		$cita->save();

		$a = array('mgs'=>'ok');
		return Response::json($a);
	}
 	

}