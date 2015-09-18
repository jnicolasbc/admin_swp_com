<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDoctorPatientTable extends Migration {

	public function up()
	{
		Schema::create('doctor_patient', function(Blueprint $table) {
			$table->integer('user_id');
			$table->integer('doctor_id');
		});
	}

	public function down()
	{
		Schema::drop('doctor_patient');
	}
}