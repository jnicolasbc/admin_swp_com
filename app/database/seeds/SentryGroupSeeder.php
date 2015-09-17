<?php

class SentryGroupSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('groups')->truncate();

		Sentry::getGroupProvider()->create(array(
	        'name'        => 'Patients',
	        ));

		Sentry::getGroupProvider()->create(array(
	        'name'        => 'Doctors',
	        ));

		Sentry::getGroupProvider()->create(array(
	        'name'        => 'Clinics',
	        ));
		Sentry::getGroupProvider()->create(array(
	        'name'        => 'Admins',
	        ));
	}

}