<?php

class DoctorsCustomDaysController extends \BaseController {
 
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        $id = Doctor::doctorLogin();
        $agenda = Agenda::where('doctor_id', $id)->first();
        $CustomDay = CustomDay::where('agenda_id', $agenda->id)->get();
        return View::make('clinic.doctor.customDay.customDay')
                   ->with('customddays', $CustomDay)
                   ->with('agendaID', $agenda->id);
	}

    public function create()
    {   
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        $id = Input::get("agendaID");
        return View::make('clinic.doctor.customDay.customDayAdd')->with('agendaID', $id);
    }



    public function store()
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        $data = array(
            "day"          =>  Input::get("day"),
            "startmorning" =>  Input::get("startmorning"),
            "endmornig"    =>  Input::get("endmornig"),
            "startlaunch"  =>  Input::get("startlaunch"),
            "endlaunch"    =>  Input::get("endlaunch"),
            "starttarde"   =>  Input::get("starttarde"),
            "endtarde"     =>  Input::get("endtarde"),
            "state"        =>  Input::get("state")
        );

        $rules = array(
            "day"          =>  'required|min:1|max:255'
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
            return Redirect::to('/doctor/custom-days/create')->withErrors($validation)->withInput();
        }
        else
        {   
            $customDay = new CustomDay;
            $customDay->agenda_id    = Input::get("id");
            $customDay->day          = Input::get("day");
            $customDay->starttime_am = CustomDay::eliminarespacios(Input::get("startmorning"));
            $customDay->endtime_am   = CustomDay::eliminarespacios(Input::get("endmornig"));
            $customDay->lunch_start  = CustomDay::eliminarespacios(Input::get("startlaunch"));
            $customDay->lunch_end    = CustomDay::eliminarespacios(Input::get("endlaunch"));
            $customDay->starttime_pm = CustomDay::eliminarespacios(Input::get("starttarde"));
            $customDay->endtime_pm   = CustomDay::eliminarespacios(Input::get("endtarde"));
            $customDay->is_day_off   = Input::get("state");
            $customDay->save();

            if($customDay){
                return Redirect::to('/doctor/custom-days/')->withFlash_message("Guardado Exitosamente");
            }
            else
            {
              return Redirect::to('/doctor/custom-days/create')->withErrors("Error")->withInput();      
            }
         
        }
    }

    public function edit($id)
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        $CustomDay = CustomDay::find($id);
        return View::make('clinic.doctor.customDay.customDayEdit')->with('customDay', $CustomDay);

    }

    
    public function update($id)
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        $data = array(
            "day"          =>  Input::get("day"),
            "starttime_am" =>  Input::get("starttime_am"),
            "endttime_am"  =>  Input::get("endtime_am"),
            "lunch_start"  =>  Input::get("lunch_startlunch_start"),
            "lunch_end"    =>  Input::get("lunch_startlunch_end"),
            "starttime_pm" =>  Input::get("starttime_pm"),
            "endtime_pm"   =>  Input::get("endtime_pm"),
            "is_day_off"   =>  Input::get("is_day_off")
        );

        $rules = array(
            "day"          =>  'required|min:1|max:255'
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
            return Redirect::to('/doctor/custom-days/'.$id.'/edit')->withErrors($validation)->withInput();
        }
        else
        {   
            $customDay = CustomDay::find($id);
            $customDay->agenda_id    = Input::get("agenda_id");
            $customDay->day          = Input::get("day");
            $customDay->starttime_am = CustomDay::eliminarespacios(Input::get("starttime_am"));
            $customDay->endtime_am   = CustomDay::eliminarespacios(Input::get("endtime_am"));
            $customDay->lunch_start  = CustomDay::eliminarespacios(Input::get("lunch_start"));
            $customDay->lunch_end    = CustomDay::eliminarespacios(Input::get("lunch_end"));
            $customDay->starttime_pm = CustomDay::eliminarespacios(Input::get("starttime_pm"));
            $customDay->endtime_pm   = CustomDay::eliminarespacios(Input::get("endtime_pm"));
            $customDay->is_day_off   = Input::get("is_day_off");
            $customDay->save();

            if($customDay){
                return Redirect::to('/doctor/custom-days/')->withFlash_message("Guardado Exitosamente");
            }
            else
            {
              return Redirect::to('/doctor/custom-days/'.$id.'/edit')->withErrors("Error")->withInput();      
            }
         
        }
    }

    public function destroy($id)
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');
       
      $CustomDay = CustomDay::find($id);
      $CustomDay->delete();
      if($CustomDay){
        return Redirect::back()->withConfirm("Eliminado Exitosamente");
      }else{
        return Redirect::back()->withErrors("Error");
      }
    }
}