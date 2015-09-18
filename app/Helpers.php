<?php namespace Api\V1;
use Carbon\Carbon;
use Config,Input,DB,Response,Cookie,Session,Cache,ArrayObject;
use Specialty,Clinic,MgsAppointment,Sentry,User,Profile,Patient,Country,City, Doctor,Insurance,Business,Agenda,Configday,CustomDay,Appointment,BusinessFavorite;
class Helpers
{	

	public static function lang()
	{
		$lang_c=Cookie::get('language');	   
		$retVal = (is_null($lang_c)) ? Config::get('app.locale') : Cookie::get('language');
		return $retVal;
	}


	public static function date_mysql($date)
	{
		list($d,$m,$y)=explode('/',$date);
		$dt = Carbon::create($y,$m,$d,0,0,0);		
		return $dt->toDateString();
	}

	public static function hora_min($hora)
	{
		list($h,$m,$s)=explode(':',$hora);
			
		return $h.":".$m;
	}
	
	public static function Token($text,$length=5)
	{
    	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    		$a =  $text.substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    	return $a.str_random(10);
	}
	
	public static  function Mgs($msg="",$status=200)
	{
    	$json= array(
	    	'status'=>$status,
	    	'mgs'=>$msg	    	
		);
	    return Response::json($json);
	}
	
	public static function Insurances($i,$find=false,$id_insurance=0){
		//ver si ese doc pertenece a una clinica
		$idClinica = Doctor::find($i)->clinic_id;
			if($idClinica!='0'){				 
				  $lista_aseguradora = Clinic::find($idClinica)->insurances;		
			}else{
				$lista_aseguradora = Doctor::find($i)->insurances;	
			}

		if(!$find)
		{
			$a = explode(',', $lista_aseguradora);	
			foreach ($a as $id) {
				$name = Insurance::find($id);
				if($name){
						$resultado[]=array('id'=>$id,'name'=>$name->name);						
					}else{
						$resultado[]=array(0);
					}
			}
			return $resultado;		
		}else{
			if(stripos($lista_aseguradora, $id_insurance) !== false){
				#si encuantra
				$a = explode(',', $lista_aseguradora);	
				foreach ($a as $id) {
					$id = ltrim($id);
					$name = Insurance::find($id);
					if($name){
						$resultado[]=array('id'=>$id,'name'=>$name->name);						
					}else{
						$resultado[]=array(0);

					}
				}
				return $resultado;
			}else{
				#no encontro
				return false;
			}
		}
	}

	public static function insurance($id=0){
		 if($id!=0){
		 	$datos  = 	DB::table('insurances')
		 			->where('country_id',$id)
	   				->select('id','name as value')
	           		->get();
		 }else{
		   $datos  = 	DB::table('insurances')
	   				->select('id','name as value')
	           		->get();
	      }
		return Response::json($datos);    
	}

	public static function insurance2($id=0){
		 if($id!=0){
		 	$datos  = 	DB::table('insurances')
		 			->where('country_id',$id)
	   				->select('id as value','name as label')
	           		->get();
		 }else{
		   $datos  = 	DB::table('insurances')
	   				->select('id','name as value')
	           		->get();
	      }
		return Response::json($datos);    
	}

	public static function insurance3($id=0){
		 if($id!=0){
		 	$datos  = 	DB::table('insurances')
		 			->where('country_id',$id)
	   				->select('id as value','name as text')
	           		->get();
		 }else{
		   $datos  = 	DB::table('insurances')
	   				->select('id as value','name as text')	   				
	           		->get();
	      }
		return Response::json($datos);    
	}

	public static function is_favorite($bussines,$patient){

		$exist = DB::table('bussiness_favorite')
				->where('business_id',$bussines)
				->where('patient_id',$patient)
				->first();
		if($exist){
			return 1;
		}else{
			return 0;
		}
	}

	public static function createTokenUser($id_user)
	{
		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$a =  $id_user.substr(str_shuffle(str_repeat($pool, 5)), 0, 10);
    	$token= $a.str_random(10);
    	$expiraEl= Carbon::now()->addDay();#Carbon::now()->addDay()
		Cache::put('_token_', $token, $expiraEl);
		return $token;
	}
	
	# ESPECIALIDADES
	public static function specialty()
	{
	   $datos  = 	DB::table('specialties')
	   				->select('id','name_es as value')
	   				->remember(10)
	           		->get();
		  #$objs = (object)$array;
		return Response::json($datos);          	
	}

	public static function specialty2()
	{			
		if(Helpers::lang()=="es"){
	   		$datos  = 	DB::table('specialties')
	   				->select('id as value','name_es as label')
	   				->remember(10)
	           		->get();
	    }else{
	    	$datos  = 	DB::table('specialties')
	   				->select('id as value','name_en as label')
	   				->remember(10)
	           		->get();
		  #$objs = (object)$array;
	    }
		return Response::json($datos);          	
	}

	public static function specialty3()
	{			
		if(Helpers::lang()=="es"){
	   		$datos  = 	DB::table('specialties')
	   				->select('id as value','name_es as text')
	   				->remember(10)
	           		->get();
	    }else{
	    	$datos  = 	DB::table('specialties')
	   				->select('id as value','name_en as text')
	   				->remember(10)
	           		->get();
		  #$objs = (object)$array;
	    }
		return Response::json($datos);          	
	}

