<?php namespace Api\V1;
use Carbon\Carbon;
use Controller,Input,Response,Sentry,Config,Session,Cache;
use Profile,Address,Province,City,Patient,User,Country,Doctor,Clinic;

class LoginController extends Controller { 
	

	function __construct()
	{
		 
	}

	public function index()
	{
		return Helpers::Mgs("Api V1.");
	}

	public function login()
	{
				
		$username = Input::get('user');
	    $password = Input::get('pass');
		$token = Input::get('token');
		try
		{


	        if(str_contains($username, '@'))
	        {
	            Config::set('cartalyst/sentry::users.login_attribute', 'email');
	            $input = array(
	                'email'    => $username,
	                'password' => $password,
	            );
	        }
	        else
	        {
	            Config::set('cartalyst/sentry::users.login_attribute', 'username');
	            $input = array(
	                'username' => $username,
	                'password' => $password,
	            );
	        }
			Sentry::authenticate($input, Input::has('remember'));
			$user = Sentry::getUser();
			$patient_id=Patient::where('user_id',$user->id)->first()->id;
			$pro=Profile::where('user_id',$user->id)->first();
			$lang=$pro->lang;
			$address = Address::where('id',$pro->address_id)->first()->city_id;
			
			$cities 	=	City::find($address);
			$city_id 	=	$cities->id;
			$city_name 	=	$cities->name;
			
			$provinces   =	Province::find($cities->province_id);
			$province_id =	$provinces->id;
		  $province_name =	$provinces->name;
			
			$countries =	Country::find($provinces->country_id);
		   $country_id =	$countries->id;
		 $country_name =	$countries->name;
			

			$token = Helpers::createTokenUser($user->id);		  	
			$mgs = array(
				'access'=>'ok',
				'token'=>$token,
				'patient_id'=>$patient_id,
				'city_id'=>$city_id,
				'city_name'=>$city_name,
				'province_id'=>$province_id,
				'province_name'=>$province_name,
				'country_id'=>$country_id,
				'country_name'=>$country_name,
				'lang'=>$lang
				);
	 

			return Helpers::Mgs($mgs);

		}
		catch(\Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
			return Helpers::Mgs("Login field is required.");
		}
		catch(\Cartalyst\Sentry\Users\PasswordRequiredException $e)
		{
			return Helpers::Mgs("Password field is required.");
		}		
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			return Helpers::Mgs("User not found.");
		}
		catch (\Cartalyst\Sentry\Users\UserNotActivatedException $e)
		{		   	
			return Helpers::Mgs("User not activated.");
		}
		catch(\Cartalyst\Sentry\Throttling\UserSuspendedException $e) 
		{
			 $time = $throttle->getSuspensionTime();
			return Helpers::Mgs("User is suspended for [$time] minutes.");
		}  
		catch (\Cartalyst\Sentry\Throttling\UserBannedException $e)
		{
			return Helpers::Mgs("User is banned.");
		}		
	}
	public function loginDoctor()
	{
		$username = Input::get('user');
	    $password = Input::get('pass');
		$token = Input::get('token');
		try
		{
	        if(str_contains($username, '@'))
	        {
	            Config::set('cartalyst/sentry::users.login_attribute', 'email');
	            $input = array(
	                'email'    => $username,
	                'password' => $password,
	            );
	        }
	        else
	        {
	            Config::set('cartalyst/sentry::users.login_attribute', 'username');
	            $input = array(
	                'username' => $username,
	                'password' => $password,
	            );
	        }
			Sentry::authenticate($input, Input::has('remember'));
			$user = Sentry::getUser();

			$doctor=Doctor::where('user_id',$user->id)->first();
			$clinic=Clinic::where('user_id',$user->id)->first();
			if($doctor){
				$token = Helpers::createTokenUser($user->id);
				$mgs = array('access'=>'ok','token'=>$token,'doctor_id'=>$doctor->id,'name_full'=>$user->first_name." ".$user->last_name);
				return Helpers::Mgs($mgs);	 
			}else if($clinic){
				$token = Helpers::createTokenUser($user->id);
				$mgs = array('access'=>'ok','token'=>$token,'clinic_id'=>$clinic->id);
				return Helpers::Mgs($mgs);	 
			}else{
				return Helpers::Mgs("Doctor not found.");
			}
			 
		}
		catch(\Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
			return Helpers::Mgs("Login field is required.");
		}
		catch(\Cartalyst\Sentry\Users\PasswordRequiredException $e)
		{
			return Helpers::Mgs("Password field is required.");
		}		
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			return Helpers::Mgs("User not found.");
		}
		catch (\Cartalyst\Sentry\Users\UserNotActivatedException $e)
		{		   	
			return Helpers::Mgs("User not activated.");
		}
		catch(\Cartalyst\Sentry\Throttling\UserSuspendedException $e) 
		{
			 $time = $throttle->getSuspensionTime();
			return Helpers::Mgs("User is suspended for [$time] minutes.");
		}  
		catch (\Cartalyst\Sentry\Throttling\UserBannedException $e)
		{
			return Helpers::Mgs("User is banned.");
		}		
	}
	public function createPatient(){
		//
		$first_name=Input::get('first_name');
		$last_name=Input::get('last_name');
		$email=Input::get('email');
		$date = Input::get('date');		
		$username = Input::get('username');
		$phone = Input::get('phone');
		$insurance = Input::get('insurance');
		$country = Input::get('country');
		$city = Input::get('city');
		$clave = Input::get('pass');
		$id = Input::get('id');
		$dni = Input::get('dni');
		$my_address=Input::get('my_address');
		$lang=Input::get('lang');		
		$base_64_picure = Input::get('picture');
		if($base_64_picure!='')
		{
			list($type, $base_64_picure) = explode(';', $base_64_picure);
			list(,$base_64_picure)  = explode(',', $base_64_picure);
			$base_64_picure = base64_decode($base_64_picure);
		}

	    try
		{	    
		    // Create the user
		    $user = Sentry::getUserProvider()->create(array(
		        'email'    => $email,
		        'password' => $clave,
		        'first_name' => $first_name,
		        'last_name' => $last_name,
		        'username' => $username,
		        'activated' => 1,
		        'gcm_id' => $id,
		        'dni'=> $dni
		    ));
		    

		    $pa = new Patient;
		    $pa->user_id=$user->id;
		    $pa->insurance_id=$insurance;
		    $pa->save();
		    $str_random = str_random(10);
			
			if($base_64_picure!='')
		    {
		    	$name_picture = '/assets/patient/images/profile_pic/'.$pa->id.'-'.$str_random.'.png';
		    	$al =public_path().'/assets/patient/images/profile_pic/'.$pa->id.'-'.$str_random.'.png';
		    	file_put_contents($al, $base_64_picure);
		    }else{
		    	$name_picture="";
		    }

		    $address= new Address;
		  	$address->city_id = $city;
		  	$address->my_address=$my_address;
		  	$address->save();

		    $profile = new Profile;
		    $profile->lang=$lang;
		    $profile->user_id=$user->id;
		    $profile->address_id=$address->id;
		    $profile->picture= $name_picture;
		    $profile->phone=$phone;
		   	$profile->date=$date;
		   	$profile->save();
		    // Find the group using the group id
		    $adminGroup = Sentry::findGroupById(1);
		    
		    // Assign the group to the user
		    $user->addGroup($adminGroup);

		    $mgs = array('txt'=>'Success registration','patient_id'=>$pa->id);
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
	 
	public function UpdatePatient(){
		
		$patient_id = Input::get('patient_id');
		$patient = Patient::find($patient_id);
		$user_id = $patient->user_id;

		$first_name=Input::get('first_name');
		$last_name=Input::get('last_name');
		$email=Input::get('email');
		$date = Input::get('date');		
		$username = Input::get('username');
		$phone = Input::get('phone');
		$insurance = Input::get('insurance');
		$country = Input::get('country');
		$city = Input::get('city');
		$clave = Input::get('pass');
		$dni = Input::get('dni');

		$address_id=Input::get('address_id');

		$my_address=Input::get('my_address');
		$lang=Input::get('lang');
		$base_64_picure = Input::get('picture');
		$state = Input::get('state');
		$state_pass = Input::get('state_pass');
		 
		if($state!="no"){
			list($type, $base_64_picure) = explode(';', $base_64_picure);
			list(,$base_64_picure)  = explode(',', $base_64_picure);
			$base_64_picure = base64_decode($base_64_picure);
		}
		

	         
		    // Create the user
		   
		    if($state_pass=='si'){
		    	$user = User::find($user_id);
		   		$user->email    = $email;
		    	$user->password = $clave;	 
		    	$user->first_name = $first_name;
			    $user->last_name = $last_name;
			    $user->username = $username;		    
			    $user->dni= $dni;	    		    
			    $user->save();		    	
		    }else{
		    	$user = User::find($user_id);
		   		 $user->email    = $email;
		    	$user->first_name = $first_name;
			    $user->last_name = $last_name;
			    $user->username = $username;		    
			    $user->dni= $dni;	    		    
			    $user->save();	
		    }
		   	    		    
		    $address= Address::find($address_id);
		  	$address->city_id = $city;
		  	$address->my_address=$my_address;
		  	$address->save();
		    
		    $pa = Patient::find($patient_id);
		    $pa->user_id=$user->id;
		    $pa->insurance_id=$insurance;
		    $pa->save();
		    $profile_id = Profile::where('user_id',$user->id)->first()->id;
		    if($state!="no"){
		    	 $str_random = str_random(10);
		    	$name_picture = '/assets/patient/images/profile_pic/'.$pa->id .'-'.$str_random.'.png';
		    	$al =public_path().'/assets/patient/images/profile_pic/'.$pa->id .'-'.$str_random.'.png';
		    	file_put_contents($al, $base_64_picure);
		    	$profile = Profile::find($profile_id);
			    $profile->lang=$lang;
			    $profile->user_id=$user->id;
			    #$profile->address_id=$address->id;
			    $profile->picture= $name_picture;
			    $profile->phone=$phone;
			   	$profile->date=$date;
			   	$profile->save();
			}else{
				$profile = Profile::find($profile_id);
			    $profile->lang=$lang;
			    $profile->user_id=$user->id;
			    #$profile->address_id=$address->id;
			    $profile->phone=$phone;
			   	$profile->date=$date;
			   	$profile->save();
			}
		    // Find the group using the group id
		    $mgs = array('txt'=>'Success registration');
		    return Helpers::Mgs($mgs);
	}
	
	public function createClinic(){
		//
		$first_name=Input::get('first_name');
		$last_name=Input::get('last_name');
		$email=Input::get('email');
		$date = Input::get('date');		
		$username = Input::get('username');
		$phone = Input::get('phone');
		$insurance = Input::get('insurance');
		$country = Input::get('country');
		$city = Input::get('city');
		$clave = Input::get('pass');
		$id = Input::get('id');
		$dni = Input::get('dni');
		$my_address=Input::get('my_address');
		$lang=Input::get('lang');
		$base_64_picure = Input::get('picture');
		
		list($type, $base_64_picure) = explode(';', $base_64_picure);
		list(,$base_64_picure)  = explode(',', $base_64_picure);
		$base_64_picure = base64_decode($base_64_picure);

	    try
		{	    
		    // Create the user
		    $user = Sentry::getUserProvider()->create(array(
		        'email'    => $email,
		        'password' => $clave,
		        'first_name' => $first_name,
		        'last_name' => $last_name,
		        'username' => $username,
		        'activated' => 1,
		        'gcm_id' => $id,
		        'dni'=> $dni
		    ));
		    

		    $pa = new Patient;
		    $pa->user_id=$user->id;
		    $pa->insurance_id=$insurance;
		    $pa->save();
		    $str_random = str_random(10);
		   
		    $name_picture = '/assets/patient/images/profile_pic/'.$pa->id.'-'.$str_random.'.png';
		    $al =public_path().'/assets/patient/images/profile_pic/'.$pa->id.'-'.$str_random.'.png';
		    file_put_contents($al, $base_64_picure);
		    
		    $address= new Address;
		  	$address->city_id = $city;
		  	$address->my_address=$my_address;
		  	$address->save();

		    $profile = new Profile;
		    $profile->lang=$lang;
		    $profile->user_id=$user->id;
		    $profile->address_id=$address->id;
		    $profile->picture= $name_picture;
		    $profile->phone=$phone;
		   	$profile->date=$date;
		   	$profile->save();
		    // Find the group using the group id
		    $adminGroup = Sentry::findGroupById(1);
		    
		    // Assign the group to the user
		    $user->addGroup($adminGroup);

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
	
}  