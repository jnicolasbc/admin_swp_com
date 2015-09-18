<?php

class ClinicController extends \BaseController {


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getHome()
	{
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');
        else 
		   return View::make('clinic.dashboard.index');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getConfig()
	{
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');
		$user        = Sentry::getUser();
		$clinic      = Clinic::where('user_id',$user->id)->first();
    $adress      = Address::find($clinic->address_id);
    $province_id = City::find($adress->city_id)->province_id;
    $country_id  = Province::find($province_id)->country_id;
    $option      = $clinic->insurances;
		
    #$option      = Option::where('name', $clinic->id.'-clinic-insurance')->first();
    $array = array();
    if($option){
      $segs = explode(',',$option);
      $segok = '';      
      foreach($segs as $seg){
        $very = Insurance::where('id', $seg)->first();
        if($very){
          $array[]=array('value'=>$very->id,'text'=>$very->name);
        }
      }
    }
    /**/
    
    $optionLang  = Option::where('name', $clinic->id.'-clinic-lang')->first();

        if(!$option){
		     return View::make('clinic.config.config')->with('country_id',$country_id)->with('clinic', $clinic)->with('adress', $adress);
	    }else{
             return View::make('clinic.config.config')
                        ->with('clinic', $clinic)
                        ->with('option', $array)
                        ->with('adress', $adress)
                        ->with('country_id',$country_id)
                        ->with('optionLang', $optionLang);
	    }
	}

	public function postConfigSave()
	{
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');
       
		    $data = array(
            "name"      =>  Input::get("name"),
            "insurance" =>  Input::get("insurance"),
            "lang"      =>  Input::get("lang"),
            "picture"   =>  Input::file("picture"),
            "phone"     =>  Input::get("phone"),
        );
    
        $rules = array(
            "name"      =>  'required|min:1|max:255',
            "insurance" =>  'required|min:1|max:255',
            "lang"      =>  'required|min:1|max:10',
            "picture"   =>  'mimes:jpeg,gif,png',
            "phone"     =>  'required|numeric|min:1',
        );

        $messages = array(
            'required'  => 'El campo :attribute es obligatorio.',
            'min'       => 'El campo :attribute no puede tener menos de :min carácteres.',
            'email'     => 'El campo :attribute debe ser un email válido.',
            'max'       => 'El campo :attribute no puede tener más de :max carácteres.',
            'numeric'   => 'El campo :attribute debe contener solo numeros',
            'mimes'     => 'El formato de la imagen :attribute debe ser jpg, git, png',
        );

        $validation = Validator::make(Input::all(), $rules, $messages);
             //si la validación falla redirigimos al formulario de registro con los errores
            //y con los campos que nos habia llenado el usuario
        if ($validation->fails()){
            return Redirect::to('/clinic/config-data/')->withErrors($validation)->withInput();
        }else{ 
            $id =Input::get("id");
            $clinic = Clinic::find($id);
            $clinic->name = Input::get("name");
            $clinic->phone = Input::get("phone");
            $clinic->insurances = Input::get("insurance");

            if(Input::file('picture')!=NULL)
            {
                //agrega imagen de logo
                $file_logo=Input::file('picture');
                $ext = Input::file('picture')->getClientOriginalExtension();
                $nameIMG=date('YmdHis');
                $logo=$nameIMG.'.'.$ext;
                $logo= 'assets/clinic/images/logo/logo_'.$logo;
                $file_logo->move("assets/clinic/images/logo/",$logo);
                $clinic->picture = $logo;
            }

            $clinic->save();
            
            $adress = Address::find($clinic->address_id);
            $adress->my_address = Input::get("address");
            $adress->save();

            $lang = Option::where('name', $clinic->id.'-clinic-lang')->first();
              if($lang){
                  $lang->key = Input::get("lang");
                  $lang->save();
              }else{
                  $langadd = new Option;
                  $langadd->key = Input::get("lang");
                  $langadd->name = $clinic->id.'-clinic-lang';
                  $langadd->save();
              }

            return Redirect::back();
             
            }
    }	  
                 
    public function getPatients()
    {
       if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');
        
        $user        = Sentry::getUser();

        $patients = DB::table('clinics')
                      ->join('doctors', 'clinics.id', '=', 'doctors.clinic_id')                    
                      ->join('doctor_patient', 'doctors.id', '=', 'doctor_patient.doctor_id')
                      ->join('patients', 'doctor_patient.patient_id', '=', 'patients.id')                      
                      ->join('users', 'patients.user_id', '=', 'users.id');

        if(Input::get("motivo")!="" or Input::get("star")!="" or Input::get("end")!=""){
            $patients = $patients->join('appointments', 'patients.id', '=', 'appointments.patient_id');  
        }

        if(Input::get("specialty")!=""){
            $patients = $patients->join('specialties', 'doctors.specialty_id', '=', 'specialties.id');  
        }

        if(Input::get("doctor")!=""){
            $patients = $patients->where('doctors.id', Input::get("doctor"));
        }

        if(Input::get("motivo")!=""){
            $patients = $patients->where('appointments.reason', 'LIKE', '%'.Input::get("motivo").'%');
        }

        if(Input::get("specialty")!=""){
            $patients = $patients->where('doctors.specialty_id', Input::get("specialty"));
        }

        if(Input::get("star")!=""){
            $startpart = explode('-',Input::get("star"));
            $start = $startpart[2].'/'.$startpart[1].'/'.$startpart[0];
            $patients = $patients->where('appointments.day','>',$start);
        }

        if(Input::get("star")!="" and Input::get("end")!=""){
            $endpart = explode('-',Input::get("end"));
            $end = $endpart[2].'/'.$endpart[1].'/'.$endpart[0];
            $patients = $patients->where('appointments.day','<',$end);
        }

        if(Input::get("seguro")!=""){
            $patients = $patients->where('patients.insurance_id',Input::get("seguro"));
        }

        $patients = $patients->groupBy('patients.id')->get();

        return View::make('clinic.patients.patients')->with('patients', $patients);
    }

    public function getConfirmationAppointments(){
        $user        = Sentry::getUser();
        $pcitas   = DB::table('clinics')
                      ->join('doctors', 'clinics.id', '=', 'doctors.clinic_id')                    
                      ->join('doctor_patient', 'doctors.id', '=', 'doctor_patient.doctor_id')
                      ->join('patients', 'doctor_patient.patient_id', '=', 'patients.id')                      
                      ->join('users', 'patients.user_id', '=', 'users.id') 
                      ->join('appointments', 'patients.id', '=', 'appointments.patient_id')
                      ->where('clinics.user_id', $user->id)
                      ->where('appointments.state', 'pending')
                      ->groupBy('appointments.id')->get();

        return View::make('clinic.appointments.ConfirmationAppointments')->with('citas', $pcitas);
    }

    public function getConfirmationAppointment(){
       $id = Input::get("cita_id");
       $cita = Appointment::find($id);
       $cita->state = 'confirmed';
       $cita->save();
       if($cita){
         $mgs = new MgsAppointment;
         $mgs->appointment_id = $id;
         $mgs->text = "Su cita fue Confirmada";
         $mgs->save();
       }

       return Redirect::back()->withFlashMessage('Cita Confirmada');
    }

    public function getCancelAppointment(){
       $id = Input::get("cita_id");
       $cita = Appointment::find($id);
       $cita->state = 'not-accepted';
       $cita->save();
       if($cita){
         $mgs = new MgsAppointment;
         $mgs->appointment_id = $id;
         $mgs->text = "Su cita no fue aceptada";
         $mgs->save();
       }
       

       return Redirect::back()->withFlashMessage('Cita Cancelada');
    }

    public function getCancelsAppointments(){
      $user        = Sentry::getUser();
        $pcitas   = DB::table('clinics')
                      ->join('doctors', 'clinics.id', '=', 'doctors.clinic_id')                    
                      ->join('doctor_patient', 'doctors.id', '=', 'doctor_patient.doctor_id')
                      ->join('patients', 'doctor_patient.patient_id', '=', 'patients.id')                      
                      ->join('users', 'patients.user_id', '=', 'users.id') 
                      ->join('appointments', 'patients.id', '=', 'appointments.patient_id')
                      ->where('clinics.user_id', $user->id)
                      ->where('appointments.state', 'canceled')
                      ->groupBy('appointments.id')->get();

        return View::make('clinic.appointments.CancelAppointments')->with('citas', $pcitas);
    }

    public function getAgendas()
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        $user    = Sentry::getUser();
        $clinic  = Clinic::where('user_id',$user->id)->first();
        $doctors = Doctor::where('clinic_id',$clinic->id)->get();
        return View::make('clinic.Agendas.agenda',['doctors'=>$doctors]);
    }