	/*
	 * PATIENT
	 */
	public static function createPatientAso(){
		//
		$first_name=Input::get('first_name');
		$last_name=Input::get('last_name');
		$date = Input::get('date');				 
		$insurance = Input::get('insurance');
		$dni = Input::get('dni');
		$main = Input::get('main');
		$base_64_picure = Input::get('picture');
		$email=$dni."@".$last_name."_".$first_name.".com";

		list($type, $base_64_picure) = explode(';', $base_64_picure);
		list(,$base_64_picure)  = explode(',', $base_64_picure);
		$base_64_picure = base64_decode($base_64_picure);

	    try
		{	    
		    // Create the user
		    $patient_main=Patient::find($main);
		    $patient_main_=$patient_main->user_id;

		    $profile_main= Profile::where('user_id',$patient_main_)->first();
		    $lang=$profile_main->lang;
		    $address_id = $profile_main->address_id;
		    $user = Sentry::getUserProvider()->create(array(
		        'email'    => $email,
		        'password'    => $email,
		        'first_name' => $first_name,
		        'last_name' => $last_name,
		        'activated' => 1,
		        'gcm_id' => null,
		        'dni' => $dni,
		    ));

		    $pa = new Patient;
		    $pa->user_id=$user->id;
		    $pa->insurance_id=$insurance;
		    $pa->main=$main;
		    $pa->save();
		    $str_random = str_random(10);
		    $al =public_path().'/assets/patient/images/profile_pic/'.$pa->id.'-'.$str_random.'.png';
		    file_put_contents($al, $base_64_picure);
		    
		    $profile = new Profile;
		    $profile->lang=$lang;
		    $profile->user_id=$user->id;
		    $profile->address_id=$address_id;
		    $profile->picture= '/assets/patient/images/profile_pic/'.$pa->id.'-'.$str_random.'.png';
		   	$profile->date=$date;
		   	$profile->save();

		    $mgs = array('txt'=>'Success registration');
		    return Helpers::Mgs($mgs);
		}
		catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
		    
		    return Helpers::Mgs("Login field is required.");
		}
		catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
		{
		    return Helpers::Mgs('Password field is required.');
		}
		catch (Cartalyst\Sentry\Users\UserExistsException $e)
		{
		    return Helpers::Mgs('User with this login already exists.');
		}
		catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
		{
		     return Helpers::Mgs('Group was not found.');
		}
	}

	public static function updatePatientAso(){

		$id=Input::get('patient_id');
		$first_name=Input::get('first_name');
		$last_name=Input::get('last_name');
		$date = Input::get('date');				 
		$insurance = Input::get('insurance');
		$dni = Input::get('dni');		
		$base_64_picures = Input::get('picture');

		 
		    // Create the user
		    $patientM=Patient::find($id);
		    $patientM->insurance_id=$insurance;
		    $patientM->save();

		    $patient=$patientM->user_id;

		    $profile_main= Profile::where('user_id',$patient)->first();

		    $user = User::find($patient);
		    $user->first_name = $first_name;
		    $user->last_name = $last_name;
		    $user->dni = $dni;
		    $user->save();
		    
 
		
		    $profile = Profile::find($profile_main->id);
		    if($base_64_picures!=''){
		    	list($type, $base_64_picure) = explode(';', $base_64_picures);		
				list(,$base_64_picure2)  = explode(',', $base_64_picure);	
				$base_64_picure3 = base64_decode($base_64_picure2);	
		    	$str_random = str_random(10);
			    $al =public_path().'/assets/patient/images/profile_pic/'.$patientM->id.'-'.$str_random.'.png';
			    file_put_contents($al, $base_64_picure3);
			    $profile->picture= '/assets/patient/images/profile_pic/'.$patientM->id.'-'.$str_random.'.png';
			   	$profile->date=$date;
			   	$profile->save();
			}else{
			   	$profile->date=$date;
			   	$profile->save();
			}
		    

		    $mgs = array('txt'=>'Success update Pactient asoc');
		    return Helpers::Mgs($mgs);
	}

	public static function listPatientAso(){
		//
		$main=Input::get('id');
		$datos = DB::table('users')
					       	->Join('profiles', 'profiles.user_id', '=', 'users.id')
					       	->Join('patients', 'patients.user_id', '=', 'users.id')
					       	->Join('insurances', 'insurances.id', '=', 'patients.insurance_id')
				           	->Join('addresses', 'addresses.id', '=', 'profiles.address_id')
				           	->Join('cities', 'cities.id', '=', 'addresses.city_id')
				           	->Join('provinces', 'provinces.id', '=', 'cities.province_id')
				           	->Join('countries', 'countries.id', '=', 'provinces.country_id')
					       	->where('patients.main',$main)
					       	->select('patients.id as patient_id',
					       			DB::raw('CONCAT(first_name," ",last_name) as patient_name'),
					       			'profiles.picture as picture',
					       			'profiles.phone as phone',
				           	        'insurances.id as insurance_id',
				           	        'insurances.name as insurance_name',
				           	        'countries.id as country_id',
				           	        'countries.name as country_name',
				           	        'cities.id as city_id',
				           	        'cities.name as city_name',
				           	        'addresses.id as address_id',
				           	        'addresses.my_address as address_name')
					       	->take(10)				       					       		
				       		->get();
				       		
		if(empty($datos)){
			return Response::json(array());
		}	
		return Response::json($datos);	       	
	}

	public static function patientAso($id){
		 
		$datos = DB::table('users')
					       	->Join('profiles', 'profiles.user_id', '=', 'users.id')
					       	->Join('patients', 'patients.user_id', '=', 'users.id')
					       	->Join('insurances', 'insurances.id', '=', 'patients.insurance_id')
					       	->where('patients.id',$id)
					       	->select('patients.id as patient_id',
					       			'first_name as name',
					       			'last_name as name_last',
					       			'users.dni as dni',
					       			'profiles.date as date',
					       			'profiles.picture as picture',
					       			'profiles.phone as phone',
				           	        'insurances.id as insurance_id',
				           	        'insurances.name as insurance_name')					       			       					       	
				       		->first();
		return Response::json($datos);	       	
	}
	
	public static function searchDoctor($pais,$ciudad,$especialidad,$seguro,$nombre,$patient)
	{
		$doctors = DB::table('doctors')
				->Join('users', 'users.id', '=', 'doctors.user_id')
				->Join('specialties', 'specialties.id', '=', 'doctors.specialty_id') 
				->Join('clinics', 'clinics.id', '=', 'doctors.clinic_id')
				->Join('business_clinics', 'business_clinics.doctor_id', '=', 'doctors.id')
				->Join('addresses', 'addresses.id', '=', 'business_clinics.addresses_id') 
				->Join('cities', 'cities.id', '=', 'addresses.city_id')
		       	->Join('provinces', 'provinces.id', '=', 'cities.province_id') 
				->Join('countries', 'countries.id', '=', 'provinces.country_id');

		 /*
		 $pais 
		 $ciudad  
		 $especialidad
		 $seguro
		 if($ciudad!=""){$patients = $patients->where('appointments.reason', 'LIKE', '%'.Input::get("motivo").'%'); }
		 */
		 if($pais!="0"){$doctors = $doctors->where('countries.id', $pais);}
        
        if($ciudad!="0"){$doctors = $doctors->where('cities.id', $ciudad);}

        if($especialidad!="0"){$doctors = $doctors->where('specialties.id', $especialidad);}
		
		$doctors = $doctors->where('doctors.state',1);

		$doctors = $doctors->select('doctors.id as id',
				   		DB::raw('CONCAT(first_name," ",last_name) as name'),
				   		'business_clinics.name as local',
				   		'business_clinics.id as local_id',
				   		'specialties.id as specialty_id',
				   		'specialties.name_es as specialty_name',
				   		'cities.id as city_id',
			 			'cities.name as city_name',
			  			'provinces.name as province',
			   			'countries.id as country_id',
			   			'countries.name as country_name',
			   			'addresses.id as address_id',
			   			'addresses.my_address as address_name')
				    	->take(10);		       	         
        $doctors = $doctors->orderBy('users.first_name')->get();

        $array=[];
		if($seguro!="0"){
			foreach ($doctors as $a) {
				$c = Helpers::Insurances($a->id,true,$seguro);
				$is_favorite= Helpers::is_favorite($a->local_id,$patient);
				if(!$c)	
				{
				    
				}else{
					$arrayobj = (array) $a;
					$arrayobj2 = array('insurances'=>$c,'favorite'=>$is_favorite);
				    $a = array_merge($arrayobj,$arrayobj2);				
				    $array2=array_merge($array,$a);
				    $array[]=$a;
				}
			}
		}else{
			foreach ($doctors as $doc) {
				$c = Helpers::Insurances($doc->id);	
				$is_favorite= Helpers::is_favorite($doc->local_id,$patient);
			    $arrayobj =(array) $doc;
				$arrayobj2 = array('insurances'=>$c,'favorite'=>$is_favorite);
			    $doc = array_merge($arrayobj,$arrayobj2);				
			    $array2=array_merge($array,$doc);
			    $array[]=$doc;
			}
		}

        
        #return Response::json($array);
		 return Response::json($array);
	}

	public static function doctorFavoritesAdd($patient_id,$business_id)
	{
		/* Si es doctor de clinica seria BussinessClinicsFavorite y si es Doctor unico BussinessDoctorFavorite */
		$exis = BusinessFavorite::where('patient_id',$patient_id)->where('business_id',$business_id)->first();
		if(!$exis){
			$favorito = new BusinessFavorite;
			$favorito->business_id = $business_id;
			$favorito->patient_id = $patient_id;
			$favorito->save();
			return Helpers::mgs('Doctor Update Favorite');
		}else{
			return Helpers::mgs('exists');			
		}
	}
	
	public static function doctorFavoritesDel($patient_id,$business_id)
	{
		$favorito = BusinessFavorite::where('patient_id',$patient_id)->where('business_id',$business_id);		
		$favorito->delete();
		return Helpers::mgs('Doctor Delete Favorite');
	}

	public static function doctorFavorites($patient_id)
	{
			/* Si es doctor de clinica seria BussinessClinicsFavorite y si es Doctor unico BussinessDoctorFavorite */		
			$url = url();
			$array =	DB::table('bussiness_favorite')
					       	->Join('business_clinics', 'business_clinics.id', '=', 'bussiness_favorite.business_id')
					       	->Join('addresses', 'addresses.id', '=', 'business_clinics.addresses_id')					       	
					       	->Join('patients', 'patients.id', '=', 'bussiness_favorite.patient_id')
					       	->Join('doctors', 'doctors.id', '=', 'business_clinics.doctor_id')
					       	->Join('users', 'users.id', '=', 'doctors.user_id')
					       	->Join('profiles', 'profiles.user_id', '=', 'users.id')
					       	->Join('cities', 'cities.id', '=', 'addresses.city_id')
					       	->Join('provinces', 'provinces.id', '=', 'cities.province_id')
					       	->Join('countries', 'countries.id', '=', 'provinces.country_id')
					       	->Where('patients.id',$patient_id)
				   			->select(
				   					'doctors.id as doctor_id',				   				
				   					DB::raw('CONCAT(first_name," ",last_name) as name'),
				   					'business_clinics.id as local_id',
				   					'business_clinics.name as local',
				   					'cities.id as city_id',
			 						'cities.name as city_name',
			  						'provinces.name as province',
			   						'countries.id as country_id',
			   						'countries.name as country_name',
			   						'addresses.id as address_id',
			   						'addresses.my_address as address_name',
			   						DB::raw('CONCAT("'.$url.'/",profiles.picture) as picure')
			   						)
				       		->get();
				    return Response::json($array);
	}
		
	public static function searchDoctorLocal($doctor_id)
	{
			$array =	DB::table('business_clinics')
					       	->Join('doctors', 'doctors.id', '=', 'business_clinics.doctor_id')
					       	->Join('cities', 'cities.id', '=', 'business_clinics.city_id')
					       	->Join('provinces', 'provinces.id', '=', 'cities.province_id')
					       	->Join('countries', 'countries.id', '=', 'provinces.country_id')
					       	->Join('addresses', 'addresses.id', '=', 'business_clinics.addresses_id')					       	
					       	->Where('doctors.id',$doctor_id)
				   			->select(				   					
				   					'business_clinics.name as local',
				   					'cities.id as city_id',
			 						'cities.name as city_name',
			  						'provinces.name as province',
			   						'countries.id as country_id',
			   						'countries.name as country_name',
			   						'addresses.id as address_id',
			   						'addresses.my_address as address_name')
				       		->get();
				    return Response::json($array);
	}

	public static function getDateString($date,$type){
		list($year,$month,$day)=explode('-', $date);
		$dt = Carbon::createFromDate($year,$month,$day,null);
		$fecha = $dt->toDateString(); 
		if($type=="day"){
			$dayLetter = $dt->format('l'); 	
		}else{
			$dayLetter = $dt->format('F'); 
		}
		return $dayLetter;
	}

	public static function getHours($start,$end,$start_2,$end_2,$start_3,$end_3,$count){

	 	$q 	= explode(' ', $start); 	$w 	= explode(' ', $end);
	 	$q2 = explode(' ', $start_2); 	$w2 = explode(' ', $end_2);
	 	$q3 = explode(' ', $start_3); 	$w3 = explode(' ', $end_3);

	 	$ff_count= $count-1;

	 	list($y_s,$m_s,$d_s) = explode('-',$q[0]); 	list($h_s,$mm_s,$s_s) = explode(':',$q[1]);
	 	list($y_e,$m_e,$d_e) = explode('-',$w[0]); 	list($h_e,$mm_e,$s_e) = explode(':',$w[1]);

	 	list($y_s2,$m_s2,$d_s2) = explode('-',$q2[0]); 	list($h_s2,$mm_s2,$s_s2) = explode(':',$q2[1]);
	 	list($y_e2,$m_e2,$d_e2) = explode('-',$w2[0]); 	list($h_e2,$mm_e2,$s_e2) = explode(':',$w2[1]);

	 	list($y_s3,$m_s3,$d_s3) = explode('-',$q3[0]); 	list($h_s3,$mm_s3,$s_s3) = explode(':',$q3[1]);
	 	list($y_e3,$m_e3,$d_e3) = explode('-',$w3[0]); 	list($h_e3,$mm_e3,$s_e3) = explode(':',$w3[1]);

	 	
	 	$dt_start = Carbon::create($y_s, $m_s, $d_s, $h_s, $mm_s, $s_s);
	 	$dt_start2 = Carbon::create($y_s, $m_s, $d_s, $h_s, $mm_s, $s_s);
	 	$dt_start3 = 	Carbon::create($y_e, $m_e, $d_e, $h_e, $mm_e, $s_e);


		$dt_start_2 = Carbon::create($y_s2, $m_s2, $d_s2, $h_s2, $mm_s2, $s_s2);
	 	$dt_start2_2 = Carbon::create($y_s2, $m_s2, $d_s2, $h_s2, $mm_s2, $s_s2);
	 	$dt_start3_2 = 	Carbon::create($y_e2, $m_e2, $d_e2, $h_e2, $mm_e2, $s_e2);


	 	$dt_start_3 = Carbon::create($y_s3, $m_s3, $d_s3, $h_s3, $mm_s3, $s_s3);
	 	$dt_start2_3 = Carbon::create($y_s3, $m_s3, $d_s3, $h_s3, $mm_s3, $s_s3);
	 	$dt_start3_3 = 	Carbon::create($y_e3, $m_e3, $d_e3, $h_e3, $mm_e3, $s_e3);

	 	$f=[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];
	 	
	 	$hora_end=$dt_start3->toTimeString();	 		
	 	$hora_end_2=$dt_start3_2->toTimeString();
	 	$hora_end_3=$dt_start3_3->toTimeString();

	 	$la = array();
	 	$fq=0;
	 	if($q[1]!='00:00:00'){
		 	foreach ($f as $a)
		 	{
		 		if($a==1)
		 		{
		 			$la[$fq]=array(
		 				'label'=>$dt_start->toTimeString()." - ".$dt_start2->addMinutes($ff_count)->addSeconds(60)->toTimeString(),
		 				'start'=>$dt_start->toTimeString(),
		 				'end'=>$dt_start2->toTimeString()
		 			);
		 		}
		 		else
		 		{
		 			$la[$fq]=array(
		 				'label'=>$dt_start->addMinutes($count)->toTimeString()." - ".$dt_start2->addMinutes($ff_count)->addSeconds(59)->toTimeString(),
		 				'start'=>$dt_start->toTimeString(),
		 				'end'=>$dt_start2->toTimeString()
		 			);	 			
		 		}
		 		if($hora_end < $dt_start2->toTimeString())
		 		{	 		 	
			 	 	break;	 		 
			 	} 
			 	$fq++;
		 	}
	 	}

	 	if($q2[1]!='00:00:00'){
		 	foreach ($f as $am)
		 	{
		 		
		 		if($am==1)
		 		{
		 			$la[$fq]=array(
		 				'label'=>$dt_start_2->toTimeString()." - ".$dt_start2_2->addMinutes($ff_count)->addSeconds(60)->toTimeString(),
		 				'start'=>$dt_start_2->toTimeString(),
		 				'end'=>$dt_start2_2->toTimeString()
		 			);
		 		}
		 		else
		 		{
		 			$la[$fq]=array(
		 				'label'=>$dt_start_2->addMinutes($count)->toTimeString()." - ".$dt_start2_2->addMinutes($ff_count)->addSeconds(59)->toTimeString(),
		 				'start'=>$dt_start_2->toTimeString(),
		 				'end'=>$dt_start2_2->toTimeString()
		 			);	 			
		 		}
		 		if($hora_end_2 < $dt_start2_2->toTimeString())
		 		{	 		 	
			 	 	break;	 		 
			 	} 
			 	$fq++;
		 	}
	 	}

	 	if($q3[1]!='00:00:00'){
		 	foreach ($f as $am)
		 	{
		 		
		 		if($am==1)
		 		{
		 			$la[$fq]=array(
		 				'label'=>$dt_start_3->toTimeString()." - ".$dt_start2_3->addMinutes($ff_count)->addSeconds(60)->toTimeString(),
		 				'start'=>$dt_start_3->toTimeString(),
		 				'end'=>$dt_start2_3->toTimeString()
		 			);
		 		}
		 		else
		 		{
		 			$la[$fq]=array(
		 				'label'=>$dt_start_3->addMinutes($count)->toTimeString()." - ".$dt_start2_3->addMinutes($ff_count)->addSeconds(59)->toTimeString(),
		 				'start'=>$dt_start_3->toTimeString(),
		 				'end'=>$dt_start2_3->toTimeString()
		 			);	 			
		 		}
		 		if($hora_end_2 < $dt_start2_3->toTimeString())
		 		{	 		 	
			 	 	break;	 		 
			 	} 
			 	$fq++;
		 	}
	 	}
	 	#return Response::json($la);
	 	return $la;	
	}

	public static function appointmentTimes($date,$doctor,$bussines)
	{
		#obtener variables
		$dayLetter = Helpers::getDateString($date,'day');

		$agenda_id = Business::find($bussines)->agenda_id; 

		$dating_duration = Agenda::find($agenda_id)->dating_duration;

		$custom_day = CustomDay::where('day',$date)
							   ->where('agenda_id',$agenda_id)
							   ->first();	

		if(is_null($custom_day)){
			#No existe en CustomDay la fecha
			$configday = Configday::where('agenda_id',$agenda_id) ->where('day',$dayLetter) ->first();
			if($configday){
				#Existe la agenda en la agenda
				$is_day_off_configday = $configday->is_day_off;

				$starttime_am = $configday->starttime_am;
				$starttime_am = ($date.' '.$starttime_am);

				$endtime_am = $configday->endtime_am;
				$endtime_am = ($date.' '.$endtime_am);

				$lunch_start = $configday->lunch_start;
				$lunch_start = ($date.' '.$lunch_start);

				$lunch_end = $configday->lunch_end;
				$lunch_end = ($date.' '.$lunch_end);

				$starttime_pm = $configday->starttime_pm;
				$starttime_pm = ($date.' '.$starttime_pm);

				$endtime_pm = $configday->endtime_pm;
				$endtime_pm = ($date.' '.$endtime_pm);

				if($is_day_off_configday){
					#Cuando is_day_off = 1 	=> que ese dia NO se trabaja
					return "0";
				}else{
					/*	
						Cuando is_day_off = 0	=> que ese dia se trabaja
						Luego buscaremos la fecha en la tabla Appointment(Citas)
						quisiera que en la citas actuales puedas ver citas posteriores(futuras)
						fechas año-mes-dia					
					*/
					
					$appointments = Appointment::where('day',$date)												
												->get();
					#return ($starttime_am." <=> ".$endtime_am." <br> ".$lunch_start." <=> ".$lunch_end." <br> ".$starttime_pm." <=> ".$endtime_pm);
					$hours =  Helpers::getHours($starttime_am,$endtime_am,$lunch_start,$lunch_end,$starttime_pm,$endtime_pm,$dating_duration);					
					$hours_back=$hours;
					foreach ($hours as $h => $j) {
						$star_h = $j['start'];						 
						/* Validar que soo muestre la citas pendiente */
						$appointment = Appointment::where('day',$date)													
													->get();

						foreach ($appointment as $key) {
							$start_key=Carbon::parse($key->start_date)->toTimeString();
							if($start_key==$star_h){
								 unset($hours_back[$h]);								 
							}
						}												 
					}
					 
					return Response::json($hours_back);
				}
			}else{
				return "0";
			}
		}else{
			#Si existe en CustomDay la fecha
			$is_day_off = $custom_day->is_day_off;
			
			$starttime_am = $custom_day->starttime_am;
			$starttime_am = ($date.' '.$starttime_am);

			$endtime_am = $custom_day->endtime_am;
			$endtime_am = ($date.' '.$endtime_am);

			$lunch_start = $custom_day->lunch_start;
			$lunch_end = $custom_day->lunch_end;

			$starttime_pm = $custom_day->starttime_pm;
			$starttime_pm = ($date.' '.$starttime_pm);

			$endtime_pm = $custom_day->endtime_pm;
			$endtime_pm = ($date.' '.$endtime_pm);
			
			#Verificar si esa fecha is_day_off = 1
			if($is_day_off){
				return "0";
			}else{
				$appointments = Appointment::where('day',$date)->get();

				#$hours =  Helpers::getHours($starttime_am,$endtime_am,$starttime_pm,$endtime_pm,'45');
				$hours =  Helpers::getHours($starttime_am,$endtime_am,$lunch_start,$lunch_end,$starttime_pm,$endtime_pm,$dating_duration);					

				$hours_back=$hours;

				foreach ($hours as $h => $j) {
						$star_h = $j['start'];						 
						$appointment = Appointment::where('day',$date)->get();
						foreach ($appointment as $key) {
							$start_key=Carbon::parse($key->start_date)->toTimeString();
							if($start_key==$star_h){
								 unset($hours_back[$h]);								 
							}
						}												 
					}
					 
					return Response::json($hours_back);
				
			}
		}
	}

	public static function getHoursHH($start,$end,$start_2,$end_2,$start_3,$end_3,$count){

	 	$q 	= explode(' ', $start); 	$w 	= explode(' ', $end);
	 	$q2 = explode(' ', $start_2); 	$w2 = explode(' ', $end_2);
	 	$q3 = explode(' ', $start_3); 	$w3 = explode(' ', $end_3);

	 	$ff_count= $count-1;

	 	list($y_s,$m_s,$d_s) = explode('-',$q[0]); 	list($h_s,$mm_s,$s_s) = explode(':',$q[1]);
	 	list($y_e,$m_e,$d_e) = explode('-',$w[0]); 	list($h_e,$mm_e,$s_e) = explode(':',$w[1]);

	 	list($y_s2,$m_s2,$d_s2) = explode('-',$q2[0]); 	list($h_s2,$mm_s2,$s_s2) = explode(':',$q2[1]);
	 	list($y_e2,$m_e2,$d_e2) = explode('-',$w2[0]); 	list($h_e2,$mm_e2,$s_e2) = explode(':',$w2[1]);

	 	list($y_s3,$m_s3,$d_s3) = explode('-',$q3[0]); 	list($h_s3,$mm_s3,$s_s3) = explode(':',$q3[1]);
	 	list($y_e3,$m_e3,$d_e3) = explode('-',$w3[0]); 	list($h_e3,$mm_e3,$s_e3) = explode(':',$w3[1]);

	 	$dt_start = Carbon::create($y_s, $m_s, $d_s, $h_s, $mm_s, $s_s);
	 	$dt_start2 = Carbon::create($y_s, $m_s, $d_s, $h_s, $mm_s, $s_s);
	 	$dt_start3 = 	Carbon::create($y_e, $m_e, $d_e, $h_e, $mm_e, $s_e);

		$dt_start_2 = Carbon::create($y_s2, $m_s2, $d_s2, $h_s2, $mm_s2, $s_s2);
	 	$dt_start2_2 = Carbon::create($y_s2, $m_s2, $d_s2, $h_s2, $mm_s2, $s_s2);
	 	$dt_start3_2 = 	Carbon::create($y_e2, $m_e2, $d_e2, $h_e2, $mm_e2, $s_e2);

	 	$dt_start_3 = Carbon::create($y_s3, $m_s3, $d_s3, $h_s3, $mm_s3, $s_s3);
	 	$dt_start2_3 = Carbon::create($y_s3, $m_s3, $d_s3, $h_s3, $mm_s3, $s_s3);
	 	$dt_start3_3 = 	Carbon::create($y_e3, $m_e3, $d_e3, $h_e3, $mm_e3, $s_e3);

	 	$f=[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];
	 	
	 	$hora_end=$dt_start3->toTimeString();	 		
	 	$hora_end_2=$dt_start3_2->toTimeString();
	 	$hora_end_3=$dt_start3_3->toTimeString();

	 	$la = array();
	 	$fq=0;

	 	if($q[1]!='00:00:00'){
		 	foreach ($f as $a)
		 	{
		 		if($a==1)
		 		{
		 			$dt_start2->addMinutes($ff_count)->addSeconds(60)->toTimeString();
		 			$la[$fq]=array(
		 				'title'=>'Hora Disponible',
		 				'start'=>$dt_start->toDateString()."T".$dt_start->toTimeString(),
		 				'end'=>$dt_start2->toDateString()."T".$dt_start2->toTimeString(),
		 				'color'=>'#CCCCCC',
		 				'allDay' => false,
		 				'startt'=>$dt_start->toDateString()." ".$dt_start->toTimeString(),
		 				'endd'=>$dt_start2->toDateString()." ".$dt_start2->toTimeString(),
		 				'day'=>$dt_start2->toDateString()
		 			);
		 		}
		 		else
		 		{
		 			$dt_start->addMinutes($count)->toTimeString();
		 			$dt_start2->addMinutes($ff_count)->addSeconds(59)->toTimeString();
		 			$la[$fq]=array(
		 				'title'=>'Hora Disponible',
		 				'start'=>$dt_start->toDateString()."T".$dt_start->toTimeString(),
		 				'end'=>$dt_start2->toDateString()."T".$dt_start2->toTimeString(),
		 				'color'=>'#CCCCCC',
		 				'allDay' => false,
		 				'startt'=>$dt_start->toDateString()." ".$dt_start->toTimeString(),
		 				'endd'=>$dt_start2->toDateString()." ".$dt_start2->toTimeString(),
		 				'day'=>$dt_start2->toDateString()
		 			);	 			
		 		}

		 		if($hora_end < $dt_start2->toTimeString())
		 		{	 		 	
			 	 	break;	 		 
			 	} 
			 	$fq++;
		 	}
	 	}

	 	if($q2[1]!='00:00:00'){

		 	foreach ($f as $am)
		 	{
		 		if($am==1)
		 		{
		 			$dt_start_2->toTimeString();
		 			$dt_start2_2->addMinutes($ff_count)->addSeconds(60)->toTimeString();
		 			$la[$fq]=array(
		 				'title'=>'Hora Disponible',
		 				'start'=>$dt_start_2->toDateString()."T".$dt_start_2->toTimeString(),
		 				'end'=>$dt_start2_2->toDateString()."T".$dt_start2_2->toTimeString(),
		 				'color'=>'#CCCCCC',
		 				'allDay' => false,
		 				'startt'=>$dt_start_2->toDateString()." ".$dt_start_2->toTimeString(),
		 				'endd'=>$dt_start2_2->toDateString()." ".$dt_start2_2->toTimeString(),
		 				'day'=>$dt_start2_2->toDateString()
		 			);
		 		}
		 		else
		 		{
		 			$dt_start_2->addMinutes($count)->toTimeString();
		 			$dt_start2_2->addMinutes($ff_count)->addSeconds(59)->toTimeString();
		 			$la[$fq]=array(
		 				'title'=>'Hora Disponible',
		 				'start'=>$dt_start_2->toDateString()."T".$dt_start_2->toTimeString(),
		 				'end'=>$dt_start2_2->toDateString()."T".$dt_start2_2->toTimeString(),
		 				'color'=>'#CCCCCC',
		 				'allDay' => false,
		 				'startt'=>$dt_start_2->toDateString()." ".$dt_start_2->toTimeString(),
		 				'endd'=>$dt_start2_2->toDateString()." ".$dt_start2_2->toTimeString(),
		 				'day'=>$dt_start2_2->toDateString()
		 			);	 			
		 		}
		 		if($hora_end_2 < $dt_start2_2->toTimeString())
		 		{	 		 
		 			#dd($hora_end_2." menor q ".$dt_start2_2->toTimeString());

			 	 	break;	 		 
			 	} 
			 	$fq++;
		 	}
	 	}

	 	if($q3[1]!='00:00:00'){
		 	foreach ($f as $am)
		 	{
		 		
		 		if($am==1)
		 		{
 
		 			$dt_start2_3->addMinutes($ff_count)->addSeconds(60);
		 			$la[$fq]=array(
		 				'title'=>'Hora Disponible',
		 				'start'=>$dt_start_3->toDateString()."T".$dt_start_3->toTimeString(),
		 				'end'=>$dt_start2_3->toDateString()."T".$dt_start2_3->toTimeString(),
		 				'color'=>'#CCCCCC',
		 				'allDay' => false
		 			);
		 			#dd($hora_end_3." menor q ".$dt_start2_3->toTimeString());

		 		}
		 		else
		 		{
		 			 $dt_start_3->addMinutes($count);
		 			 $dt_start2_3->addMinutes($ff_count)->addSeconds(59);
		 			 
		 			$la[$fq]=array(
		 				'title'=>'Hora Disponible',
		 				'start'=>$dt_start_3->toDateString()."T".$dt_start_3->toTimeString(),
		 				'end'=>$dt_start2_3->toDateString()."T".$dt_start2_3->toTimeString(),
		 				'color'=>'#CCCCCC',
		 				'allDay' => false
		 			);	 			
		 		}

		 		if($hora_end_3 < $dt_start2_3->toTimeString())
		 		{	 

			 	 	break;	 		 
			 	} 
			 	$fq++;
		 	}
	 	}

	 	#return Response::json($la);
	 	 
	 	return $la;
	}

	public static function appointmentTimesHH($date,$doctor,$bussines)
	{
		#obtener variables
		$in_start = date('Y-m-d', Input::get('start'));
		$date = $in_start;
		$dayLetter = Helpers::getDateString($date,'day');

		$agenda_id = Business::find($bussines)->agenda_id; 

		$dating_duration = Agenda::find($agenda_id)->dating_duration;

		$custom_day = CustomDay::where('day',$date)
							   ->where('agenda_id',$agenda_id)
							   ->first();	
		if(is_null($custom_day)){
			$configday = Configday::where('agenda_id',$agenda_id)->where('day',$dayLetter) ->first();
			
			if($configday){
				#Existe la agenda en la agenda
				$is_day_off_configday = $configday->is_day_off;
				$l1 = ($date.'T'.'00:00:00');
				$l2 = ($date.'T'.'00:00:00');


				$starttime_am = $configday->starttime_am;
				$starttime_am = ($date.' '.$starttime_am);

				$endtime_am = $configday->endtime_am;
				$endtime_am = ($date.' '.$endtime_am);

				$lunch_start = $configday->lunch_start;
				$lunch_start = ($date.' '.$lunch_start);

				$lunch_end = $configday->lunch_end;
				$lunch_end = ($date.' '.$lunch_end);

				$starttime_pm = $configday->starttime_pm;
				$starttime_pm = ($date.' '.$starttime_pm);

				$endtime_pm = $configday->endtime_pm;
				$endtime_pm = ($date.' '.$endtime_pm);

				if($is_day_off_configday){
					#Cuando is_day_off = 1 	=> que ese dia NO se trabaja
					$a = array(
							array(
					  			'title'=>'Dia No Disponible',
		 						'start'=>$l1,
		 						'end'=>$l2,
		 						'color'=>'gray',
		 						'editable'=>false,
		 						'allDay' => true)
					);
					return Response::json($a);
				}else{
					/*	
						Cuando is_day_off = 0	=> que ese dia se trabaja
						Luego buscaremos la fecha en la tabla Appointment(Citas)
						quisiera que en la citas actuales puedas ver citas posteriores(futuras)
						fechas año-mes-dia					
					*/
					
					$appointments = Appointment::where('day',$date)												
												->get();
					$hours =  Helpers::getHoursHH($starttime_am,$endtime_am,$lunch_start,$lunch_end,$starttime_pm,$endtime_pm,$dating_duration);
					
					$hours_back=$hours;
					
					foreach ($hours as $h => $j) {
						
						$star_h = $j['start'];						 
						/* Validar que soo muestre la citas pendiente */
					
						$states = array('confirmed', 'delayed', 'pending', 'in-progress', 'served', 'old');
			    	    $appointment = Appointment::where('day',$date)
			    								->whereIn('state',$states)
			 									->get();

						foreach ($appointment as $key) {
							$start_key=Carbon::parse($key->start_date)->toTimeString();
							$h_key=Carbon::parse($star_h)->toTimeString();
							$user = Patient::find($key->patient_id)->user_id;
							$name_user = User::find($user)->getFullName();
							if($start_key==$h_key){
							    #dd($hours_back[$h]);  #unset($hours_back[$h]);
								$hours_back[$h]['title'] = $name_user." - ".$key->reason;								
								if($key->state=='confirmed'){
										$hours_back[$h]['title'] = $name_user." - ".$key->reason;											
										$hours_back[$h]['color'] = '#00D900';
										$hours_back[$h]['id'] = $key->id;

								}else if($key->state=='delayed'){
										
										$hours_back[$h]['color'] = '#FF9900';	
										$hours_back[$h]['title'] = $name_user." - ".$key->reason;
										$hours_back[$h]['id'] = $key->id;


								}else if($key->state=='pending'){
										$hours_back[$h]['color'] = '#E40B0B';
										$hours_back[$h]['title'] = $name_user." - ".$key->reason;
										$hours_back[$h]['id'] = $key->id;


								}else if($key->state=='in-progress'){
										$hours_back[$h]['color'] = '#FFFF00';	
										$hours_back[$h]['title'] = $name_user." - ".$key->reason;										
										$hours_back[$h]['id'] = $key->id;

								}else if($key->state=='old'){
										$hours_back[$h]['color'] = '#000000';
										$hours_back[$h]['title'] = $name_user." - ".$key->reason;										
								
								}else if($key->state=='served'){
										$hours_back[$h]['color'] = '#4A86E8';
										$hours_back[$h]['title'] = $name_user." - ".$key->reason;																		
								}else{
									$hours_back[$h]['color'] = '#D9D9D9';
								}							
							}
						}												 
					}
					return Response::json($hours_back);
				}
			}else{
				$a = array(
							array(
					  			'title'=>'Dia No Disponible',
		 						'start'=>$l1,
		 						'end'=>$l2,
		 						'color'=>'gray',
		 						'editable'=>false,
		 						'allDay' => true)
					);
					return Response::json($a);
			}
		}else{
			#Si existe en CustomDay la fecha
			$is_day_off = $custom_day->is_day_off;
			
			$starttime_am = $custom_day->starttime_am;
			$starttime_am = ($date.' '.$starttime_am);

			$endtime_am = $custom_day->endtime_am;
			$endtime_am = ($date.' '.$endtime_am);

			$lunch_start = $configday->lunch_start;
			$l1 = ($date.'T'.$lunch_start);

			$lunch_start = ($date.' '.$lunch_start);

			$lunch_end = $configday->lunch_end;
			$l2 = ($date.'T'.$lunch_end);

			$lunch_end = ($date.' '.$lunch_end);

			$starttime_pm = $custom_day->starttime_pm;
			$starttime_pm = ($date.' '.$starttime_pm);

			$endtime_pm = $custom_day->endtime_pm;
			$endtime_pm = ($date.' '.$endtime_pm);
			
			#Verificar si esa fecha is_day_off = 1
			if($is_day_off){
				$a = array(
					  	'title'=>'Dia No Disponible',
		 				'start'=>$l1,
		 				'end'=>$l2,
		 				'color'=>'gray',
		 				'background' => 'gray');
					return  Response::json($a);
			}else{				

					$states = array('confirmed', 'delayed', 'pending', 'in-progress', 'served', 'old');
			    	$appointment = Appointment::where('day',$date)
			    								->whereIn('state',$states)
			 									->get();
				#$hours =  Helpers::getHours($starttime_am,$endtime_am,$starttime_pm,$endtime_pm,'45');
				$hours =  Helpers::getHoursHH($starttime_am,$endtime_am,$lunch_start,$lunch_end,$starttime_pm,$endtime_pm,$dating_duration);								

				$hours_back=$hours;

				foreach ($hours as $h => $j) {
						$star_h = $j['start'];						 
						$appointment = Appointment::where('day',$date)->get();
						foreach ($appointment as $key) {
							$start_key=Carbon::parse($key->start_date)->toTimeString();
							if($start_key==$star_h){
								 unset($hours_back[$h]);								 
							}
						}												 
					}
					 
					return Response::json($hours_back);				
			}
		}
	}

	public static function appointmentAgenda($doctor,$bussines)
	{
		
		#obtener variables
		$dayLetter = Helpers::getDateString(date('Y-m-d'),'day');

		$agenda_id = Business::find($bussines)->agenda_id; 

		$dating_duration = Agenda::find($agenda_id)->dating_duration;

		$custom_day = CustomDay::where('day',$date)
							   ->where('agenda_id',$agenda_id)
							   ->first();
		
		if(is_null($custom_day)){
			#No existe en CustomDay la fecha
			$configday = Configday::where('agenda_id',$agenda_id) ->where('day',$dayLetter) ->first();
			if($configday){
				#Existe la agenda en la agenda
				$is_day_off_configday = $configday->is_day_off;

				$starttime_am = $configday->starttime_am;
				$starttime_am = ($date.' '.$starttime_am);

				$endtime_am = $configday->endtime_am;
				$endtime_am = ($date.' '.$endtime_am);

				$lunch_start = $configday->lunch_start;
				$lunch_end = $configday->lunch_end;

				$starttime_pm = $configday->starttime_pm;
				$starttime_pm = ($date.' '.$starttime_pm);

				$endtime_pm = $configday->endtime_pm;
				$endtime_pm = ($date.' '.$endtime_pm);

				if($is_day_off_configday){
					#Cuando is_day_off = 1 	=> que ese dia NO se trabaja
					return "0";
				}else{
					/*	
						Cuando is_day_off = 0	=> que ese dia se trabaja
						Luego buscaremos la fecha en la tabla Appointment(Citas)
						quisiera que en la citas actuales puedas ver citas posteriores(futuras)
						fechas año-mes-dia					
					*/
					
					$appointments = Appointment::where('day',$date)->get();
					$hours =  Helpers::getHours($starttime_am,$endtime_am,$starttime_pm,$endtime_pm,$dating_duration);					
					$hours_back=$hours;
					foreach ($hours as $h => $j) {
						$star_h = $j['start'];						 
						$appointment = Appointment::where('day',$date)->get();
						foreach ($appointment as $key) {
							$start_key=Carbon::parse($key->start_date)->toTimeString();
							if($start_key==$star_h){
								 unset($hours_back[$h]);								 
							}
						}												 
					}
					 
					return $hours_back;
				}
			}else{
				return "0";
			}
		}else{
			#Si existe en CustomDay la fecha
			$is_day_off = $custom_day->is_day_off;
			
			$starttime_am = $custom_day->starttime_am;
			$starttime_am = ($date.' '.$starttime_am);

			$endtime_am = $custom_day->endtime_am;
			$endtime_am = ($date.' '.$endtime_am);

			$lunch_start = $custom_day->lunch_start;
			$lunch_end = $custom_day->lunch_end;

			$starttime_pm = $custom_day->starttime_pm;
			$starttime_pm = ($date.' '.$starttime_pm);

			$endtime_pm = $custom_day->endtime_pm;
			$endtime_pm = ($date.' '.$endtime_pm);
			
			#Verificar si esa fecha is_day_off = 1
			if($is_day_off){
				return "0";
			}else{
				$appointments = Appointment::where('day',$date)->get();

				$hours =  Helpers::getHours($starttime_am,$endtime_am,$starttime_pm,$endtime_pm,$dating_duration);					
				$hours_back=$hours;

				foreach ($hours as $h => $j) {
						$star_h = $j['start'];						 
						$appointment = Appointment::where('day',$date)->get();
						foreach ($appointment as $key) {
							$start_key=Carbon::parse($key->start_date)->toTimeString();
							if($start_key==$star_h){
								 unset($hours_back[$h]);								 
							}
						}												 
					}
					 
					return Response::json($hours_back);
				
			}
		}
	}

	/* PATIENT */
	public static function Show($patient)
	{
		$id = $patient->id;
		$user_id = $patient->user_id;
	    $datos = DB::table('patients')
		           	->Join('users', 'users.id', '=', 'patients.user_id')
		           	->Join('profiles', 'profiles.user_id', '=', 'users.id')
		           	->Join('insurances', 'insurances.id', '=', 'patients.insurance_id')
		           	->Join('addresses', 'addresses.id', '=', 'profiles.address_id')
		           	->Join('cities', 'cities.id', '=', 'addresses.city_id')
		           	->Join('provinces', 'provinces.id', '=', 'cities.province_id')
		           	->Join('countries', 'countries.id', '=', 'provinces.country_id')
		           	->where('users.id', $user_id)
		           	->select(DB::raw('CONCAT(first_name," ",last_name) as name'),
		           	         'users.first_name as name_',
		           	         'users.last_name as last_name',
		           	         'users.username as username',
		           	         'users.email as email',
		           	         'users.dni as dni',
		           	         'profiles.phone as phone',
		           	         'profiles.picture as picture',
		           	         'profiles.date as date',
		           	         'insurances.id as insurance_id',
		           	         'insurances.name as insurance_name',
		           	         'countries.id as country_id',
		           	         'countries.name as country_name',
		           	         'provinces.id as province_id',
		           	         'provinces.name as province_name',
		           	         'cities.id as city_id',
		           	         'cities.name as city_name',
		           	         'addresses.id as address_id',
		           	         'addresses.my_address as address_name',
		           	         'users.last_login as last_login')
		           	->first();
		  $array = array('data'=>$datos);
		 return Response::json($array );          	
	}

	/* DOCTORS */
	public static function indexDoctor()
	{			
	    $datos = DB::table('doctors')
		           	->Join('users', 'users.id', '=', 'doctors.user_id')		           
		           	->select(DB::raw('CONCAT(first_name," ",last_name) as name'),
		           	         'users.username as username',
		           	         'users.email as email',
		           	         'users.last_login as last_login')
		           	->get();
		  $array = array('data'=>$datos);
		 return Response::json($array );          	
	}

	/* ADDRESS */
	public static function countriesAddress($value)
	{
	    $datos = DB::table('countries')
	    			->where('name', 'like', '%'. $value.'%')
		           	->select('id','name as value')
		           	->orderBy('name','asc')
		           	->get();
		  #$objs = (object)$array;
		 return Response::json($datos);          	
	}

	public static function provinciesAddress($country)
	{

	    $datos = DB::table('countries')
	    		    ->join('provinces', 'provinces.country_id', '=', 'countries.id')		           
	    			->where('countries.id',$country)	    		    
		           	->select(
		           			'provinces.id as id', 
		           			'provinces.name as label',		           			
		           			'provinces.name as value')
		           	->get();
		 return Response::json($datos);          	
	}
	
	public static function provinciesAddress2($id,$load,$country)
	{

	    $datos = DB::table('countries')
	    		    ->join('provinces', 'provinces.country_id', '=', 'countries.id')		           
	    			->where('countries.id',$country)	    		    
		           	->select('provinces.id as id',
		           			'provinces.name as name')
		           	->remember(10)
		           	->get();
        echo "<select parsley-required='true' name='".$id."'  id='".$id."' class='selectpicker form-control' data-size='10' data-live-search='true'><option value=''>Live search Provincies </option>";
		foreach ($datos as $d) {
            echo "<option value='".$d->id."'>".$d->name."</option>";			
		}
		echo "</select><script>$('#".$id."').selectpicker(); $('#".$id."').on('change',function(){var province_id = $(this).val(); $('#".$load."').remove(); $('#c".$load."').load('http://104.236.201.9/proyectos/agendamedica/api/address/cities3?id=".$load."&country='+province_id); }); </script>";
		         	
	}

	public static function citiesAddress3($id,$load,$country)
	{

	    $datos = DB::table('countries')
	    		    ->join('provinces', 'provinces.country_id', '=', 'countries.id')		           
	    		    ->join('cities', 'cities.province_id', '=', 'provinces.id')	
	    			->where('provinces.id', $country)
		           	->select('cities.id as id','cities.name as name')
		           	->orderBy('cities.name','asc')
		           	->remember(10)
		           	->get();
        echo "<select parsley-required='true' name='".$id."'  id='".$id."' class='selectpicker form-control' data-size='10' data-live-search='true'><option value=''>Live search Cities </option>";
		foreach ($datos as $d) {
            echo "<option value='".$d->id."'>".$d->name."</option>";			
		}
		echo "</select><script>$('#".$id."').selectpicker();</script>";
		         	
	}

	public static function citiesAddress($pro)
	{

	    $datos = DB::table('cities')
	    			->where('cities.province_id', $pro)
		           	->select('cities.id as id',
		           			'cities.name as label',		           			
		           			'cities.name as value')
		           	->get();
		  #$objs = (object)$array;
		 #          dd($datos);
		 return Response::json($datos);          	
	}

	public static function countriesAddress2($value)
	{
	    $datos = DB::table('countries')
	    			->where('name', 'like', '%'. $value.'%')
		           	->select('id as value','name as label')
		           	->orderBy('name','asc')
		           	->get();
		  #$objs = (object)$array;
		 return Response::json($datos);          	
	}

	public static function citiesAddress2($value,$country)
	{

	    $datos = DB::table('countries')
	    		    ->join('provinces', 'provinces.country_id', '=', 'countries.id')		           
	    		    ->join('cities', 'cities.province_id', '=', 'provinces.id')	
	    			->where('countries.id', $country)
	    			->where('cities.name', 'like', '%'. $value.'%')
		           	->select('cities.id as value','cities.name as label')
		           	->orderBy('cities.name','asc')
		           	->get();
		  #$objs = (object)$array;
		 #          dd($datos);
		 return Response::json($datos);          	
	}
	

	public static function countDoctor($clinic_id)
	{
		$datos = DB::table('doctors')
	        ->join('clinics', 'clinics.id', '=', 'doctors.clinic_id')		           
	        ->where('clinics.id',$clinic_id)
		   	->count();
		return Response::json(array('count'=>$datos));          	
	}

	/**
		FUNCIONA
	**/
	public static function listDoctor($clinic_id)
	{
		$datos = DB::table('doctors')
	        ->join('users', 'users.id', '=', 'doctors.user_id')		           
	        ->join('clinics', 'clinics.id', '=', 'doctors.clinic_id')		           
	        ->join('business_clinics', 'business_clinics.doctor_id', '=', 'doctors.id')		           
	        ->join('specialties', 'specialties.id', '=', 'doctors.specialty_id')		           
	        ->join('profiles', 'profiles.user_id', '=', 'users.id')		           
	        ->where('clinics.id',$clinic_id)
	        ->select('doctors.id as doctor_id',
	        		DB::raw('CONCAT(users.first_name," " ,users.last_name) as doctor_name'),
	        		'business_clinics.id as local_id',
	        		'doctors.specialty_id as specialty_id',
	        		'profiles.picture as picture')
		   	->get();

			$especialidades = array();
			$dda = array();
			foreach ($datos as $d) {
				$specialties = explode(',', $d->specialty_id);
				foreach ($specialties as $esp) {
					$espec = Specialty::find($esp);
					if(Helpers::lang()=='es'){
						$especialidades[]=array('id'=>$espec->id,'name'=>$espec->name_es);
					}else{
						$especialidades[]=array('id'=>$espec->id,'name'=>$espec->name_en);
					}
				}
				$dda = array(
					'doctor_id'=>$d->doctor_id,
					'doctor_name'=>$d->doctor_name,
					'local_id'=>$d->local_id,
					'picture'=>$d->picture,
					'specialties'=>$especialidades
					);
			}

		return Response::json($dda);          	
	}

	/**
		FUNCIONA
	*/
	public static function getHoursDD($start,$end,$start_2,$end_2,$start_3,$end_3,$count){

	 	$q 	= explode(' ', $start); 	$w 	= explode(' ', $end);
	 	$q2 = explode(' ', $start_2); 	$w2 = explode(' ', $end_2);
	 	$q3 = explode(' ', $start_3); 	$w3 = explode(' ', $end_3);

	 	$ff_count= $count-1;

	 	list($y_s,$m_s,$d_s) = explode('-',$q[0]); 	list($h_s,$mm_s,$s_s) = explode(':',$q[1]);
	 	list($y_e,$m_e,$d_e) = explode('-',$w[0]); 	list($h_e,$mm_e,$s_e) = explode(':',$w[1]);

	 	list($y_s2,$m_s2,$d_s2) = explode('-',$q2[0]); 	list($h_s2,$mm_s2,$s_s2) = explode(':',$q2[1]);
	 	list($y_e2,$m_e2,$d_e2) = explode('-',$w2[0]); 	list($h_e2,$mm_e2,$s_e2) = explode(':',$w2[1]);

	 	list($y_s3,$m_s3,$d_s3) = explode('-',$q3[0]); 	list($h_s3,$mm_s3,$s_s3) = explode(':',$q3[1]);
	 	list($y_e3,$m_e3,$d_e3) = explode('-',$w3[0]); 	list($h_e3,$mm_e3,$s_e3) = explode(':',$w3[1]);

	 	$dt_start = Carbon::create($y_s, $m_s, $d_s, $h_s, $mm_s, $s_s);
	 	$dt_start2 = Carbon::create($y_s, $m_s, $d_s, $h_s, $mm_s, $s_s);
	 	$dt_start3 = 	Carbon::create($y_e, $m_e, $d_e, $h_e, $mm_e, $s_e);

		$dt_start_2 = Carbon::create($y_s2, $m_s2, $d_s2, $h_s2, $mm_s2, $s_s2);
	 	$dt_start2_2 = Carbon::create($y_s2, $m_s2, $d_s2, $h_s2, $mm_s2, $s_s2);
	 	$dt_start3_2 = 	Carbon::create($y_e2, $m_e2, $d_e2, $h_e2, $mm_e2, $s_e2);

	 	$dt_start_3 = Carbon::create($y_s3, $m_s3, $d_s3, $h_s3, $mm_s3, $s_s3);
	 	$dt_start2_3 = Carbon::create($y_s3, $m_s3, $d_s3, $h_s3, $mm_s3, $s_s3);
	 	$dt_start3_3 = 	Carbon::create($y_e3, $m_e3, $d_e3, $h_e3, $mm_e3, $s_e3);

	 	$f=[1,2,3,4,5,6,7,8,9,10];
	 	
	 	$hora_end=$dt_start3->toTimeString();	 		
	 	$hora_end_2=$dt_start3_2->toTimeString();
	 	$hora_end_3=$dt_start3_3->toTimeString();

	 	$la = array();
	 	$fq=0;

	 	if($q[1]!='00:00:00'){
		 	foreach ($f as $a)
		 	{
		 		if($a==1)
		 		{
		 			$dt_start2->addMinutes($ff_count)->addSeconds(60)->toTimeString();
		 			$la[$fq]=array(
		 				'paciente'=>'Hora Disponible',
		 				'motivo'=>'',
		 				'inicio_hora'=>Helpers::hora_min($dt_start->toTimeString()),
		 				'final_hora'=> Helpers::hora_min($dt_start2->toTimeString()),
		 				'color'=>'#CCCCCC',		 				
		 				'fecha'=>$dt_start2->toDateString(),
		 				'estado'=>'disponible'
		 			);
		 		}
		 		else
		 		{
		 			$dt_start->addMinutes($count)->toTimeString();
		 			$dt_start2->addMinutes($ff_count)->addSeconds(59)->toTimeString();
		 			$la[$fq]=array(
		 				'paciente'=>'Hora Disponible',
		 				'motivo'=>'',		 				
		 				'inicio_hora'=>Helpers::hora_min($dt_start->toTimeString()),
		 				'final_hora'=> Helpers::hora_min($dt_start2->toTimeString()),
		 				'color'=>'#CCCCCC',
		 				'fecha'=>$dt_start2->toDateString(),
		 				'estado'=>'disponible'
		 			);	 			
		 		}

		 		if($hora_end < $dt_start2->toTimeString())
		 		{	 		 	
			 	 	break;	 		 
			 	} 
			 	$fq++;
		 	}
	 	}

	 	if($q2[1]!='00:00:00'){

		 	foreach ($f as $am)
		 	{
		 		if($am==1)
		 		{
		 			$dt_start_2->toTimeString();
		 			$dt_start2_2->addMinutes($ff_count)->addSeconds(60)->toTimeString();
		 			$la[$fq]=array(
		 				'paciente'=>'Hora Disponible',
		 				'motivo'=>'',
		 				'inicio_hora'=>Helpers::hora_min($dt_start_2->toTimeString()),
		 				'final_hora'=> Helpers::hora_min($dt_start2_2->toTimeString()),
		 				'color'=>'#CCCCCC',
		 				'fecha'=>$dt_start2_2->toDateString(),
		 				'estado'=>'disponible'
		 			);
		 		}
		 		else
		 		{
		 			$dt_start_2->addMinutes($count)->toTimeString();
		 			$dt_start2_2->addMinutes($ff_count)->addSeconds(59)->toTimeString();
		 			$la[$fq]=array(
		 				'paciente'=>'Hora Disponible',
		 				'motivo'=>'',
		 				'inicio_hora'=>Helpers::hora_min($dt_start_2->toTimeString()),
		 				'final_hora'=> Helpers::hora_min($dt_start2_2->toTimeString()),
		 				'color'=>'#CCCCCC',
		 				'fecha'=>$dt_start2_2->toDateString(),
		 				'estado'=>'disponible'
		 			);	 			
		 		}
		 		if($hora_end_2 < $dt_start2_2->toTimeString())
		 		{	 		 	
			 	 	break;	 		 
			 	} 
			 	$fq++;
		 	}
	 	}

	 	if($q3[1]!='00:00:00'){
		 	foreach ($f as $am)
		 	{
		 		if($am==1)
		 		{
		 			$la[$fq]=array(
		 				'paciente'=>'Hora Disponible',
		 				'motivo'=>'',
		 				'inicio_hora'=>Helpers::hora_min($dt_start_3->toTimeString()),
		 				'final_hora'=> Helpers::hora_min($dt_start2_3->addMinutes($ff_count)->addSeconds(60)->toTimeString()),
		 				'color'=>'#CCCCCC',
		 				'fecha' => $dt_start_3->toDateString(),
		 				'estado'=>'disponible'		 				
		 			);
		 		}
		 		else
		 		{
		 			$la[$fq]=array(
		 				'paciente'=>'Hora Disponible',
		 				'motivo'=>'',
		 				'inicio_hora'=>Helpers::hora_min($dt_start_3->addMinutes($count)->toTimeString()),
		 				'final_hora'=> Helpers::hora_min($dt_start2_3->addMinutes($ff_count)->addSeconds(59)->toTimeString()),
		 				'color'=>'#CCCCCC',
		 				'fecha' => $dt_start2_3->toDateString(),
		 				'estado'=>'disponible'		 				
		 			);	 			
		 		}
		 		if($hora_end_3 < $dt_start2_3->toTimeString())
		 		{	 		 	
			 	 	break;	 		 
			 	} 
			 	$fq++;
		 	}
	 	}

	 	#return Response::json($la);
	 	return $la;
	}

	/**
		FUNCIONA
	*/
	public static function appointmentTimesDD($date,$doctor,$bussines)
	{
		#obtener variables		
		$in_start = date('Y-m-d', Input::get('start'));
		$date = $in_start;
		$dayLetter = Helpers::getDateString($date,'day');

		$agenda_id = Business::find($bussines)->agenda_id; 

		$dating_duration = Agenda::find($agenda_id)->dating_duration;

		$custom_day = CustomDay::where('day',$date)
							   ->where('agenda_id',$agenda_id)
							   ->first();	
		if(is_null($custom_day)){
			$configday = Configday::where('agenda_id',$agenda_id)->where('day',$dayLetter) ->first();
			
			if($configday){
				#Existe la agenda en la agenda
				$is_day_off_configday = $configday->is_day_off;
				$l1 = ($date.'T'.'00:00:00');
				$l2 = ($date.'T'.'00:00:00');


				$starttime_am = $configday->starttime_am;
				$starttime_am = ($date.' '.$starttime_am);

				$endtime_am = $configday->endtime_am;
				$endtime_am = ($date.' '.$endtime_am);

				$lunch_start = $configday->lunch_start;
				$lunch_start = ($date.' '.$lunch_start);

				$lunch_end = $configday->lunch_end;
				$lunch_end = ($date.' '.$lunch_end);

				$starttime_pm = $configday->starttime_pm;
				$starttime_pm = ($date.' '.$starttime_pm);

				$endtime_pm = $configday->endtime_pm;
				$endtime_pm = ($date.' '.$endtime_pm);

				if($is_day_off_configday){
					#Cuando is_day_off = 1 	=> que ese dia NO se trabaja
					$a = array(
							array(
					  			'title'=>'Dia No Disponible',
		 						'color'=>'gray',
		 						'editable'=>false,
		 						'day' => $date)
					);
					return Response::json($a);
				}else{
					/*	
						Cuando is_day_off = 0	=> que ese dia se trabaja
						Luego buscaremos la fecha en la tabla Appointment(Citas)
						quisiera que en la citas actuales puedas ver citas posteriores(futuras)
						fechas año-mes-dia					
					*/
					
					$appointments = Appointment::where('day',$date)												
												->get();
					$hours =  Helpers::getHoursDD($starttime_am,$endtime_am,$lunch_start,$lunch_end,$starttime_pm,$endtime_pm,$dating_duration);
					
					$hours_back=$hours;
					
					foreach ($hours as $h => $j) {
						
						$star_h = $j['fecha']." ".$j['inicio_hora'];						 
						/* Validar que soo muestre la citas pendiente */
					
						$states = array('confirmed', 'delayed', 'pending', 'in-progress', 'served', 'old');
			    	    $appointment = Appointment::where('day',$date)
			    								->whereIn('state',$states)
			 									->get();

						foreach ($appointment as $key) {
							$start_key=Carbon::parse($key->start_date)->toTimeString();
							$h_key=Carbon::parse($star_h)->toTimeString();
							$user = Patient::find($key->patient_id)->user_id;
							$name_user = User::find($user)->getFullName();
							if($start_key==$h_key){
							    #dd($hours_back[$h]);  #unset($hours_back[$h]);
								$hours_back[$h]['titulo'] = $name_user." - ".$key->reason;								
								if($key->state=='confirmed'){
										$hours_back[$h]['paciente'] = $name_user;											
										$hours_back[$h]['motivo'] = $key->reason;											
										$hours_back[$h]['color'] = '#00D900';
										$hours_back[$h]['id'] = $key->id;
										$hours_back[$h]['estado'] = 'confirmed';

								}else if($key->state=='delayed'){
										$hours_back[$h]['paciente'] = $name_user;											
										$hours_back[$h]['motivo'] = $key->reason;	
										$hours_back[$h]['color'] = '#FF9900';	
										$hours_back[$h]['id'] = $key->id;
										$hours_back[$h]['estado'] = 'delayed';


								}else if($key->state=='pending'){
										$hours_back[$h]['paciente'] = $name_user;											
										$hours_back[$h]['motivo'] = $key->reason;
										$hours_back[$h]['color'] = '#E40B0B';
										$hours_back[$h]['id'] = $key->id;
										$hours_back[$h]['estado'] = 'pending';


								}else if($key->state=='in-progress'){
										$hours_back[$h]['paciente'] = $name_user;											
										$hours_back[$h]['motivo'] = $key->reason;
										$hours_back[$h]['color'] = '#FFFF00';	
										$hours_back[$h]['id'] = $key->id;
										$hours_back[$h]['estado'] = 'in-progress';

								}else if($key->state=='old'){
										$hours_back[$h]['color'] = '#000000';
										$hours_back[$h]['paciente'] = $name_user;											
										$hours_back[$h]['motivo'] = $key->reason;
								}else if($key->state=='served'){

										$hours_back[$h]['paciente'] = $name_user;											
										$hours_back[$h]['motivo'] = $key->reason;
										$hours_back[$h]['color'] = '#4A86E8';
										$hours_back[$h]['id'] = $key->id;
										$hours_back[$h]['estado'] = 'served';

								}else{
									$hours_back[$h]['color'] = '#D9D9D9';
								}							
							}
						}												 
					}
					return Response::json($hours_back);
				}
			}else{
				$a = array(
							array(
					  			'titulo'=>'Dia No Disponible',
		 						'color'=>'gray',
		 						'fecha' => $date)
					);
					return Response::json($a);
			}
		}else{
			#Si existe en CustomDay la fecha
			$is_day_off = $custom_day->is_day_off;
			
			$starttime_am = $custom_day->starttime_am;
			$starttime_am = ($date.' '.$starttime_am);

			$endtime_am = $custom_day->endtime_am;
			$endtime_am = ($date.' '.$endtime_am);

			$lunch_start = $configday->lunch_start;
			$l1 = ($date.'T'.$lunch_start);

			$lunch_start = ($date.' '.$lunch_start);

			$lunch_end = $configday->lunch_end;
			$l2 = ($date.'T'.$lunch_end);

			$lunch_end = ($date.' '.$lunch_end);

			$starttime_pm = $custom_day->starttime_pm;
			$starttime_pm = ($date.' '.$starttime_pm);

			$endtime_pm = $custom_day->endtime_pm;
			$endtime_pm = ($date.' '.$endtime_pm);
			
			#Verificar si esa fecha is_day_off = 1
			if($is_day_off){
				$a = array(
					  	'title'=>'Dia No Disponible',
		 				'start'=>$l1,
		 				'end'=>$l2,
		 				'color'=>'gray',
		 				'background' => 'gray');
					return  Response::json($a);
			}else{				

					$states = array('confirmed', 'delayed', 'pending', 'in-progress', 'served', 'old');
			    	$appointment = Appointment::where('day',$date)
			    								->whereIn('state',$states)
			 									->get();
				#$hours =  Helpers::getHours($starttime_am,$endtime_am,$starttime_pm,$endtime_pm,'45');
				$hours =  Helpers::getHoursDD($starttime_am,$endtime_am,$lunch_start,$lunch_end,$starttime_pm,$endtime_pm,$dating_duration);								

				$hours_back=$hours;

				foreach ($hours as $h => $j) {
						$star_h = $j['fecha']." ".$j['inicio_hora'];
						$appointment = Appointment::where('day',$date)->get();
						foreach ($appointment as $key) {
							$start_key=Carbon::parse($key->start_date)->toTimeString();
							if($start_key==$star_h){
								 unset($hours_back[$h]);								 
							}
						}												 
					}
					 
					return Response::json($hours_back);				
			}
		}
	}

	/**
		FUNCIONA
	*/
	public static function patientInProgress()
	{
		$date = Input::get('date');
		$doctor_id = Input::get('id');		
		$datos = DB::table('appointments') 
			   ->Join('agendas', 'agendas.id', '=', 'appointments.agenda_id')		
			   ->Join('business_clinics', 'business_clinics.agenda_id', '=', 'agendas.id')
			   ->Join('doctors', 'doctors.id', '=', 'business_clinics.doctor_id')
			   ->Join('patients', 'patients.id', '=', 'appointments.patient_id')
			   ->Join('users', 'users.id', '=', 'patients.user_id')
			   ->Join('profiles', 'profiles.user_id', '=', 'users.id')
			   ->Join('addresses', 'addresses.id', '=', 'profiles.address_id')
			   ->where('appointments.day','=',$date)			   
			   ->where('appointments.state','=','in-progress')			   
			   ->where('doctors.id','=',$doctor_id)
			   ->select('patients.id as patient_id',
			   			DB::raw('CONCAT(first_name," ",last_name) as patient_name'),
			   			'appointments.id as appointments_id',
			   			'appointments.reason as reason',
			   			'appointments.state as state_appointment',
			   			'appointments.start_date as hour_appointment',
			   			'appointments.last_time as hour_stimate')
			   ->first();
		if($datos){
			return Response::json($datos); 

		}else{
			return "0";
		}
	}




	

	///////////////////////////////////////////////////////////////////////
	public static function cupHours($a)
	{
		list($asd1,$hour) = explode(' ',$a);
		list($h,$m,$s) = explode(':',$hour);
		return $h.":".$m;
	}
	
	public static function listAppointments($doctor_id)
	{
		#return Response::json($citas);
		if(Input::get('date')==''){
			$date = date('Y-m-d');
		}else{
			$date=Input::get('date');
		}
		$datos = DB::table('appointments') 
			   ->Join('agendas', 'agendas.id', '=', 'appointments.agenda_id')		
			   ->Join('business_clinics', 'business_clinics.agenda_id', '=', 'agendas.id')
			   ->Join('doctors', 'doctors.id', '=', 'business_clinics.doctor_id')
			   ->Join('patients', 'patients.id', '=', 'appointments.patient_id')
			   ->Join('users', 'users.id', '=', 'patients.user_id')
			   ->Join('profiles', 'profiles.user_id', '=', 'users.id')
			   ->Join('addresses', 'addresses.id', '=', 'profiles.address_id')
			   ->where('appointments.day','=',$date)			   
			   ->where('doctors.id','=',$doctor_id)
			   ->select('patients.id as patient_id',
			   			DB::raw('CONCAT(first_name," ",last_name) as patient_name'),
			   			'addresses.my_address as address',
			   			'appointments.id as appointments_id',
			   			'appointments.day as date',
			   			'doctors.id as doctor_id',
			   			'appointments.reason as reason',
			   			'appointments.state as state_appointment',
			   			'appointments.start_date as hour_appointment',
			   			'appointments.end_date as hour_stimate',
			   			'appointments.last_date_update as last_update')
			   ->get();
		$array=array();	
		foreach ($datos as $d) {
		   $array[]=array(
		   	'patient_id'		=>$d->patient_id,
		   	'patient_name'		=>$d->patient_name,
		   	'appointments_id'	=>$d->appointments_id,
		   	'reason'			=>$d->reason,
		   	'date'				=>$d->date,
		   	'doctor_id'			=>$d->doctor_id,
		   	'state_appointment'	=>$d->state_appointment,
		   	'hour_appointment'	=>Helpers::cupHours($d->hour_appointment),
		   	'hour_stimate'		=>Helpers::cupHours($d->hour_stimate),
		   	'last_update'		=>Helpers::cupHours($d->last_update)
		   	);
		}		 
		return Response::json($array);          	
	}

	public static function appointmentDoc($appointment_id)
	{
		#return Response::json($citas);
		 
		$datos = DB::table('appointments') 
			   ->Join('agendas', 'agendas.id', '=', 'appointments.agenda_id')		
			   ->Join('business', 'business.agenda_id', '=', 'agendas.id')
			   ->Join('doctors', 'doctors.id', '=', 'business.doctor_id')
			   ->Join('patients', 'patients.id', '=', 'appointments.patient_id')
			   ->Join('users', 'users.id', '=', 'patients.user_id')
			   ->where('appointments.id','=',$appointment_id)
			   ->select('patients.id as patient_id',
			   			DB::raw('CONCAT(first_name," ",last_name) as patient_name'),
			   			'business.address as address',
			   			'appointments.id as appointments_id',
			   			'appointments.day as date',
			   			'appointments.reason as reason',
			   			'doctors.id as doctor_id',
			   			'appointments.state as state_appointment',
			   			'appointments.start_date as hour_appointment',
			   			'appointments.end_date as hour_stimate',
			   			'appointments.last_date_update as last_update')
			   ->first();
			   $array=array();
			   $d='';
			   $d=$datos;
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
			   				'reason'=>$d->reason,
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
		return Response::json($citas);          	
	}


	public static function InProgressAppointmentDoc()
	{

		if(Input::get('date')==''){
			$date = date('Y-m-d');
		}else{
			$date=Input::get('date');
		}
		 	
		$d_a=Carbon::parse(date('Y-m-d '.Input::get('hour')));
		  
		#return Response::json($citas);
		
		$datos = DB::table('appointments') 
			   ->Join('agendas', 'agendas.id', '=', 'appointments.agenda_id')		
			   ->Join('business_clinics', 'business_clinics.agenda_id', '=', 'agendas.id')
			   ->Join('doctors', 'doctors.id', '=', 'business_clinics.doctor_id')
			   ->Join('patients', 'patients.id', '=', 'appointments.patient_id')
			   ->Join('users', 'users.id', '=', 'patients.user_id')
			   ->where('appointments.day','=',$date)			   			   
			   ->where('doctors.id','=',Input::get('doctor_id'))			   			   
			   ->select('patients.id as patient_id',
			   			DB::raw('CONCAT(first_name," ",last_name) as patient_name'),
			   			'business.address as address',
			   			'appointments.id as appointments_id',
			   			'appointments.day as date',
			   			'doctors.id as doctor_id',
			   			'appointments.state as state_appointment',
			   			'appointments.start_date as hour_appointment',
			   			'appointments.end_date as hour_stimate',
			   			'appointments.last_date_update as last_update')
			   ->get();
				 
 	 		$as = false;
			foreach ($datos as $d) {
				$citas=array();
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
			   				'doctor_phone'=>'999-8989-200',
			   				'messages'=>$messages_all
			   			);

			   	$first =Carbon::parse($d->hour_appointment);  
				$second=Carbon::parse($d->hour_stimate);  
				$a=$d_a->between($first, $second);
				if(!$a){
					return Response::json(array());
				}else{
					return Response::json($citas);
				}
			}   
		    if(!$as){
		    	return Response::json(array());
		    }
	}

	public static function NextAppointmentDoc($appointment_id)
	{
		if(Input::get('date')==''){
			$date = date('Y-m-d');
		}else{
			$date=Input::get('date');
		}
		 	
		$d_a=Carbon::parse(date('Y-m-d '.Input::get('hour')));
		  
		#return Response::json($citas);
		
		$datos = DB::table('appointments') 
			   ->Join('agendas', 'agendas.id', '=', 'appointments.agenda_id')		
			   ->Join('business', 'business.agenda_id', '=', 'agendas.id')
			   ->Join('doctors', 'doctors.id', '=', 'business.doctor_id')
			   ->Join('patients', 'patients.id', '=', 'appointments.patient_id')
			   ->Join('users', 'users.id', '=', 'patients.user_id')
			   ->where('appointments.day','=',$date)			   			   
			   ->where('doctors.id','=',Input::get('doctor_id'))			   			   
			   ->select('patients.id as patient_id',
			   			DB::raw('CONCAT(first_name," ",last_name) as patient_name'),
			   			'business.address as address',
			   			'appointments.id as appointments_id',
			   			'appointments.day as date',
			   			'doctors.id as doctor_id',
			   			'appointments.state as state_appointment',
			   			'appointments.start_date as hour_appointment',
			   			'appointments.end_date as hour_stimate',
			   			'appointments.last_date_update as last_update')
			   ->get();
				 
 	 
			foreach ($datos as $d) {
				$citas=array();
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
			   				'doctor_phone'=>'999-8989-200',
			   				'messages'=>$messages_all
			   			);

			   	$first =Carbon::parse($d->hour_appointment);  
				$second=Carbon::parse($d->hour_stimate);  
				$a=$d_a->between($first, $second);
				if($a){
					return Response::json($citas);
				}else{

				}
			}   
	}

	public static function sendMenssage($doctor,$appoments,$mensage)
	{		
		$mgs= new MgsAppointment;
		$mgs->title="Message";
		$mgs->appointment_id=$appoments;
		$mgs->text=$mensage;
		$mgs->save();
		return Helpers::Mgs('Mensaje Send Ok');
	}

	public static function chageState($doctor,$mensage)
	{
		
	}

	
}
