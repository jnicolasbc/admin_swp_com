<?php

class SentryUserGroupSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users_groups')->truncate();

		$userUser1 = Sentry::getUserProvider()->findByLogin('patient1@skywebplus.com');
		$userUser2 = Sentry::getUserProvider()->findByLogin('patient2@skywebplus.com');
		$userUser3 = Sentry::getUserProvider()->findByLogin('patient3@skywebplus.com');
		$userUser4 = Sentry::getUserProvider()->findByLogin('patient4@skywebplus.com');
		$userUser5 = Sentry::getUserProvider()->findByLogin('patient5@skywebplus.com');
		
		$clinic1User = Sentry::getUserProvider()->findByLogin('clinic1@skywebplus.com');		
		$clinic2User = Sentry::getUserProvider()->findByLogin('clinic2@skywebplus.com');		
		
		$doctor1User = Sentry::getUserProvider()->findByLogin('doctor1@skywebplus.com');
		$doctor2User = Sentry::getUserProvider()->findByLogin('doctor2@skywebplus.com');
		$doctor3User = Sentry::getUserProvider()->findByLogin('doctor3@skywebplus.com');
		
		$adminUser = Sentry::getUserProvider()->findByLogin('admin@skywebplus.com');

		$userGroup = Sentry::getGroupProvider()->findByName('Patients');
		$doctorsyGroup = Sentry::getGroupProvider()->findByName('Doctors');
		$clinicGroup = Sentry::getGroupProvider()->findByName('Clinics');
		$adminGroup = Sentry::getGroupProvider()->findByName('Admins');

	    // Assign the groups to the users
	    $userUser1->addGroup($userGroup);
	    $userUser2->addGroup($userGroup);
	    $userUser3->addGroup($userGroup);
	    $userUser4->addGroup($userGroup);
	    $userUser5->addGroup($userGroup);
	    
	    $clinic1User->addGroup($clinicGroup);
	    $clinic2User->addGroup($clinicGroup);
	    
	    $doctor1User->addGroup($doctorsyGroup);
	    $doctor2User->addGroup($doctorsyGroup);
	    $doctor3User->addGroup($doctorsyGroup);

	    $adminUser->addGroup($adminGroup);
	}

}