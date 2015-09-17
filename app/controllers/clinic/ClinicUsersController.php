<?php

class ClinicUsersController extends \BaseController {



	 public function index()
	 {
      if(Payment::VeryPayment()==false)
         return View::make('clinic.payment.renews-payment');
         
         return View::make('clinic.users.index');
   }

   public function create()
   {
       if(Payment::VeryPayment()==false)
          return View::make('clinic.payment.renews-payment');

       return View::make('clinic.users.new');
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
            return Redirect::to('/clinic/users/create')->withErrors($validation)->withInput();
        }
        else
        {   
            $user = new User;
            $user->first_name = Input::get("first_name");
            $user->last_name  = Input::get("last_name");
            $user->email      = Input::get("email");
            $user->username   = Input::get("username");
            $user->password   = Input::get("password");
            $user->activated  = 1;
            $user->save();

            $doctorUser    = Sentry::getUserProvider()->findByLogin($user->email);
            $doctorsyGroup = Sentry::getGroupProvider()->findByName('Receptionist');
            $doctorUser->addGroup($doctorsyGroup);
            
            $Receptionist = new UserReceptionist;
            $Receptionist->user_id      = $user->id;
            $Receptionist->clinic_id    = Clinic::getClinicId();

            if(Input::file('picture')!=NULL)
            {
                //agrega imagen de logo
                $file_logo=Input::file('picture');
                $ext = Input::file('picture')->getClientOriginalExtension();
                $nameIMG=date('YmdHis');
                $logo=$nameIMG.'.'.$ext;
                $logo= 'assets/doctor/images/profile_pic/profile_'.$logo;
                $file_logo->move("assets/doctor/images/profile_pic/",$logo);                 
            }

            $Receptionist->save();

            if(!$logo){ $logo ="";}

            $profile = new Profile;
            $profile->user_id = $user->id;
            $profile->lang    = Input::get("lang");
            $profile->phone   = Input::get("phone");
            $profile->picture = $logo;
            $profile->save();

            if($profile){                
                return Redirect::to('/clinic/users/')->withFlash_message("Guardado Exitosamente");
            }
            else
            {
              return Redirect::to('/clinic/users/create')->withErrors("Error")->withInput();      
            }
         
        }
   }

    public function edit($id)
    {
      $user=User::find($id);
      $profile = Profile::where('user_id',$id)->first();
      return View::make('clinic.users.edit',['user'=>$user, 'profile'=>$profile]);
    }

    public function update($id)
    {
        $data = array(
            "first_name"     =>  Input::get("first_name"),
            "last_name"      =>  Input::get("last_name"),
            "email"          =>  Input::get("email"),
            "phone"          =>  Input::get("phone"),
            "picture"        =>  Input::file("picture"),
            "password"       =>  Input::get("password"),
            "password_confirmation"     =>  Input::get("password_confirmation"),
        );
        
        if(Input::get("password")!=""){
          $rules = array(
            "first_name"     =>  'required|min:1|max:255',
            "last_name"      =>  'required|min:1|max:100',
            "email"          =>  'required|email',
            "phone"          =>  'required|min:1|max:100',
            "picture"        =>  'mimes:jpeg,gif,png',
            'password'       =>  'confirmed|min:6',
          );
        }else{
          $rules = array(
            "first_name"     =>  'required|min:1|max:255',
            "last_name"      =>  'required|min:1|max:100',
            "email"          =>  'required|email',
            "phone"          =>  'required|min:1|max:100',
            "picture"        =>  'mimes:jpeg,gif,png',
          );
        }
        

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
            return Redirect::to('/clinic/users/'.$id.'/edit')->withErrors($validation)->withInput();
        }
        else
        {   

            $user = User::find($id);
            $user->first_name = Input::get("first_name");
            $user->last_name  = Input::get("last_name");
            $user->email      = Input::get("email");
            
            if(Input::get("password")!="")
              $user->password   = Input::get("password");

            $user->save();
            $profile = Profile::where('user_id', $id)->first();

            if(Input::file('picture')!=NULL)
            {
                //agrega imagen de logo
                $file_logo=Input::file('picture');
                $ext = Input::file('picture')->getClientOriginalExtension();
                $nameIMG=date('YmdHis');
                $logo=$nameIMG.'.'.$ext;
                $logo= 'assets/doctor/images/profile_pic/profile_'.$logo;
                $file_logo->move("assets/doctor/images/profile_pic/",$logo);                 
                $profile->picture = $logo;
            }

            $profile->lang    = Input::get("lang");
            $profile->phone   = Input::get("phone");
            
            $profile->save();

            if($profile){                
                return Redirect::to('/clinic/users/')->withFlash_message("Guardado Exitosamente");
            }
            else
            {
              return Redirect::to('/clinic/users/'.$id.'/edit')->withErrors("Error")->withInput();      
            }
         
        }
      }
        public function destroy($id)
        {
          $doctor = UserReceptionist::find($id);
          $userId   = $doctor->user_id;
          $doctor->delete();
          $group = Group::where('user_id', $userId)->first();
          $group->group_id = 1;
          $group->save();
    
          if($doctor){
            return Redirect::back()->withConfirm("Eliminado Exitosamente");
          }else{
            return Redirect::back()->withErrors("Error");
          }
        }
}