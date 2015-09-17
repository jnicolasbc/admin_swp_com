<?php

class SentryUserSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users')->truncate();
		DB::table('patients')->truncate();
		DB::table('doctors')->truncate();
		DB::table('clinics')->truncate();

		Sentry::getUserProvider()->create(array(
	        'email'    => 'admin@skywebplus.com',
	        'password' => '123',
	        'username' => 'diego10',
	        'first_name' => 'Diego',
	        'last_name' => 'Serras',
	        'activated' => 1,
	    ));	

		$c1 = Sentry::getUserProvider()->create(array(
	        'email'    => 'clinic1@skywebplus.com',
	        'password' => '123',
	        'username' => 'jose10',
	        'first_name' => 'Jose',
	        'last_name' => 'Gonzalez',
	        'activated' => 1,
	    ));
	    	$c1_ = new Clinic;
	    	$c1_->user_id = $c1->id;
	    	$c1_->name="Clinica Medica Raul Ortega";
	    	$c1_->save();


	    $c2=Sentry::getUserProvider()->create(array(
	        'email'    => 'clinic2@skywebplus.com',
	        'password' => '123',
	        'username' => 'alber10',
	        'first_name' => 'Alberto',
	        'last_name' => 'Perdomo',
	        'activated' => 1,
	    ));
	    	$c2_ = new Clinic;
	    	$c2_->user_id = $c2->id;
	    	$c2_->name="Clinica Medica Jose Pineda";
	    	$c2_->save();


	    $d1 = Sentry::getUserProvider()->create(array(
	        'email'    => 'doctor1@skywebplus.com',
	        'password' => '123',
	        'username' => 'martha10',	        
	        'first_name' => 'Martha',
	        'last_name' => 'Mendoza',
	        'activated' => 1,
	    ));

	    	$d1_ = new Doctor;
	    	$d1_->user_id = $d1->id;
	    	$d1_->clinic_id= $c2_->id ;
	    	$d1_->save();

	    $d2 = Sentry::getUserProvider()->create(array(
	        'email'    => 'doctor2@skywebplus.com',
	        'password' => '123',
	        'username' => 'jairo10',	        
	        'first_name' => 'Jairo',
	        'last_name' => 'Flores',
	        'activated' => 1,
	    ));

	    	$d2_ = new Doctor;
	    	$d2_->user_id = $d2->id;
	    	$d2_->clinic_id= $c2_->id ;
	    	$d2_->save();

	    $d3=Sentry::getUserProvider()->create(array(
	        'email'    => 'doctor3@skywebplus.com',
	        'password' => '123',
	        'username' => 'gabriela10',	        
	        'first_name' => 'Gabriela',
	        'last_name' => 'del Cid',
	        'activated' => 1,
	    ));
	    	$d3_ = new Doctor;
	    	$d3_->user_id = $d3->id;
	    	$d3_->clinic_id= $c2_->id;
	    	$d3_->save();

	    $d4 = Sentry::getUserProvider()->create(array(
	        'email'    => 'doctor4@skywebplus.com',
	        'password' => '123',
	        'username' => 'keiry10',	        
	        'first_name' => 'Keiry',
	        'last_name' => 'Rivas',
	        'activated' => 1,
	    ));
	    	$d4_ = new Doctor;
	    	$d4_->user_id = $d4->id;
	    	$d4_->save();

	    $d5 = Sentry::getUserProvider()->create(array(
	        'email'    => 'doctor5@skywebplus.com',
	        'password' => '123',
	        'username' => 'mabel10',	        
	        'first_name' => 'Mabel',
	        'last_name' => 'Mendoza',
	        'activated' => 1,
	    ));
	    	$d5_ = new Doctor;
	    	$d5_->user_id = $d5->id;
	    	$d5_->save();

	    $p1 = Sentry::getUserProvider()->create(array(
	        'email'    => 'patient1@skywebplus.com',
	        'password' => '123',
	        'username' => 'John10',	        	        
	        'first_name' => 'Jonh',
	        'last_name' => 'Smith',
	        'activated' => 1,
	    ));
	    	$p1_ = new Patient;
	    	$p1_->user_id = $p1->id;
	    	$p1_->save();

	    $p2=Sentry::getUserProvider()->create(array(
	        'email'    => 'patient2@skywebplus.com',
	        'password' => '123',
	        'username' => 'Orlando10',	        	        
	        'first_name' => 'Orlando',
	        'last_name' => 'Arias',
	        'activated' => 1,
	    ));
	    	$p2_ = new Patient;
	    	$p2_->user_id = $p2->id;
	    	$p2_->main= $p1_->id;
	    	$p2_->relationship= 'son';
	    	$p2_->save();
	    
	    $p3= Sentry::getUserProvider()->create(array(
	        'email'    => 'patient3@skywebplus.com',
	        'password' => '123',
	        'username' => 'Alejandra10',	        	        
	        'first_name' => 'Alejandra',
	        'last_name' => 'Cerna',
	        'activated' => 1,
	    ));
	    	$p3_ = new Patient;
	    	$p3_->user_id = $p3->id;
	    	$p3_->main = $p1_->id;
	    	$p3_->relationship= 'daughter';
	    	$p3_->save();

		$p4=Sentry::getUserProvider()->create(array(
	        'email'    => 'patient4@skywebplus.com',
	        'password' => '123',
	        'username' => 'Maria10',	        	        
	        'first_name' => 'Maria',
	        'last_name' => 'Polio',
	        'activated' => 1,
	    ));
			$p4_ = new Patient;
	    	$p4_->user_id = $p4->id;
	    	$p3_->main=  $p1_->id;
	    	$p3_->relationship= 'wife';
	    	$p4_->save();

	    $p5=Sentry::getUserProvider()->create(array(
	        'email'    => 'patient5@skywebplus.com',
	        'password' => '123',
	        'username' => 'Rodrigo10',	        	        
	        'first_name' => 'Rodrigo',
	        'last_name' => 'Jandres',
	        'activated' => 1,
	    )); 
	    	$p5_ = new Patient;
	    	$p5_->user_id = $p5->id;
	    	$p5_->save();
	}

}