    public function getAdminProfile()
    {
      $user    = Sentry::getUser();
      $profile = Profile::where('user_id',$user->id)->first();
      return View::make('clinic.Profile.profile',['user'=>$user, 'profile'=>$profile]);
    }

    public function postAdminProfile()
    {
        $data = array(
            "first_name"     =>  Input::get("first_name"),
            "last_name"      =>  Input::get("last_name"),
            "email"          =>  Input::get("email"),
            "phone"          =>  Input::get("phone"),
            "picture"        =>  Input::file("picture")
        );
    
        $rules = array(
            "first_name"     =>  'required|min:1|max:255',
            "last_name"      =>  'required|min:1|max:100',
            "email"          =>  'required|min:1',
            "phone"          =>  'required|min:1|max:100',
            "picture"        =>  'mimes:jpeg,gif,png'
        );

        $messages = array(
            'required'  => 'El campo :attribute es obligatorio.',
            'min'       => 'El campo :attribute no puede tener menos de :min carácteres.',
            'email'     => 'El campo :attribute debe ser un email válido.',
            'max'       => 'El campo :attribute no puede tener más de :max carácteres.',
            'numeric'   => 'El campo :attribute debe contener solo numeros',
            'mimes'     => 'El formato de la imagen logo debe ser jpg, git, png',
        );
    
        $validation = Validator::make(Input::all(), $rules, $messages);
             //si la validación falla redirigimos al formulario de registro con los errores
            //y con los campos que nos habia llenado el usuario
        if ($validation->fails())
        {
            return Redirect::back()->withErrors($validation)->withInput();
        }
        else
        { 
            $user = Sentry::getUser();
            $user->first_name=$data['first_name'];
            $user->last_name=$data['last_name'];
            $user->save();

            $profile = Profile::where('user_id', $user->id)->first();

            if(Input::file('picture')!=NULL)
            {
                //agrega imagen de logo
                $file_logo=Input::file('picture');
                $ext = Input::file('picture')->getClientOriginalExtension();
                $nameIMG=date('YmdHis');
                $logo=$nameIMG.'.'.$ext;
                $logo= 'assets/doctor/images/profile_pic/profile_'.$logo;
                $profile->picture = $logo;
            }
            $profile->phone = Input::get("phone");
            $profile->save();
            
            if($profile){
                if(Input::file('picture')!=NULL)
                {
                    $file_logo->move("assets/doctor/images/profile_pic/",$logo); 
                } 
                return Redirect::back()->withFlash_message("Guardado Exitosamente");
            }
            else
            {
              return Redirect::back()->withErrors("Error")->withInput();      
            }
         
        }
    }

    public function getAgendasDay()
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        $user        = Sentry::getUser();
        $pcitas   = DB::table('clinics')
                      ->join('doctors', 'clinics.id', '=', 'doctors.clinic_id')
                      ->join('doctor_patient', 'doctors.id', '=', 'doctor_patient.doctor_id')
                      ->join('patients', 'doctor_patient.patient_id', '=', 'patients.id')                      
                      ->join('users', 'doctors.user_id', '=', 'users.id') 
                      ->join('appointments', 'patients.id', '=', 'appointments.patient_id')
                      ->where('clinics.user_id', $user->id)
                      ->where('appointments.day', date('Y-m-d'))
                      ->select('doctors.id as id', 'doctors.user_id as user_id', 'appointments.state as state')
                      ->groupBy('doctors.id')->get();
      
 
        return View::make('clinic.Agendas.agendaDay',['doctors'=>$pcitas]);
    }
}