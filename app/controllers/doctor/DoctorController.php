<?php

class DoctorController extends \BaseController {


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getHome()
	{
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

		return View::make('clinic.doctor.dashboard.index');
	}

	public function index()
	{
    if(Payment::VeryPayment()==false)
       return View::make('clinic.payment.renews-payment');

    $user = Sentry::getUser();
    $doctor = Doctor::where('user_id',$user->id)->first();
    $agenda = Agenda::where('doctor_id',$doctor->id)->first();
    $esps = explode(',', $doctor->specialty_id);
    $espok = '';

    foreach($esps as $esp){
      $very = Specialty::where('id', $esp)->first();
      if($very){
        $espok = $espok.','.$very->name_es;
      }
    }

    $profile = Profile::where('user_id',$doctor->user_id)->first();

    return View::make('clinic.doctor.config.edit',['agenda'=>$agenda, 'doctor'=>$doctor, 'profile'=>$profile, 'specialty'=>$espok]);
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
            return Redirect::to('/doctor/profile')->withErrors($validation)->withInput();
        }
        else
        { 
            $doctor = Doctor::find($id);
            $agenda = Agenda::where('doctor_id',$doctor->id)->first();
            $agenda->dating_duration = Input::get("dating_duration");
            $agenda->save();
            $espes = explode(',', Input::get("specialty_id"));
            $espeuok = '';
            foreach($espes as $espe){
              $very = Specialty::where('name_es', $espe)->first();
              if($very){
                $espeuok = $espeuok.','.$very->id;
              }
            }
            $doctor->specialty_id = $espeuok;
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
                return Redirect::to('/doctor/profile')->withFlash_message("Guardado Exitosamente");
            }
            else
            {
              return Redirect::to('/doctor/profile')->withErrors("Error")->withInput();      
            }
         
        }
	}

    public function getConfig()
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');

        $id = Doctor::doctorLogin();
        $doctor =Doctor::where('id',$id)->first();
        $option      = Option::where('name', $doctor->id.'-doctor-insurance')->first();
        $optionLang  = Option::where('name', $doctor->id.'-doctor-lang')->first();
        if(!$option){
             return View::make('clinic.doctor.config.config')->with('errors', 'Error');
        }else{
             return View::make('clinic.doctor.config.config')
                        ->with('option', $option)
                        ->with('optionLang', $optionLang);
        }
    }

    public function getConfigSave()
    {
        if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');
       
        $data = array(
            "insurance" =>  Input::get("insurance"),
            "lang"      =>  Input::get("lang")
        );
    
        $rules = array(
            "insurance" =>  'required|min:1|max:100',
            "lang"      =>  'required|min:1|max:100'
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
        if ($validation->fails()){
            return Redirect::to('/clinic/config-data/')->withErrors($validation)->withInput();
        }else{ 
            $id = Doctor::doctorLogin();
            $doctor =Doctor::where('id',$id)->first();
            
            $lang = Option::where('name', $id.'-doctor-lang')->first();
            if($lang){
                $lang->key = Input::get("lang");
                $lang->save();
            }else{
                $langadd = new Option;
                $langadd->key = Input::get("lang");
                $langadd->name = $id.'-doctor-lang';
                $langadd->save();
            }

            $opcionSeg = Option::where('name', $id.'-doctor-insurance')->first();
            if($opcionSeg){
                $segs = explode(',', Input::get("insurance"));
                $segok = '';
                foreach($segs as $seg){
                    $very = Insurance::where('name', 'like', '%'.$seg.'%')->first();
                    if($very){
                        $segok = $segok.','.$seg;
                    }
                }
                $opcionSeg->key = $segok;
                $opcionSeg->save();
            }else{
                $segs = explode(',', Input::get("insurance"));
                $seguok = '';
                foreach($segs as $seg){
                    $very = Insurance::where('name', $seg)->first();
                    if($very){
                        $seguok = $seguok.','.$seg;
                    }
                }
                $addseg = new Option;
                $addseg->name = $id.'-doctor-insurance';
                $addseg->key = $seguok;
                $addseg->save();
            }
            return Redirect::back();;
        }
    }
}