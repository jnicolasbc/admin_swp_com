<?php
use Carbon\Carbon;
class AppointmentsController extends \BaseController {
	public function index()
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        $id = Doctor::doctorLogin();
        $agenda = Agenda::where('doctor_id', $id)->first();
        $dt = Carbon::now();

        $CustomDay = CustomDay::where('agenda_id', $agenda->id)->get();
        $Configday = Configday::where('day', $dt->dayOfWeek)->where('agenda_id', $agenda->id)->first();
        $AgendaAppo = Appointment::where('agenda_id', $agenda->id)->get();
        return View::make('clinic.doctor.Appointments.Appointments')
                   ->with('agendaAppo', $AgendaAppo)
                   ->with('configday', $Configday)
                   ->with('agenda', $agenda)
                   ->with('customddays', $CustomDay);
    }

    public function store()
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

    public function getmessage()
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');
       
        $data = array(
            "message"     =>  Input::get("message")
        );
    
        $rules = array(
            "message"     =>  'required|min:3|max:255'
        );

        $messages = array(
            'required'  => 'El campo :attribute es obligatorio.',
            'min'       => 'El campo :attribute no puede tener menos de :min carácteres.',
            'max'       => 'El campo :attribute no puede tener más de :max carácteres.',
        );
    
        $validation = Validator::make(Input::all(), $rules, $messages);
             //si la validación falla redirigimos al formulario de registro con los errores
            //y con los campos que nos habia llenado el usuario
        if ($validation->fails())
        {
            return Redirect::to('/doctors/profile')->withErrors($validation)->withInput();
        }
        else
        { 
           $msg = new MgsAppointment;
           $msg->appointment_id = Input::get("cita_id");
           $msg->text = Input::get("message");
           $msg->save();
           return Redirect::Back();
        }
          
    }

    public function getAgendaDay()
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');
        $id = Doctor::doctorLogin();
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
        return View::make('clinic.doctor.Appointments.AppointmentsDay')
                   ->with('agendaAppo', $AgendaAppo)
                   ->with('configday', $Configday)
                   ->with('customddays', $CustomDay)
                   ->with('bussi', $bussi)
                   ->with('agenda', $agenda);
    }

    public function getConfirmationAppointments(){
        $user        = Sentry::getUser();
        $pcitas   = DB::table('doctors')                 
                      ->join('doctor_patient', 'doctors.id', '=', 'doctor_patient.doctor_id')
                      ->join('patients', 'doctor_patient.patient_id', '=', 'patients.id')                      
                      ->join('users', 'patients.user_id', '=', 'users.id') 
                      ->join('appointments', 'patients.id', '=', 'appointments.patient_id')
                      ->where('doctors.id', Doctor::doctorLogin())
                      ->groupBy('appointments.id')->get();

        return View::make('clinic.doctor.Appointments.ConfirmationAppointments')->with('citas', $pcitas);
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
        $pcitas   = DB::table('doctors')        
                      ->join('doctor_patient', 'doctors.id', '=', 'doctor_patient.doctor_id')
                      ->join('patients', 'doctor_patient.patient_id', '=', 'patients.id')                      
                      ->join('users', 'patients.user_id', '=', 'users.id') 
                      ->join('appointments', 'patients.id', '=', 'appointments.patient_id')
                      ->where('doctors.user_id', $user->id)
                      ->where('appointments.state', 'canceled')
                      ->groupBy('appointments.id')->get();

        return View::make('clinic.doctor.Appointments.CancelAppointments')->with('citas', $pcitas);
    }

}