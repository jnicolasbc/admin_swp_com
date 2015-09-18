<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMgsAppointmentTable extends Migration {

	public function up()
	{
		Schema::create('mgs_appointment', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('appointment_id')->index();
			$table->text('text');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('mgs_appointment');
	}
}