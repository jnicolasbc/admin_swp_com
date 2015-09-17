<?php
use Carbon\Carbon;
use \Api\V1\Helpers as H;
class ClinicDoctorsController extends \BaseController {

    public function index()
    {
      if(Payment::VeryPayment()==false)
             return View::make('clinic.payment.renews-payment');

          $user    = Sentry::getUser();
      $clinic  = Clinic::where('user_id',$user->id)->first();
        $doctors = Doctor::where('clinic_id',$clinic->id)->get();
      return View::make('clinic.doctors.index',['doctors'=>$doctors]);

    }

    public function create()
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        return View::make('clinic.doctors.new');
    }

    public function store()
    {
        $data = array(
            "first_name"     =>  Input::get("first_name"),
            "last_name"      =>  Input::get("last_name"),
            "email"          =>  Input::get("email"),
            "username"       =>  Input::get("username"),
            "phone"          =>  Input::get("phone"),
            "picture"        =>  Input::file("picture"),
            "password"       =>  Input::get("password"),
            "password_confirmation"     =>  Input::get("password_confirmation"),
        );
    
        $rules = array(
            "first_name"     =>  'required|min:1|max:255',
            "last_name"      =>  'required|min:1|max:100',
            "email"          =>  'required|email|unique:users',
            "username"       =>  'required|min:5|unique:users',
            "phone"          =>  'required|min:1|max:100',
            "picture"        =>  'mimes:jpeg,gif,png',
            'password'       =>  'confirmed|min:6',
        );

        $messages = array(
            'required'  => 'El campo :attribute es obligatorio.',
            'min'       => 'El campo :attribute no puede tener menos de :min carácteres.',
            'email'     => 'El campo :attribute debe ser un email válido.',
            'max'       => 'El campo :attribute no puede tener más de :max carácteres.',
            'numeric'   => 'El campo :attribute debe contener solo numeros',
            'mimes'     => 'El formato de la imagen logo debe ser jpg, git, png',
            'unique'    => 'El :attribute ingresado ya esta siendo usaddo por otro usuario.',
            'confirmed' => 'La confirmación del :attribute no coincide',

        );
    
        $validation = Validator::make(Input::all(), $rules, $messages);
             //si la validación falla redirigimos al formulario de registro con los errores
            //y con los campos que nos habia llenado el usuario
        if ($validation->fails())
        {
            return Redirect::to('/clinic/doctors/create')->withErrors($validation)->withInput();
        }
        else
        {   
            $user = new User;
            $user->first_name = Input::get("first_name");
            $user->last_name  = Input::get("last_name");
            $user->email      = Input::get("email");
            $user->username   = Input::get("username");
            $user->password   = Input::get("password");
            $user->save();

            $doctorUser    = Sentry::getUserProvider()->findByLogin($user->email);
            $doctorsyGroup = Sentry::getGroupProvider()->findByName('Doctors');
            $doctorUser->addGroup($doctorsyGroup);
            
            $doctor = new Doctor;
            $doctor->user_id      = $user->id;
            $doctor->clinic_id    = Clinic::getClinicId();
            $doctor->specialty_id = Input::get("specialty_id");

            if(Input::file('picture')!=NULL)
            {
                //agrega imagen de logo
                $file_logo=Input::file('picture');
                $ext = Input::file('picture')->getClientOriginalExtension();
                $nameIMG=date('YmdHis');
                $logo=$nameIMG.'.'.$ext;
                $logo= 'assets/doctor/images/profile_pic/profile_'.$logo;
                $file_logo->move("assets/doctor/images/profile_pic/",$logo);                 
                $doctor->picture = $logo;
            }

            $doctor->save();

            $clinic = Clinic::find(Clinic::getClinicId());
            $address_id = $clinic->address_id;

            $agenda = new Agenda;
            $agenda->doctor_id = $doctor->id;
            $agenda->name = "Agenda ".Input::get("first_name").' '.Input::get("last_name");
            $agenda->description = 'Nuevo';
            $agenda->save();

            $busines = new Business;
            $busines->doctor_id    = $doctor->id;
            $busines->agenda_id    = $agenda->id;
            $busines->addresses_id = $address_id;
            $busines->name = 'Local # ';
            $busines->save();

            if(!$logo){ $logo ="";};

            $profile = new Profile;
            $profile->user_id = $user->id;
            $profile->lang    = Input::get("lang");
            $profile->phone   = Input::get("phone");
            $profile->picture = $logo;
            $profile->save();

            if($profile){                
                return Redirect::to('/clinic/doctors/')->withFlash_message("Guardado Exitosamente");
            }
            else
            {
              return Redirect::to('/clinic/doctors/create')->withErrors("Error")->withInput();      
            }
         
        }
    }

  public function edit($id)
  {
    if(Payment::VeryPayment()==false)
       return View::make('clinic.payment.renews-payment');

    $user = Sentry::getUser();
    $clinic = Clinic::where('user_id',$user->id)->first();
    $doctor = Doctor::where('clinic_id',$clinic->id)->where('id',$id)->first();
    $agenda = Agenda::where('doctor_id',$doctor->id)->first();
    $esps = explode(',', $doctor->specialty_id);
    $espok = '';

    $array = array();
    foreach($esps as $esp){
      $very = Specialty::where('id', $esp)->first();
      if($very){
        $array[] =array('value'=>$very->id,'text'=>$very->name_es);
      }
    }

    $profile = Profile::where('user_id',$doctor->user_id)->first();

    return View::make('clinic.doctors.edit',['agenda'=>$agenda, 'doctor'=>$doctor, 'profile'=>$profile, 'specialty'=>$array]);
  }

  public function update($id)
  {
        $data = array(
            "first_name"     =>  Input::get("first_name"),
            "last_name"      =>  Input::get("last_name"),
            "email"          =>  Input::get("email"),
            "phone"          =>  Input::get("phone"),
            "picture"        =>  Input::file("picture"),
            "specialty_id"   =>  Input::get("specialty_id"),
            "dating_duration"   =>  Input::get("dating_duration")
        );
    
        $rules = array(
            "first_name"      =>  'required|min:1|max:255',
            "last_name"       =>  'required|min:1|max:100',
            "email"           =>  'required|min:1',
            "phone"           =>  'required|min:1|max:100',
            "specialty_id"    =>  'required|min:3|max:255',
            "dating_duration" =>  'required|min:1|max:3',
            "picture"         =>  'mimes:jpeg,gif,png'
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
            return Redirect::to('/clinic/doctors/'.$id.'/edit')->withErrors($validation)->withInput();
        }
        else
        { 
            $doctor = Doctor::find($id);
            $agenda = Agenda::where('doctor_id',$doctor->id)->first();
            $agenda->dating_duration = Input::get("dating_duration");
            $agenda->save();
             
            $doctor->specialty_id = Input::get("specialty_id");
            $doctor->save();
            $user = User::find($doctor->user_id);
            $user->first_name=$data['first_name'];
            $user->last_name=$data['last_name'];
            $user->save();

            $profile = Profile::where('user_id', $doctor->user_id)->first();

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
                return Redirect::to('/clinic/doctors/')->withFlash_message("Guardado Exitosamente");
            }
            else
            {
              return Redirect::to('/clinic/doctors')->withErrors("Error")->withInput();      
            }
         
        }
  }

    public function destroy($id)
    {
      $doctor = Doctor::find($id);
      $userId   = $doctor->user_id;
      $doctor->delete();
      $profile  = Profile::where('user_id',  $userId)->first();
      $profile->delete();
      $user = User::find( $userId);
      $user->delete();

      if($user){
        return Redirect::back()->withConfirm("Eliminado Exitosamente");
      }else{
        return Redirect::back()->withErrors("Error");
      }
    }

    public function getDoctorStatus()
    {
        if(Request::ajax()){
            $doctor = Doctor::find(Input::get("id"));
            if($doctor->state==1){
             $doctor->state = 0;
            }else{
             $doctor->state = 1; 
            }
            $doctor->save();
            if($doctor){ 
            return Response::json(array(
                  'success'  => true
            )); 
            }else{ 
              return Response::json(array(
                    'success'  => false
              ));
            } 
        }
    }

    public function getDoctorConfirm()
    {
        if(Request::ajax()){
            $age = Agenda::find(Input::get("id"));
            if($age->appointment_state==1){
             $age->appointment_state = 0;
            }else{
             $age->appointment_state = 1; 
            }
            $age->save();
            if($age){ 
            return Response::json(array(
                  'success'  => true
            )); 
            }else{ 
              return Response::json(array(
                    'success'  => false
              ));
            } 
        }
    }
    public function getDoctorPatients($id)
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

            $patients = DoctorPatient::where('doctor_id', $id)->get();
            return View::make('clinic.doctors.patients')->with('patients', $patients);    
    }

    public function getDoctorPatientsAppointmentsEdit()
    {
        $id = Input::get("id");
        $cita = Appointment::find($id);
        $cita->state = "canceled";
        $cita->save();
        return Redirect::back();
    }

    public function getDoctorAppointments($id)
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        $agenda = Agenda::where('doctor_id', $id)->first();

        $dt = Carbon::now();
        if($dt->dayOfWeek==0){$dia = 'Sunday';}
        elseif($dt->dayOfWeek==1){$dia = 'Monday';}
        elseif($dt->dayOfWeek==2){$dia = 'Tuesday';}
        elseif($dt->dayOfWeek==3){$dia = 'Wednesday';}
        elseif($dt->dayOfWeek==4){$dia = 'Thursday';}
        elseif($dt->dayOfWeek==5){$dia = 'Friday';}
        else{$dia = 'Saturday';}
        
        $CustomDay = CustomDay::where('agenda_id', $agenda->id)->get();
        $Configday = Configday::where('day', $dia)->where('agenda_id', $agenda->id)->first();
        $AgendaAppo = Appointment::where('agenda_id', $agenda->id)->get();
        return View::make('clinic.doctors.Appointment')
                   ->with('agendaAppo', $AgendaAppo)
                   ->with('configday', $Configday)
                   ->with('customddays', $CustomDay)
                   ->with('agenda', $agenda);
    }

    public function getDoctorAgenda($id)
    {
       if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        $agenda = Agenda::where('doctor_id', $id)->first();
        $dt = Carbon::now();
        $bussi = Business::where('agenda_id', $agenda->id)->first()->id;
        if($dt->dayOfWeek==0){$dia = 'Sunday';}
        elseif($dt->dayOfWeek==1){$dia = 'Monday';}
        elseif($dt->dayOfWeek==2){$dia = 'Tuesday';}
        elseif($dt->dayOfWeek==3){$dia = 'Wednesday';}
        elseif($dt->dayOfWeek==4){$dia = 'Thursday';}
        elseif($dt->dayOfWeek==5){$dia = 'Friday';}
        else{$dia = 'Saturday';}
        
        $CustomDay = CustomDay::where('agenda_id', $agenda->id)->get();
        $Configday = Configday::where('day', $dia)->where('agenda_id', $agenda->id)->first();
        $AgendaAppo = Appointment::where('agenda_id', $agenda->id)->get();
        return View::make('clinic.doctors.Appointments')
                   ->with('agendaAppo', $AgendaAppo)
                   ->with('configday', $Configday)
                   ->with('customddays', $CustomDay)
                   ->with('bussi', $bussi)
                   ->with('agenda', $agenda);
    }

    public function getEditAppointments()
    {
        $id = Input::get("cita_id");
        $AgendaAppo = Appointment::find($id);
        $AgendaAppo->state = Input::get("state");
        
        if(Input::get("state")=='in-progress')
        {
          $Appos = Appointment::where('agenda_id',  $AgendaAppo->agenda_id)->where('state',  Input::get("state"))->get();
          foreach($Appos as $Appo){
            if($Appo){
              $Appo->state='served';
              $Appo->save();
            }
          }

        }

        if(Input::get("state")=='delayed')
        {
         $star = explode(' ', $AgendaAppo->start_date);
         $stardate = explode('-', $star[0]);
         $startime = explode(':', $star[1]);
         $dt = Carbon::create($stardate[0], $stardate[1], $stardate[2], $startime[0], $startime[1], $startime[2]);
         $AgendaAppo->last_time =  $dt->addMinutes(Input::get("time")); 
        }

        $AgendaAppo->save();
        return Redirect::back();
    }

    public function postaddAppointments()
    {
        if(Request::ajax()){

            $data = array(
                 "agenda_id"      =>  Input::get("agenda_id"),
                 "patient"        =>  Input::get("patient"),
                 "reason"         =>  Input::get("reason"),
                 "fecha"          =>  Input::get("fecha"),
                 "start"          =>  Input::get("start"),
                 "end"            =>  Input::get("end"),
      
             );
         
             $rules = array(
                 "agenda_id"      =>  'required',
                 "patient"        =>  'required|min:2|max:100',
                 "reason"         =>  'required|min:2|max:200',
                 "fecha"          =>  'required|min:1|max:100',
                 "start"          =>  'required|min:1|max:100',
                 "end"            =>  'required|min:1|max:100',
       
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
             if ($validation->fails())
             {
                return Response::json(array(
                    'success' => false,
                    'errors' => $validation->getMessageBag()->toArray()
                ));
             }else{
                $agenda = Agenda::find(Input::get('agenda_id'));
                 $user = User::where('email', Input::get('patient'))->orWhere('username', Input::get('patient'))->first();
                 if($user){

                    $patient = Patient::where('user_id', $user->id)->first();
                    if(!$patient){
                        $patient = new Patient;
                        $patient->user_id = $user->id;
                        $patient->save();
                    }

                    $docPatient = DoctorPatient::where('patient_id', $patient->id)->where('doctor_id', $agenda->doctor_id)->first();
                    if(!$docPatient){
                       $docPatient = new DoctorPatient;
                       $docPatient->patient_id = $patient->id;
                       $docPatient->doctor_id = $agenda->doctor_id;
                       $docPatient->save();
                    }
                    
                    $apo = new Appointment;
                    $apo->patient_id = $patient->id;
                    $apo->agenda_id = $agenda->id;
                    $apo->day = Input::get("fecha");
                    $apo->start_date = Input::get('start');
                    $apo->end_date = Input::get('end');
                    $apo->reason = Input::get('reason');
                    if($agenda->appointment_state==0)
                       $apo->state = 'confirmed';
                    else
                       $apo->state = 'pending';
                     
                    $apo->save();

                    if($apo){
                       if($agenda->appointment_state==0){
                          $mgs = new MgsAppointment;
                          $mgs->appointment_id = $apo->id;
                          $mgs->text = "Su cita fue Confirmada";
                          $mgs->save();
                       }

                       return Response::json(array(
                          'success'       =>  true
                       ));
                    }


                 }else{
                    return Response::json(array(
                     'success' => false,
                     'errors' => 'El usuario no existe'
                    )); 
                 }                 
            }
        }
    }

    public function getEditAjaxAppointments()
    {
        if(Request::ajax()){
            //validamos el formulario
            $registerData = array(
                'start'   =>    Input::get('start'),
                'end'     =>    Input::get('end')
            );

            $rules = array(
                'start'        => 'required|min:2',
                'end'          => 'required|min:2'
            );

            $messages = array(
                'required'        => 'El campo :attribute es obligatorio.',
                'min'             => 'El campo :attribute no puede tener menos de :min carácteres.'
            );

            $validation = Validator::make($registerData, $rules, $messages);
            //si la validación falla redirigimos al formulario de registro con los errores   
            if ($validation->fails()){
                //como ha fallado el formulario, devolvemos los datos en formato json
                //esta es la forma de hacerlo en laravel, o una de ellas
                return Response::json(array(
                    'success' => false,
                    'errors' => $validation->getMessageBag()->toArray()
                ));
                //en otro caso ingresamos al usuario en la tabla usuarios
            }else{
                $dt = Carbon::now();
                if(Input::get("start")<=$dt)
                {
                 return Response::json(array(
                    'success' => false
                ));
                } 
                $id = Input::get("id");
                $AgendaAppo = Appointment::find($id);
                $AgendaAppo->state      = "delayed";
                $AgendaAppo->start_date = Input::get("start");
                $AgendaAppo->end_date   = Input::get("end");
                $AgendaAppo->save();
                return Response::json(array(
                    'success' => true
                ));

            }
        }
    }

    public function getConfigDay($id)
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        $agenda = Agenda::where('doctor_id', $id)->first()->id;

        $configDay0 = Configday::where('agenda_id', $agenda)->where('day', 'Sunday')->first();
        $configDay1 = Configday::where('agenda_id', $agenda)->where('day', 'Monday')->first();
        $configDay2 = Configday::where('agenda_id', $agenda)->where('day', 'Tuesday')->first();
        $configDay3 = Configday::where('agenda_id', $agenda)->where('day', 'Wednesday')->first();
        $configDay4 = Configday::where('agenda_id', $agenda)->where('day', 'Thursday')->first();
        $configDay5 = Configday::where('agenda_id', $agenda)->where('day', 'Friday')->first();
        $configDay6 = Configday::where('agenda_id', $agenda)->where('day', 'Saturday')->first();
        return View::make('clinic.doctors.ConfigDay')
                   ->with('agenda', $agenda)
                   ->with('configDay0', $configDay0)
                   ->with('configDay1', $configDay1)
                   ->with('configDay2', $configDay2)
                   ->with('configDay3', $configDay3)
                   ->with('configDay4', $configDay4)
                   ->with('configDay5', $configDay5)
                   ->with('configDay6', $configDay6);
    }

    public function postConfigDaySave()
    {
        /*-------------------Domingo-----------------------------*/
        $configDay = Configday::where('day', 'Sunday')->where('agenda_id', Input::get("agenda"))->first();
        if(!$configDay)
            $configDay = new Configday;

        $configDay->agenda_id    = Input::get("agenda");
        $configDay->day          = 'Sunday';
        $configDay->starttime_am = CustomDay::eliminarespacios(Input::get("starttime_am"));
        $configDay->endtime_am   = CustomDay::eliminarespacios(Input::get("endtime_am"));
        $configDay->lunch_start  = CustomDay::eliminarespacios(Input::get("lunch_start"));
        $configDay->lunch_end    = CustomDay::eliminarespacios(Input::get("lunch_end"));
        $configDay->starttime_pm = CustomDay::eliminarespacios(Input::get("starttime_pm"));
        $configDay->endtime_pm   = CustomDay::eliminarespacios(Input::get("endtime_pm"));
        $configDay->is_day_off   = Input::get("is_day_off");
        $configDay->save(); 
        /*-------------------Lunes-----------------------------*/
        $configDay1 = Configday::where('day', 'Monday')->where('agenda_id', Input::get("agenda"))->first();
        if(!$configDay1)
            $configDay1 = new Configday;

        $configDay1->agenda_id    = Input::get("agenda");
        $configDay1->day          = 'Monday';
        $configDay1->starttime_am = CustomDay::eliminarespacios(Input::get("starttime_am_1"));
        $configDay1->endtime_am   = CustomDay::eliminarespacios(Input::get("endtime_am_1"));
        $configDay1->lunch_start  = CustomDay::eliminarespacios(Input::get("lunch_start_1"));
        $configDay1->lunch_end    = CustomDay::eliminarespacios(Input::get("lunch_end_1"));
        $configDay1->starttime_pm = CustomDay::eliminarespacios(Input::get("starttime_pm_1"));
        $configDay1->endtime_pm   = CustomDay::eliminarespacios(Input::get("endtime_pm_1"));
        $configDay1->is_day_off   = Input::get("is_day_off_1");
        $configDay1->save();  
        /*-------------------Martes-----------------------------*/
        $configDay2 = Configday::where('day', 'Tuesday')->where('agenda_id', Input::get("agenda"))->first();
        if(!$configDay2)
            $configDay2 = new Configday;

        $configDay2->agenda_id    = Input::get("agenda");
        $configDay2->day          = 'Tuesday';
        $configDay2->starttime_am = CustomDay::eliminarespacios(Input::get("starttime_am_2"));
        $configDay2->endtime_am   = CustomDay::eliminarespacios(Input::get("endtime_am_2"));
        $configDay2->lunch_start  = CustomDay::eliminarespacios(Input::get("lunch_start_2"));
        $configDay2->lunch_end    = CustomDay::eliminarespacios(Input::get("lunch_end_2"));
        $configDay2->starttime_pm = CustomDay::eliminarespacios(Input::get("starttime_pm_2"));
        $configDay2->endtime_pm   = CustomDay::eliminarespacios(Input::get("endtime_pm_2"));
        $configDay2->is_day_off   = Input::get("is_day_off_2");
        $configDay2->save();  
        /*-------------------Miercoles-----------------------------*/
        $configDay3 = Configday::where('day', 'Wednesday')->where('agenda_id', Input::get("agenda"))->first();
        if(!$configDay3)
            $configDay3 = new Configday;

        $configDay3->agenda_id    = Input::get("agenda");
        $configDay3->day          = 'Wednesday';
        $configDay3->starttime_am = CustomDay::eliminarespacios(Input::get("starttime_am_3"));
        $configDay3->endtime_am   = CustomDay::eliminarespacios(Input::get("endtime_am_3"));
        $configDay3->lunch_start  = CustomDay::eliminarespacios(Input::get("lunch_start_3"));
        $configDay3->lunch_end    = CustomDay::eliminarespacios(Input::get("lunch_end_3"));
        $configDay3->starttime_pm = CustomDay::eliminarespacios(Input::get("starttime_pm_3"));
        $configDay3->endtime_pm   = CustomDay::eliminarespacios(Input::get("endtime_pm_3"));
        $configDay3->is_day_off   = Input::get("is_day_off_3");
        $configDay3->save();         
        /*-------------------Jueves-----------------------------*/
        $configDay4 = Configday::where('day', 'Thursday')->where('agenda_id', Input::get("agenda"))->first();
        if(!$configDay4)
            $configDay4 = new Configday;

        $configDay4->agenda_id    = Input::get("agenda");
        $configDay4->day          = 'Thursday';
        $configDay4->starttime_am = CustomDay::eliminarespacios(Input::get("starttime_am_4"));
        $configDay4->endtime_am   = CustomDay::eliminarespacios(Input::get("endtime_am_4"));
        $configDay4->lunch_start  = CustomDay::eliminarespacios(Input::get("lunch_start_4"));
        $configDay4->lunch_end    = CustomDay::eliminarespacios(Input::get("lunch_end_4"));
        $configDay4->starttime_pm = CustomDay::eliminarespacios(Input::get("starttime_pm_4"));
        $configDay4->endtime_pm   = CustomDay::eliminarespacios(Input::get("endtime_pm_4"));
        $configDay4->is_day_off   = Input::get("is_day_off_4");
        $configDay4->save();
        /*-------------------Viernes-----------------------------*/
        $configDay5 = Configday::where('day', 'Friday')->where('agenda_id', Input::get("agenda"))->first();
        if(!$configDay5)
            $configDay5 = new Configday;

        $configDay5->agenda_id    = Input::get("agenda");
        $configDay5->day          = 'Friday';
        $configDay5->starttime_am = CustomDay::eliminarespacios(Input::get("starttime_am_5"));
        $configDay5->endtime_am   = CustomDay::eliminarespacios(Input::get("endtime_am_5"));
        $configDay5->lunch_start  = CustomDay::eliminarespacios(Input::get("lunch_start_5"));
        $configDay5->lunch_end    = CustomDay::eliminarespacios(Input::get("lunch_end_5"));
        $configDay5->starttime_pm = CustomDay::eliminarespacios(Input::get("starttime_pm_5"));
        $configDay5->endtime_pm   = CustomDay::eliminarespacios(Input::get("endtime_pm_5"));
        $configDay5->is_day_off   = Input::get("is_day_off_5");
        $configDay5->save();
        /*-------------------Sabado-----------------------------*/
        $configDay6 = Configday::where('day', 'Saturday')->where('agenda_id', Input::get("agenda"))->first();
        
        if(!$configDay6)
            $configDay6 = new Configday;

        $configDay6->agenda_id    = Input::get("agenda");
        $configDay6->day          = 'Saturday';
        $configDay6->starttime_am = CustomDay::eliminarespacios(Input::get("starttime_am_6"));
        $configDay6->endtime_am   = CustomDay::eliminarespacios(Input::get("endtime_am_6"));
        $configDay6->lunch_start  = CustomDay::eliminarespacios(Input::get("lunch_start_6"));
        $configDay6->lunch_end    = CustomDay::eliminarespacios(Input::get("lunch_end_6"));
        $configDay6->starttime_pm = CustomDay::eliminarespacios(Input::get("starttime_pm_6"));
        $configDay6->endtime_pm   = CustomDay::eliminarespacios(Input::get("endtime_pm_6"));
        $configDay6->is_day_off   = Input::get("is_day_off_6");
        $configDay6->save();

        return Redirect::back();
    }

    public function getPatientAppointments($doctor, $patient)
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        $Agenda = Agenda::where('doctor_id', $doctor)->first();
        $Appointment = Appointment::where('patient_id', $patient)->where('agenda_id', $Agenda->id)->get();
        return View::make('clinic.doctors.pending')->with('Appointment', $Appointment);
    }

    public function getPatientAppointmentsHistory($doctor, $patient)
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');
       
        $Agenda = Agenda::where('doctor_id', $doctor)->first();
        $Appointment = Appointment::where('patient_id', $patient)->where('agenda_id', $Agenda->id)->get();
        return View::make('clinic.doctors.history')->with('Appointment', $Appointment);
    }

    public function postaddPatient()
    {
        if(Request::ajax()){

            $data = array(
                 "agenda_id"      =>  Input::get("agenda_id"),
                 "first_name"     =>  Input::get("first_name"),
                 "last_name"      =>  Input::get("last_name"),
                 "email"          =>  Input::get("email")
      
             );
         
             $rules = array(
                 "agenda_id"      =>  'required',
                 "first_name"     =>  'required|min:2|max:50',
                 "last_name"      =>  'required|min:2|max:50',
                 "email"          =>  'required|email|min:1|max:50',
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
             if ($validation->fails())
             {
                return Response::json(array(
                    'success' => false,
                    'errors' => $validation->getMessageBag()->toArray()
                ));
             }else{
                 $password = $this->generaPass();
                 $agenda = Agenda::find(Input::get('agenda_id'));
                 $user = new User;
                 $user->first_name = Input::get("first_name");
                 $user->last_name = Input::get("last_name");
                 $user->email = Input::get("email");
                 $user->password = $password;
                 $user->username = Input::get("first_name").Input::get("last_name");
                 $user->activated = 1;
                 $user->save();
                 if($user){
                    
                    $doctorUser    = Sentry::getUserProvider()->findByLogin($user->email);
                    $doctorsyGroup = Sentry::getGroupProvider()->findByName('Patients');
                    $doctorUser->addGroup($doctorsyGroup);

                    $profile = new Profile;
                    $profile->user_id = $user->id;
                    $profile->save();

                    $patient = new Patient;
                    $patient->user_id = $user->id;
                    $patient->save();

                    $docPatient = new DoctorPatient;
                    $docPatient->patient_id = $patient->id;
                    $docPatient->doctor_id = $agenda->doctor_id;
                    $docPatient->save();

                    $subject = 'Registro por Clinica | smartdoctorappointments.com';
                    $name  = $user->first_name;
                    $email = $user->email;
      
                    $data = array(
                       'name' => $user->first_name." ".$user->last_name,
                       'password' => $password,
                       'email' => $user->email

                    );

                    Mail::send('email.userpatiendadd',  $data, function($message) use ($name, $email, $subject)
                    {
                        $message->to($email, $name);
                        $message->subject($subject);
                    });

                    return Response::json(array(
                       'success' =>  true,
                       'email' => Input::get("email")
                    ));
                 }else{
                    return Response::json(array(
                     'success' => false,
                     'errors' => 'Error al registrar este usuario.'
                    )); 
                 }                 
            }
        }
    }

    function generaPass(){
      //Se define una cadena de caractares. Te recomiendo que uses esta.
      $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
      //Obtenemos la longitud de la cadena de caracteres
      $longitudCadena=strlen($cadena);
       
      //Se define la variable que va a contener la contraseña
      $pass = "";
      //Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
      $longitudPass=10;
       
      //Creamos la contraseña
      for($i=1 ; $i<=$longitudPass ; $i++){
          //Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
          $pos=rand(0,$longitudCadena-1);
       
          //Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
          $pass .= substr($cadena,$pos,1);
      }
      return $pass;
    } 
}