<?php

class PatientsController extends \BaseController
{	
	public function index()
    {
       if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');


        $patients = DB::table('doctors')                  
                      ->join('doctor_patient', 'doctors.id', '=', 'doctor_patient.doctor_id')
                      ->join('patients', 'doctor_patient.patient_id', '=', 'patients.id')                      
                      ->join('users', 'patients.user_id', '=', 'users.id');

        if(Input::get("motivo")!="" or Input::get("star")!="" or Input::get("end")!=""){
            $patients = $patients->join('appointments', 'patients.id', '=', 'appointments.patient_id');  
        }

        if(Input::get("specialty")!=""){
            $patients = $patients->join('specialties', 'doctors.specialty_id', '=', 'specialties.id');  
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
        $patients = $patients->where('doctors.id', Doctor::doctorLogin());
        $patients = $patients->groupBy('patients.id')->get();

        return View::make('clinic.doctor.patients.patients')->with('patients', $patients);
    }

	public function getPatientAppointments($patient)
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

    	$doctor = Doctor::doctorLogin();
        $Agenda = Agenda::where('doctor_id', $doctor)->first();
        $Appointment = Appointment::where('patient_id', $patient)->where('agenda_id', $Agenda->id)->get();
        return View::make('clinic.doctor.patients.pending')->with('Appointment', $Appointment);
    }

    public function getPatientAppointmentsHistory($patient)
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        $doctor = Doctor::doctorLogin();
        $Agenda = Agenda::where('doctor_id', $doctor)->first();
        $Appointment = Appointment::where('patient_id', $patient)->where('agenda_id', $Agenda->id)->get();
        return View::make('clinic.doctor.patients.history')->with('Appointment', $Appointment);
    }

    public function getConfigDay()
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        $doctor = Doctor::doctorLogin();
        $agenda = Agenda::where('doctor_id', $doctor)->first()->id;
        $configDay = Configday::where('agenda_id', $agenda)->get();
        if($configDay->isEmpty())
        {
        return View::make('clinic.doctor.config.ConfigDay')
                   ->with('agenda', $agenda);
        }else{
        return View::make('clinic.doctor.config.ConfigDay')
                   ->with('agenda', $agenda)
                   ->with('configDay', $configDay);   
        }
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
}